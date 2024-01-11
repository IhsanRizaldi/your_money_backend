<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PemasukanController extends Controller
{
    public function index()
    {
        $pemasukan = Pemasukan::where('user_id',Auth::user()->id)->get();
        return response()->json($pemasukan);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nominal' => 'required|numeric',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        Pemasukan::create([
            'user_id' => Auth::user()->id,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        History::create([
            'user_id' => Auth::user()->id,
            'kategori' => 'pemasukan',
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        return response([
            'message' => 'Pemasukan Has Been Created'
        ], 200);
    }

    public function update(Request $request, Pemasukan $pemasukan)
    {
        $validator = Validator::make($request->all(), [
            'nominal' => 'required|numeric',
            'tanggal' => 'required|date',
            'keterangan' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $pemasukan->update([
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        $history = History::where('user_id', Auth::user()->id)
            ->where('kategori', 'pemasukan')
            ->first();
        $history->update([
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        return response([
            'message' => 'Pemasukan Has Been Updated'
        ], 200);
    }

    public function destroy(Pemasukan $pemasukan)
    {
        $pemasukan->delete();

        History::where('user_id', $pemasukan->user_id)
            ->where('kategori', 'pemasukan')
            ->delete();

        return response([
            'message' => 'Pemasukan Has Been Deleted'
        ], 200);
    }
}
