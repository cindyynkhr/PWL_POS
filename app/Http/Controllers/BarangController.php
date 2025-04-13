<?php

namespace App\Http\Controllers;

use App\Models\LevelModel; 
use App\Models\BarangModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; 
 use Yajra\DataTables\Facades\DataTables;
 use PhpOffice\PhpSpreadsheet\IOFactory;
 use Illuminate\Support\Facades\Validator;
 use Barryvdh\DomPDF\Facade\Pdf;
 

class BarangController extends Controller
{
    public function index()
    {
        $activeMenu = 'barang'; 
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];

        $kategori = KategoriModel::select('id_kategori', 'kategori_nama')->get(); 
        return view('barang.index', [ 
            'activeMenu' => $activeMenu, 
            'breadcrumb' => $breadcrumb, 
            'kategori' => $kategori 
        ]); 
    }

    //create_ajax
    public function create_ajax(Request $request){
        $kategoris = KategoriModel::select('id_kategori', 'kategori_nama')->get();

        return view('barang.create_ajax')->with('kategori', $kategoris);
    }

    //store_ajax
    public function store_ajax(Request $request){
        if ($request->ajax() || $request->wantsJson()) {
            $rules=[
                'id_kategori' => 'required|integer',
                'barang_kode' => 'required|max:10|unique:m_barang,barang_kode',
                'barang_nama' => 'required|max:100',
                'harga_beli' => 'required|min:0',
                'harga_jual' => 'required|min:0'
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }
            BarangModel::create($request->all());

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan'
            ]);
        }
        return redirect('/barang');
    }
    
    public function list(Request $request)
    {
        $barangs = BarangModel::with('kategori')->select('barang_id', 'id_kategori', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual');

        $id_kategori = $request->input('filter_kategori'); 
        if(!empty($id_kategori)){ 
            $barangs->where('id_kategori', $id_kategori); 
        }

        return DataTables::of($barangs)
            ->addIndexColumn()
            ->addColumn('aksi', function ($barang) {
                $btn  = '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> '; 
                
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    //update_ajax
    public function update_ajax(Request $request, $id){
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'id_kategori' => ['required', 'integer', 'exists:m_kategori,id_kategori'], 
                'barang_kode' => ['required', 'min:3', 'max:20', 'unique:m_barang,barang_kode, '. $id .',barang_id'], 
                'barang_nama' => ['required', 'string', 'max:100'], 
                'harga_beli' => ['required', 'numeric'], 
                'harga_jual' => ['required', 'numeric'], 
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }

            $check = BarangModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan',
                ]);
            }
        }    
        return redirect('/barang');
    }

    //confirm ajax
    public function confirm_ajax(Request $request, $id){
        $barang = BarangModel::find($id);

        return view('barang.confirm_ajax', ['barang' => $barang]);
    }

    //delete ajax
    public function delete_ajax(Request $request, $id){
        if ($request->ajax() || $request->wantsJson()) {
            $barang = BarangModel::find($id);
            if ($barang) {
                $barang->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/barang');
    }

    //menampilkan halaman form edit user ajax
    public function edit_ajax(string $id){
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::select('id_kategori', 'kategori_nama')->get();

        return view('barang.edit_ajax', ['barang' => $barang, 'kategori' => $kategori]);
    }

    //import
    public function import() 
    { 
        return view('barang.import'); 
    } 

    //import ajax
    public function import_ajax(Request $request) 
    { 
        if($request->ajax() || $request->wantsJson()){ 
            $rules = [ 
                // validasi file harus xls atau xlsx, max 1MB 
                'file_barang' => ['required', 'mimes:xlsx', 'max:1024'] 
            ]; 
 
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Validasi Gagal', 
                    'msgField' => $validator->errors() 
                ]); 
            } 
            
            $file = $request->file('file_barang');  // ambil file dari request 
 
            $reader = IOFactory::createReader('Xlsx');  // load reader file excel 
            $reader->setReadDataOnly(true);             // hanya membaca data 
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel 
            $sheet = $spreadsheet->getActiveSheet();    // ambil sheet yang aktif 
 
            $data = $sheet->toArray(null, false, true, true);   // ambil data excel 
 
            $insert = []; 
            if(count($data) > 1){ // jika data lebih dari 1 baris 
                foreach ($data as $baris => $value) { 
                    if($baris > 1){ // baris ke 1 adalah header, maka lewati 
                        $insert[] = [ 
                            'id_kategori' => $value['A'], 
                            'barang_kode' => $value['B'], 
                            'barang_nama' => $value['C'], 
                            'harga_beli' => $value['D'], 
                            'harga_jual' => $value['E'], 
                            'created_at' => now(), 
                        ]; 
                    } 
                } 
 
                if(count($insert) > 0){ 
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    BarangModel::insertOrIgnore($insert);    
                } 
 
                return response()->json([ 
                    'status' => true, 
                    'message' => 'Data berhasil diimport' 
                ]); 
            }else{ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Tidak ada data yang diimport' 
                ]); 
            } 
        } 
        return redirect('/barang'); 
    } 

    //Export Excel 
    public function export_excel()
    {
        $barang = BarangModel::select('id_kategori','barang_kode','barang_nama','harga_beli','harga_jual')
                    ->orderBy('id_kategori')
                    ->with('kategori')
                    ->get();

        //load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Harga Beli');
        $sheet->setCellValue('E1', 'Harga Jual');
        $sheet->setCellValue('F1', 'Kategori');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);
        
        $no =1;
        $baris = 2;
        foreach ($barang as $value) {
            $sheet->setCellValue('A'.$baris, $no++);
            $sheet->setCellValue('B'.$baris, $value->barang_kode);
            $sheet->setCellValue('C'.$baris, $value->barang_nama);
            $sheet->setCellValue('D'.$baris, $value->harga_beli);
            $sheet->setCellValue('E'.$baris, $value->harga_jual);
            $sheet->setCellValue('F'.$baris, $value->kategori->kategori_nama);
            $baris++;
            $no++;
        }
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Barang');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Barang '. date('Y-m-d H:i:s') .'.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf()
    {
        set_time_limit(300);
        
        $barang = BarangModel::select('id_kategori','barang_kode','barang_nama','harga_beli','harga_jual')
                    ->orderBy('id_kategori')
                    ->orderBy('barang_kode')
                    ->with('kategori')
                    ->get();
        $pdf = PDF::loadView('barang.export_pdf', ['barang' => $barang]);
        $pdf->setPaper('A4', 'potrait');
        $pdf->setOption("isRemoteEnabled", true);
        $pdf->render();

        return $pdf->stream('Data Kategori '.date('Y-m-d H:i:s').'.pdf');
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Data Barang'
        ];

        $kategori = KategoriModel::all();

        $activeMenu = 'barang';

        return view('barang.create', compact('breadcrumb', 'page', 'kategori', 'activeMenu'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_kategori' => 'required',
            'barang_kode' => 'required|unique:m_barang',
            'barang_nama' => 'required',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
        ]);

        BarangModel::create($request->all());

        return redirect('barang')->with('success', 'Barang berhasil ditambahkan');
    }

    public function show($id)
    {
        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Data Barang'
        ];

        $barang = BarangModel::with('kategori')->find($id);

        $activeMenu = 'barang';

        return view('barang.show', compact('breadcrumb', 'page', 'barang', 'activeMenu'));
    }

    public function edit($id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Barang',
            'list' => ['Home', 'Barang', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Data Barang'
        ];

        $barang = BarangModel::find($id);
        $kategori = KategoriModel::all();

        $activeMenu = 'barang';

        return view('barang.edit', compact('breadcrumb', 'page', 'barang', 'kategori', 'activeMenu'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kategori' => 'required',
            'barang_kode' => 'required|unique:m_barang,barang_kode,'.$id.',barang_id',
            'barang_nama' => 'required',
            'harga_beli' => 'required|numeric',
            'harga_jual' => 'required|numeric',
        ]);

        $barang = BarangModel::find($id);
        $barang->update($request->all());

        return redirect('barang')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy($id)
    {
        $barang = BarangModel::find($id);
        if ($barang) {
            $barang->delete();
            return redirect('barang')->with('success', 'Barang berhasil dihapus');
        }
        return redirect('barang')->with('error', 'Barang tidak ditemukan');
    }
}
