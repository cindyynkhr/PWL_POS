<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        // Set validation
        $validator = Validator::make($request->all(), [
            'user_kode' => 'required',
            'nama' => 'required',
            'password' => 'required|min:5|confirmed',
            'id_level' => 'required'
        ]);

        //if validation fails
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        //create user
        $user = UserModel::create([
            'user_kode' => $request->user_kode,
            'nama' => $request->nama,
            'password' => bcrypt($request->password),
            'id_level' => $request->id_level
        ]);

        //return response JSON user is create
        if($user){
            return response()->json([
                'success' => true,
                'data' => $user
            ], 201);
        }

        //return JSON process insert failed
        return response()->json([
            'success' => false
        ], 409);
    }
}
