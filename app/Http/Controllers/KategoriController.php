<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use Illuminate\Http\Request;
 use Yajra\DataTables\Facades\DataTables;
 use Illuminate\Support\Facades\Validator;

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
        $kategori = KategoriModel::select('id_kategori', 'kategori_kode', 'kategori_nama')->get();

        return view('kategori.create_ajax')->with('kategori', $kategori);
    }

    //store_ajax
    public function store_ajax(Request $request){
      //cek apakah request berupa ajax
      if ($request->ajax() || $request->wantsJson()) {
          $rules = [
              'kategori_kode' => 'required|max:10|unique:m_kategori,kategori_kode',
              'kategori_nama' => 'required|max:100',
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
          $kategori = KategoriModel::create([
              'kategori_kode' => $request->kategori_kode,
              'kategori_nama' => $request->kategori_nama,
          ]);
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
                // $btn = '<a href="'.url('/kategori/' . $kategori->id_kategori).'" class="btn btn-info btn-sm">Detail</a> ';
                // $btn .= '<a href="'.url('/kategori/' . $kategori->id_kategori . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
                // $btn .= '<form class="d-inline-block" method="POST" action="'. url('/kategori/'.$kategori->id_kategori).'">'
                //     . csrf_field() . method_field('DELETE') .
                //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
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
                'kategori_nama' => 'required|max:100',
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
    public function confirm_ajax(Request $request, $id){
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
        $kategori = KategoriModel::select('id_kategori', 'kategori_kode', 'kategori_nama')->get();

        return view('kategori.edit_ajax', ['kategori' => $kategori]);
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
