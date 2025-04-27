<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;

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

    //show ajax
    // public function show_(string $id)
    // {
    //     $level = LevelModel::find($id);

    //     $breadcrumb = (object) [
    //         'title' => 'Detail Level',
    //         'list' => ['Home', 'Level', 'Detail']
    //     ];

    //     $page = (object) [
    //         'title' => 'Detail Level'
    //     ];

    //     $activeMenu = 'level';

    //     return view('level.show', [
    //         'breadcrumb' => $breadcrumb,
    //         'page' => $page,
    //         'level' => $level,
    //         'activeMenu' => $activeMenu
    //     ]);
    // }
    
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

    public function import() 
    { 
        return view('level.import'); 
    }

    //import ajax
    public function import_ajax(Request $request) 
   { 
       if($request->ajax() || $request->wantsJson()){ 
           $rules = [ 
               // validasi file harus xls atau xlsx, max 1MB 
               'file_level' => ['required', 'mimes:xlsx', 'max:1024'] 
           ]; 

           $validator = Validator::make($request->all(), $rules); 
           if($validator->fails()){ 
               return response()->json([ 
                   'status' => false, 
                   'message' => 'Validasi Gagal', 
                   'msgField' => $validator->errors() 
               ]); 
           } 
           
           $file = $request->file('file_level');  // ambil file dari request 

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
                           'level_kode' => $value['A'], 
                           'level_nama' => $value['B'], 
                           'created_at' => now(),
                           'updated_at' => now() 
                       ]; 
                   } 
               } 

               if(count($insert) > 0){ 
                   // insert data ke database, jika data sudah ada, maka diabaikan 
                   levelModel::insertOrIgnore($insert);    
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
       return redirect('/level'); 
   } 

   public function export_excel()
   {
       $level= levelModel::select('level_kode','level_nama')
                   ->orderBy('id_level')
                   ->get();

       //load library excel
       $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
       $sheet = $spreadsheet->getActiveSheet();

       $sheet->setCellValue('A1', 'No');
       $sheet->setCellValue('B1', 'Kode level');
       $sheet->setCellValue('C1', 'Nama level');

       $sheet->getStyle('A1:C1')->getFont()->setBold(true);
       
       $no =1;
       $baris = 2;
       foreach ($level as $value) {
           $sheet->setCellValue('A'.$baris, $no++);
           $sheet->setCellValue('B'.$baris, $value->level_kode);
           $sheet->setCellValue('C'.$baris, $value->level_nama);
           $baris++;
           $no++;
       }
       foreach (range('A', 'C') as $columnID) {
           $sheet->getColumnDimension($columnID)->setAutoSize(true);
       }

       $sheet->setTitle('Data level');
       $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
       $filename = 'Data level '. date('Y-m-d H:i:s') .'.xlsx';

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
       
       $level = LevelModel::select('level_kode','level_nama')
                   ->orderBy('id_level')
                   ->get();
       $pdf = PDF::loadView('level.export_pdf', ['level' => $level]);
       $pdf->setPaper('A4', 'potrait');
       $pdf->setOption("isRemoteEnabled", true);
       $pdf->render();

       return $pdf->stream('Data level '.date('Y-m-d H:i:s').'.pdf');
   }

   public function show_ajax(string $id)
   {
       $level = LevelModel::find($id);

       return view('level.show_ajax', [
           'level' => $level
       ]);
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

     public function show(string $id)
{
    $level = LevelModel::find($id);

    $breadcrumb = (object) [
        'title' => 'Detail Level',
        'list' => ['Home', 'Level', 'Detail']
    ];

    $page = (object) [
        'title' => 'Detail Level'
    ];

    $activeMenu = 'level';

    return view('level.show', [
        'breadcrumb' => $breadcrumb,
        'page' => $page,
        'level' => $level,
        'activeMenu' => $activeMenu
    ]);
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
