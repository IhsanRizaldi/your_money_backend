<?php
namespace App\Http\Controllers;

use App\Models\Pengeluaran;
use App\Models\History;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PengeluaranController extends Controller
{
    public function index()
    {
        // Mengambil semua pengeluaran yang terkait dengan user yang sedang login
        $pengeluaran = Pengeluaran::where('user_id', Auth::id())->get();
        return response()->json($pengeluaran);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nominal' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        Pengeluaran::create([
            'user_id' => Auth::user()->id,
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan,
        ]);

        History::create([
            'user_id' => Auth::user()->id,
            'kategori' => 'pengeluaran',
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        return response([
            'message' => 'Pengeluaran Has Been Created'
        ], 200);
    }

    public function update(Request $request, Pengeluaran $pengeluaran)
    {
        $validator = Validator::make($request->all(), [
            'nominal' => 'required',
            'tanggal' => 'required|date',
            'keterangan' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }


        $pengeluaran->update($request->all());

        // Ubah histori
        $history = History::where('user_id', $pengeluaran->user_id)
            ->where('kategori', 'Pengeluaran')
            ->first();
        $history->update([
            'nominal' => $request->nominal,
            'tanggal' => $request->tanggal,
            'keterangan' => $request->keterangan
        ]);

        return response([
            'message' => 'Pengeluaran Has Been Updated'
        ], 200);
    }

    public function destroy(Pengeluaran $pengeluaran)
    {
        // Hapus pengeluaran
        $pengeluaran->delete();

        // Hapus histori yang terkait
        $history = History::where('user_id', $pengeluaran->user_id)
            ->where('kategori', 'Pengeluaran')
            ->delete();

        return response([
            'message' => 'Pengeluaran Has Been Deleted'
        ], 200);
    }
}
