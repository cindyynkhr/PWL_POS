<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LevelModel;

class LevelController extends Controller
{
    public function index(Request $request)
    {
        $query = LevelModel::query();

        if ($request->has('ADM')) {
            $query->where('ADM', $request->ADM);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $level = LevelModel::create($request->all());
        return response()->json($level, 201);
    }

    public function show(LevelModel $level)
    {
        return response()->json($level);
    }

    public function update(Request $request, LevelModel $level)
    {
        $level->update($request->all());
        return response()->json($level);
    }

    public function destroy(LevelModel $level)
    {
        $level->delete();
        return response()->json([
            'success' => true,
            'message' => 'Data terhapus',
        ]);
    }
}
