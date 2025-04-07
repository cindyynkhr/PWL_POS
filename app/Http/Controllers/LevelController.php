<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar Level',
            'list' => ['Home', 'Level']
        ];

        $page = (object) [
            'title' => 'Daftar Level User'
        ];

        $activeMenu = 'level';
        return view('level.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }


    //fungsi create
    public function create_ajax(){
        return view('level.create_ajax');

        // $level = LevelModel::select('id_level', 'level_kode', 'level_nama')->get();
        // return view('level.create_ajax')->with('level', $level);
    }

    public function list(Request $request)
    {
        $levels = LevelModel::select('id_level', 'level_kode', 'level_nama');

        if ($request->id_level) {
            $levels->where('id_level', $request->id_level);
        }

        return DataTables::of($levels)
            ->addIndexColumn()
            ->addColumn('aksi', function ($level) {
                $btn  = '<button onclick="modalAction(\''.url('/level/' . $level->id_level . '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->id_level . '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> '; 
                $btn .= '<button onclick="modalAction(\''.url('/level/' . $level->id_level . '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> '; 

                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    //menampilkan halaman form edit user ajax
    public function edit_ajax(string $id){
        $level = LevelModel::find($id);
        
        return view('level.edit_ajax', ['level' => $level]);
    }

    //request_ajax
    public function store_ajax(Request $request){
        //cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|string|max:20|unique:m_level,level_kode',
                'level_nama' => 'required|string|max:100'
            ];

            // validasi
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal',
                    'msgField' => $validator->errors(), // menunjukkan field mana yang error
                ]);
            }
            LevelModel::create([
                'level_kode' => $request->level_kode,
                'level_nama' => $request->level_nama
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil disimpan'
            ]);
        }
        return redirect('/level');
    }

    //request ajax
    public function update_ajax(Request $request, $id){
        //cek apakah request berupa ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'level_kode' => 'required|max:20|unique:m_level,level_kode,'.$id.',id_level',
                'level_nama' => 'required|max:100',
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
            $check = LevelModel::find($id);
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
        return redirect('/level');
    }

    //confirm ajax
    public function confirm_ajax(Request $request, $id){
        $level = LevelModel::find($id);

        return view('level.confirm_ajax', ['level' => $level]);
    }

    //delete ajax
    public function delete_ajax(Request $request, $id){
        //cek apakah request dari ajax?
        if ($request->ajax() || $request->wantsJson()) {
            $level = LevelModel::find($id);
            if ($level) {
                try {
                    $level->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data level berhasil dihapus'
                    ]);
                }catch(\Illuminate\Database\QueryException $e) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data level gagal dihapus karena masih terkait dengan data lain'
                    ]);
                }
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/level');
    }

    public function create()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Level',
            'list' => ['Home', 'Level', 'Tambah']
        ];

        $page = (object) [
            'title' => 'Tambah Data Level Level'
        ];

        $activeMenu = 'level';
        return view('level.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'level_kode' => 'required|max:10|unique:m_level,level_kode',
            'level_nama' => 'required|max:100',
        ]);

        LevelModel::create([
            'level_kode' => $request->level_kode,
            'level_nama' => $request->level_nama,
        ]);

        return redirect('/level')->with('success', 'Data level berhasil disimpan');
    }

    public function show($id)
    {
        $breadcrumb = (object) [
            'title' => 'Detail Level',
            'list' => ['Home', 'Level', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail Data Level Level'
        ];

        $level = LevelModel::find($id);
        $activeMenu = 'level';
        return view('level.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function edit($id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Level',
            'list' => ['Home', 'Level', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit Data Level Level'
        ];

        $level = LevelModel::find($id);
        $activeMenu = 'level';

        return view('level.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'level' => $level, 'activeMenu' => $activeMenu]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'level_kode' => 'required|max:10|unique:m_level,level_kode,'.$id.',id_level',
            'level_nama' => 'required|max:100',
        ]);

        $level = LevelModel::find($id);
        $level->level_kode = $request->level_kode;
        $level->level_nama = $request->level_nama;
        $level->save();

        return redirect('/level')->with('success', 'Data level berhasil diubah');
    }

    public function destroy($id)
    {
        $level = LevelModel::find($id);
        $level->delete();

        return redirect('/level')->with('success', 'Data level berhasil dihapus');
    }
    
}


// public function index(){
    
//     $data = DB::select('select * from m_level');
//     return view('level',['data' => $data]);
// }
// DB::insert('insert into m_level(id_level,level_kode,level_nama,created_at) values(?,?,?,?)', [4,'CUS', 'Pelanggan', now()]);

// return 'insert data baru berhasil';

// $row = DB::update('update m_level set level_nama = ? where level_kode = ?',['Customer', 'CUS']);
// return 'Update data berhasil. Jumlah data yang diupdate : '.$row.' baris';

// $row = DB::delete('delete from m_level where level_kode = ?',['CUS']);
// return 'Delete data berhasil. Jumlah data yang dihapus : '.$row.' baris';
