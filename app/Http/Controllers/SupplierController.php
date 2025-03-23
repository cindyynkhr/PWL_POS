<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Models\SupplierModel;

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
 
     public function list(Request $request)
     {
         $suppliers = SupplierModel::select('supplier_id', 'supplier_kode', 'nama', 'nama_pt', 'alamat');
 
         return DataTables::of($suppliers)
             ->addIndexColumn()
             ->addColumn('aksi', function ($supplier) {
                 return '<a href="' . url('/supplier/' . $supplier->supplier_id) . '" class="btn btn-info btn-sm">Detail</a> '
                     . '<a href="' . url('/supplier/' . $supplier->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> '
                     . '<form class="d-inline-block" method="POST" action="' . url('/supplier/' . $supplier->supplier_id) . '">'
                     . csrf_field() . method_field('DELETE')
                     . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
             })
             ->rawColumns(['aksi'])
             ->make(true);
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
