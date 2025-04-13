<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
 use Yajra\DataTables\Facades\DataTables;
 use Illuminate\Support\Facades\Validator;
 use PhpOffice\PhpSpreadsheet\IOFactory;
 use Barryvdh\DomPDF\Facade\Pdf;

class KategoriController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Kategori',
            'list' => ['Home', 'Kategori']
        ];

        $page = (object) [
            'title' => 'Daftar Kategori Barang'
        ];

        $activeMenu = 'kategori';

        return view('kategori.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    //create_ajax
    public function create_ajax(Request $request){
        return view('kategori.create_ajax');

        // $kategori = KategoriModel::select('id_kategori', 'kategori_kode', 'kategori_nama')->get();
        // return view('kategori.create_ajax')->with('kategori', $kategori);
    }

    //store_ajax
    public function store_ajax(Request $request){
      //cek apakah request berupa ajax
      if ($request->ajax() || $request->wantsJson()) {
          $rules = [
              'kategori_kode' => 'required|max:10|unique:m_kategori,kategori_kode',
              'kategori_nama' => 'required|max:100'
          ];

          // validasi
          $validator = Validator::make($request->all(), $rules);

          if ($validator->fails()){
              return response()->json([
                  'status' => false, // respon json, true: berhasil, false: gagal
                  'message' => 'Validasi gagal.',
                  'msgField' => $validator->errors() // menunjukkan field mana yang error
              ]);
          }


          KategoriModel::create($request->all());
          return response()->json([
              'status' => true,
              'message' => 'Data berhasil disimpan'
          ]);
      }
      return redirect('/kategori');
    }

    public function list(Request $request)
    {
        $kategori = KategoriModel::select('id_kategori', 'kategori_kode', 'kategori_nama');
        
        return DataTables::of($kategori)
            ->addIndexColumn()
            ->addColumn('aksi', function ($kategori) {
                 $btn  = '<button onclick="modalAction(\''.url('/kategori/' . $kategori->id_kategori. '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                 $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->id_kategori. '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> '; 
                 $btn .= '<button onclick="modalAction(\''.url('/kategori/' . $kategori->id_kategori. '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> '; 
                
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    //update_ajax
    public function update_ajax(Request $request, $id){
        //cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_kode' => 'required|max:10|unique:m_kategori,kategori_kode,'.$id.',id_kategori',
                'kategori_nama' => 'required|max:100'
            ];

            // validasi
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }
            $check = KategoriModel::find($id);
            if ($check) {
                if (!$request->filled('password')) { // jika password tidak diisi, maka hapus dari request
                    $request->request->remove('password');
                }
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/kategori');    
    }
    
    //confirm ajax
    public function confirm_ajax(string $id){
        $kategori = KategoriModel::find($id);
        return view('kategori.confirm_ajax', ['kategori' => $kategori]);
    }

    //delete_ajax
    public function delete_ajax(Request $request, $id){
        //cek apakah request dari ajax?
        if ($request->ajax() || $request->wantsJson()) {
            $kategori = KategoriModel::find($id);
            if ($kategori) {
                $kategori->delete();
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
        return redirect('/kategori');
    }

    //menampilkan halaman form edit user ajax
    public function edit_ajax(string $id){
        $kategori = KategoriModel::find($id);
        //$kategori = KategoriModel::select('id_kategori', 'kategori_kode', 'kategori_nama')->get();

        return view('kategori.edit_ajax', ['kategori' => $kategori]);
    }

    public function import() 
    { 
        return view('kategori.import'); 
    }

    //import ajax
    public function import_ajax(Request $request) 
   { 
       if($request->ajax() || $request->wantsJson()){ 
           $rules = [ 
               // validasi file harus xls atau xlsx, max 1MB 
               'file_kategori' => ['required', 'mimes:xlsx', 'max:1024'] 
           ]; 

           $validator = Validator::make($request->all(), $rules); 
           if($validator->fails()){ 
               return response()->json([ 
                   'status' => false, 
                   'message' => 'Validasi Gagal', 
                   'msgField' => $validator->errors() 
               ]); 
           } 
           
           $file = $request->file('file_kategori');  // ambil file dari request 

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
                           'kategori_kode' => $value['A'], 
                           'kategori_nama' => $value['B'], 
                           'created_at' => now(),
                           'updated_at' => now() 
                       ]; 
                   } 
               } 

               if(count($insert) > 0){ 
                   // insert data ke database, jika data sudah ada, maka diabaikan 
                   kategoriModel::insertOrIgnore($insert);    
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
       return redirect('/kategori'); 
   } 

   public function export_excel()
   {
       $kategori = kategoriModel::select('kategori_kode','kategori_nama')
                   ->orderBy('id_kategori')
                   ->get();

       //load library excel
       $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
       $sheet = $spreadsheet->getActiveSheet();

       $sheet->setCellValue('A1', 'No');
       $sheet->setCellValue('B1', 'Kode kategori');
       $sheet->setCellValue('C1', 'Nama kategori');

       $sheet->getStyle('A1:C1')->getFont()->setBold(true);
       
       $no =1;
       $baris = 2;
       foreach ($kategori as $value) {
           $sheet->setCellValue('A'.$baris, $no++);
           $sheet->setCellValue('B'.$baris, $value->kategori_kode);
           $sheet->setCellValue('C'.$baris, $value->kategori_nama);
           $baris++;
           $no++;
       }
       foreach (range('A', 'C') as $columnID) {
           $sheet->getColumnDimension($columnID)->setAutoSize(true);
       }

       $sheet->setTitle('Data kategori');
       $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
       $filename = 'Data kategori '. date('Y-m-d H:i:s') .'.xlsx';

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
       
       $kategori = KategoriModel::select('kategori_kode','kategori_nama')
                   ->orderBy('id_kategori')
                   ->get();
       $pdf = PDF::loadView('kategori.export_pdf', ['kategori' => $kategori]);
       $pdf->setPaper('A4', 'potrait');
       $pdf->setOption("isRemoteEnabled", true);
       $pdf->render();

       return $pdf->stream('Data kategori '.date('Y-m-d H:i:s').'.pdf');
   }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Kategori',
            'list' => ['Home', 'Kategori', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Kategori Barang'
        ];

        $activeMenu = 'kategori';

        return view('kategori.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'kategori_kode' => 'required|max:10|unique:m_kategori,kategori_kode',
            'kategori_nama' => 'required|max:100',
        ], [
            'kategori_kode.required' => 'Kode kategori wajib diisi',
            'kategori_kode.max' => 'Kode kategori maksimal 10 karakter',
            'kategori_kode.unique' => 'Kode kategori sudah digunakan',
            'kategori_nama.required' => 'Nama kategori wajib diisi',
            'kategori_nama.max' => 'Nama kategori maksimal 100 karakter',
        ]);

        if ($validator->fails()) {
            return redirect('/kategori/create')
                ->withErrors($validator)
                ->withInput();
        }

        $kategori = new KategoriModel();
        $kategori->kategori_kode = $request->kategori_kode;
        $kategori->kategori_nama = $request->kategori_nama;
        $kategori->save();

        return redirect('/kategori')->with('success', 'Data kategori berhasil disimpan');
    }

    public function show($id)
    {
        $breadcrumb = (object) [
            'title' => 'Detail Kategori',
            'list' => ['Home', 'Kategori', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Kategori Barang'
        ];

        $activeMenu = 'kategori';

        $kategori = KategoriModel::find($id);
        
        return view('kategori.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    public function edit($id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Kategori',
            'list' => ['Home', 'Kategori', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Kategori Barang'
        ];

        $activeMenu = 'kategori';

        $kategori = KategoriModel::find($id);
        
        return view('kategori.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kategori_kode' => 'required|max:10|unique:m_kategori,kategori_kode,'.$id.',id_kategori',
            'kategori_nama' => 'required|max:100',
        ]);

        $kategori = KategoriModel::find($id);
        $kategori->kategori_kode = $request->kategori_kode;
        $kategori->kategori_nama = $request->kategori_nama;
        $kategori->save();

        return redirect('/kategori')->with('success', 'Data kategori berhasil diubah');
    }

    public function destroy($id)
    {
        $kategori = KategoriModel::find($id);

        if (!$kategori) {
            return redirect('/kategori')->with('error', 'Data kategori tidak ditemukan');
        }
        
        try {
            $kategori->delete();
            return redirect('/kategori')->with('success', 'Data kategori berhasil dihapus');
        } catch (\Exception $e) {
            return redirect('/kategori')->with('error', 'Data kategori gagal dihapus karena masih digunakan');
        }
    }
}
// $data = [
//     'kategori_kode'=>'SNK',
//     'kategori_nama'=>'Snack/Makanan Ringan',
//     'created_at'=>now(),
// ];

// DB::table('m_kategori')->insert($data);
// return 'insert data berhasil';

// $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->update(['kategori_nama' => 'Camilan']);
// return 'Update data berhasil. Jumlah data yang diupdate : '.$row.' baris';

// $row = DB::table('m_kategori')->where('kategori_kode', 'SNK')->delete();
// return 'Delete data berhasil. Jumlah data yang dihapus : '.$row.' baris';

// $data = DB::table('m_kategori')->get();
// return view('kategori',['data' => $data]);
