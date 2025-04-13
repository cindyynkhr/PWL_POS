<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\SupplierModel;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class SupplierController extends Controller
{
    public function index()
     {
         $breadcrumb = (object) [
             'title' => 'Daftar Supplier',
             'list' => ['Home', 'Supplier']
         ];
 
         $page = (object) [
             'title' => 'Daftar Supplier'
         ];
 
         $activeMenu = 'supplier';
 
         return view('supplier.index', compact('breadcrumb', 'page', 'activeMenu'));
     }
     
     //create_ajax
     public function create_ajax(Request $request){
        return view('supplier.create_ajax');
        //  $supplier = SupplierModel::select('supplier_id', 'supplier_kode', 'nama','nama_pt', 'alamat')->get();
 
        //  return view('supplier.create_ajax')->with('supplier', $supplier);
     }

     //store ajax
     public function store_ajax(Request $request){
         //cek apakah request berupa ajax
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'supplier_kode' => 'required|max:10|unique:m_supplier,supplier_kode',
                 'nama' => 'required|max:100',
                 'nama_pt' => 'required|max:100',
                 'alamat' => 'required|max:100',
             ];
 
             $validator = Validator::make($request->all(), $rules);
 
            //  if ($validator->fails()) {
            //      return response()->json(['errors' => $validator->errors()]);
            //  }
                if ($validator->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Validasi Gagal',
                        'msgField' => $validator->errors(),
                    ]);
                }

                SupplierModel::create($request->all());
    
                return response()->json([
                    'status' => true,
                    'message' => 'Data supplier berhasil disimpan'
                ]);
 
            //  $supplier = SupplierModel::create([
            //      'supplier_kode' => $request->supplier_kode,
            //      'nama' => $request->nama,
            //      'nama_pt' => $request->nama_pt,
            //      'alamat' => $request->alamat,
            //  ]);
 
             // return response()->json(['success' => 'Data supplier berhasil disimpan']);
         }
 
         return redirect('/supplier');
     }

     public function list(Request $request)
     {
         $suppliers = SupplierModel::select('supplier_id', 'supplier_kode', 'nama', 'nama_pt', 'alamat');
 
         return DataTables::of($suppliers)
             ->addIndexColumn()
             ->addColumn('aksi', function ($supplier) {
                $btn  = '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id.'/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                 $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id.'/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> '; 
                 $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id.'/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> '; 
            
                 return $btn;
            })
             ->rawColumns(['aksi'])
             ->make(true);
     }

     //menampilkan halaman update ajax
     public function update_ajax(Request $request, $id){
        //cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_kode' => 'required|max:10|unique:m_supplier,supplier_kode,'.$id.',supplier_id',
                'nama' => 'required|max:100',
                'nama_pt' => 'required|max:100',
                'alamat' => 'required|max:100',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }
            $check = SupplierModel::find($id);
            if ($check) {
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
        return redirect('/supplier');
    }

    //confirm ajax
    public function confirm_ajax(Request $request, $id){
        $supplier = SupplierModel::find($id);
        return view('supplier.confirm_ajax', ['supplier' => $supplier]);
    }

    //delete ajax
    public function delete_ajax(Request $request, $id){
        //cek apakah request dari ajax?
        if ($request->ajax() || $request->wantsJson()) {
            $supplier = SupplierModel::find($id);
            if ($supplier) {
                $supplier->delete();
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
        return redirect('/supplier');
    }

     //menampilkan halaman form edit ajax
     public function edit_ajax($id){
         $supplier = SupplierModel::find($id);
    
 
         return view('supplier.edit_ajax')->with('supplier', $supplier);
     }

     public function import() 
     { 
         return view('supplier.import'); 
     }
 
     //import ajax
     public function import_ajax(Request $request) 
    { 
        if($request->ajax() || $request->wantsJson()){ 
            $rules = [ 
                // validasi file harus xls atau xlsx, max 1MB 
                'file_supplier' => ['required', 'mimes:xlsx', 'max:1024'] 
            ]; 
 
            $validator = Validator::make($request->all(), $rules); 
            if($validator->fails()){ 
                return response()->json([ 
                    'status' => false, 
                    'message' => 'Validasi Gagal', 
                    'msgField' => $validator->errors() 
                ]); 
            } 
            
            $file = $request->file('file_supplier');  // ambil file dari request 
 
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
                            'supplier_kode' => $value['A'], 
                            'nama' => $value['B'], 
                            'nama_pt' => $value['C'], 
                            'alamat' => $value['D'], 
                            'created_at' => now(),
                            'updated_at' => now() 
                        ]; 
                    } 
                } 
 
                if(count($insert) > 0){ 
                    // insert data ke database, jika data sudah ada, maka diabaikan 
                    supplierModel::insertOrIgnore($insert);    
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
        return redirect('/supplier'); 
    } 

    public function export_excel()
   {
       $supplier= supplierModel::select('supplier_kode','nama','nama_pt','alamat')
                   ->orderBy('supplier_id')
                   ->get();

       //load library excel
       $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
       $sheet = $spreadsheet->getActiveSheet();

       $sheet->setCellValue('A1', 'No');
       $sheet->setCellValue('B1', 'Kode supplier');
       $sheet->setCellValue('C1', 'Nama supplier');
       $sheet->setCellValue('D1', 'Nama PT');
       $sheet->setCellValue('E1', 'Alamat');


       $sheet->getStyle('A1:E1')->getFont()->setBold(true);
       
       $no =1;
       $baris = 2;
       foreach ($supplier as $value) {
           $sheet->setCellValue('A'.$baris, $no++);
           $sheet->setCellValue('B'.$baris, $value->supplier_kode);
           $sheet->setCellValue('C'.$baris, $value->nama);
           $sheet->setCellValue('D'.$baris, $value->nama_pt);
           $sheet->setCellValue('E'.$baris, $value->alamat);
           $baris++;
           $no++;
       }
       foreach (range('A', 'E') as $columnID) {
           $sheet->getColumnDimension($columnID)->setAutoSize(true);
       }

       $sheet->setTitle('Data supplier');
       $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
       $filename = 'Data supplier '. date('Y-m-d H:i:s') .'.xlsx';

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
       
       $supplier = SupplierModel::select('supplier_kode','nama','nama_pt','alamat')
                   ->orderBy('supplier_id')
                   ->get();
       $pdf = PDF::loadView('supplier.export_pdf', ['supplier' => $supplier]);
       $pdf->setPaper('A4', 'potrait');
       $pdf->setOption("isRemoteEnabled", true);
       $pdf->render();

       return $pdf->stream('Data Supplier '.date('Y-m-d H:i:s').'.pdf');
   }

     public function create()
     {
         $breadcrumb = (object) [
             'title' => 'Tambah Supplier',
             'list' => ['Home', 'Supplier', 'Tambah']
         ];
 
         $page = (object) [
             'title' => 'Tambah Data Supplier'
         ];
 
         $activeMenu = 'supplier';
 
         return view('supplier.create', compact('breadcrumb', 'page', 'activeMenu'));
     }
 
     public function store(Request $request)
     {
         $request->validate([
             'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode',
             'nama' => 'required|string|max:255',
             'nama_pt' => 'required|string|max:255',
             'alamat' => 'required|string|max:255',
         ]);
 
         SupplierModel::create($request->all());
 
         return redirect('/supplier')->with('success', 'Data supplier berhasil disimpan');
     }
 
     public function show($id)
     {
         $breadcrumb = (object) [
             'title' => 'Detail Supplier',
             'list' => ['Home', 'Supplier', 'Detail']
         ];
 
         $page = (object) [
             'title' => 'Detail Data Supplier'
         ];
 
         $supplier = SupplierModel::findOrFail($id);
         $activeMenu = 'supplier';
 
         return view('supplier.show', compact('breadcrumb', 'page', 'supplier', 'activeMenu'));
     }
 
     public function edit($id)
     {
         $breadcrumb = (object) [
             'title' => 'Edit Supplier',
             'list' => ['Home', 'Supplier', 'Edit']
         ];
 
         $page = (object) [
             'title' => 'Edit Data Supplier'
         ];
 
         $supplier = SupplierModel::findOrFail($id);
         $activeMenu = 'supplier';
 
         return view('supplier.edit', compact('breadcrumb', 'page', 'supplier', 'activeMenu'));
     }
 
     public function update(Request $request, $id)
     {
         $request->validate([
             'supplier_kode' => 'required|string|max:10|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
             'nama' => 'required|string|max:255',
             'nama_pt' => 'required|string|max:255',
             'alamat' => 'required|string|max:255',
         ]);
 
         $supplier = SupplierModel::findOrFail($id);
         $supplier->update($request->all());
 
         return redirect('/supplier')->with('success', 'Data supplier berhasil diubah');
     }
 
     public function destroy($id)
     {
         SupplierModel::findOrFail($id)->delete();
         return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus');
     }
}
