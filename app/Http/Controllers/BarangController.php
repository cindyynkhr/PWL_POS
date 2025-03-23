<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BarangModel;
 use App\Models\KategoriModel;
 use Yajra\DataTables\Facades\DataTables;
 use Illuminate\Support\Facades\Validator;
 

class BarangController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];

        $page = (object) [
            'title' => 'Data Barang'
        ];

        $kategori = KategoriModel::all();

        $activeMenu = 'barang';

        return view('barang.index', compact('breadcrumb', 'page', 'kategori', 'activeMenu'));
    }

    //create_ajax
    public function create_ajax(Request $request){
        $kategori = KategoriModel::all();

        return view('barang.create_ajax')->with('kategori', $kategori);
    }

    //store_ajax
    public function store_ajax(Request $request){
        if ($request->ajax() || $request->wantsJson()) {
            $barang = BarangModel::create([
                'barang_kode' => $request->barang_kode,
                'barang_nama' => $request->barang_nama,
                'id_kategori' => $request->id_kategori,
                'harga_beli' => $request->harga_beli,
                'harga_jual' => $request->harga_jual,
            ]);

            $validator = Validator::make($request->all());

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
        $barang = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'id_kategori', 'harga_beli', 'harga_jual')
            ->with('kategori');

        if ($request->id_kategori) {
            $barang->where('id_kategori', $request->id_kategori);
        }

        return DataTables::of($barang)
            ->addIndexColumn()
            ->addColumn('aksi', function ($barang) {
                // $btn = '<a href="'.url('/barang/' . $barang->barang_id).'" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="'.url('/barang/' . $barang->barang_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="'. url('/barang/'.$barang->barang_id).'">'
                //     . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
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
                'barang_kode' => 'required|max:10|unique:m_barang,barang_kode,'.$id.',barang_id',
                'barang_nama' => 'required|max:100',
                'id_kategori' => 'required',
                'harga_beli' => 'required|numeric',
                'harga_jual' => 'required|numeric',
            ];

            $validator = Validator::make($request->all());

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }
            $check = BarangModel::find($id);
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
        $barang = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'id_kategori', 'harga_beli', 'harga_jual')->get();

        return view('barang.edit_ajax', ['barang' => $barang]);
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
            'kategori_id' => 'required',
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
            'kategori_id' => 'required',
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
