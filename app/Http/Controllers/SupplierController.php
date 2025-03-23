<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\SupplierModel;
use Illuminate\Support\Facades\Validator;

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
         $supplier = SupplierModel::select('supplier_id', 'supplier_kode', 'nama','nama_pt', 'alamat')->get();
 
         return view('supplier.create_ajax')->with('supplier', $supplier);
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
 
             $validator = Validator::make($request->all());
 
             if ($validator->fails()) {
                 return response()->json(['errors' => $validator->errors()]);
             }
 
             $supplier = SupplierModel::create([
                 'supplier_kode' => $request->supplier_kode,
                 'nama' => $request->nama,
                 'nama_pt' => $request->nama_pt,
                 'alamat' => $request->alamat,
             ]);
 
             return response()->json(['success' => 'Data supplier berhasil disimpan']);
         }
 
         return redirect('/supplier');
     }

     public function list(Request $request)
     {
         $suppliers = SupplierModel::select('supplier_id', 'supplier_kode', 'nama', 'nama_pt', 'alamat');
 
         return DataTables::of($suppliers)
             ->addIndexColumn()
             ->addColumn('aksi', function ($supplier) {
                //  return '<a href="' . url('/supplier/' . $supplier->supplier_id) . '" class="btn btn-info btn-sm">Detail</a> '
                //      . '<a href="' . url('/supplier/' . $supplier->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> '
                //      . '<form class="d-inline-block" method="POST" action="' . url('/supplier/' . $supplier->supplier_id) . '">'
                //      . csrf_field() . method_field('DELETE')
                //      . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                $btn  = '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                 $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> '; 
                 $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> '; 
            
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

            $validator = Validator::make($request->all());

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }
            $check = SupplierModel::find($id);
            if ($check) {
            if(!$request->filled('password')) {
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
         $supplier = SupplierModel::findOrFail($id);
    
 
         return view('supplier.edit_ajax')->with('supplier', $supplier);
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
