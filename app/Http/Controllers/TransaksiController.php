<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    // Helper untuk mengambil daftar item
    private function getItems()
    {
        return [
            'MLBB' => [
                ['label' => '5 Diamonds', 'price' => 2000],
                ['label' => '19 Diamonds', 'price' => 6000],
                ['label' => '28 Diamonds', 'price' => 8000],
                ['label' => '44 Diamonds', 'price' => 13000],
                ['label' => '56 Diamonds', 'price' => 15000],
                ['label' => '74 Diamonds', 'price' => 20000],
                ['label' => '85 Diamonds', 'price' => 23000],
                ['label' => '1 x Weekly Diamond', 'price' => 30000],
                ['label' => '112 Diamonds', 'price' => 30000],
                ['label' => '185 Diamonds', 'price' => 47000],
                ['label' => '240 Diamonds', 'price' => 63000],
                ['label' => '300 Diamonds', 'price' => 80000],
                ['label' => 'Starlight Membership', 'price' => 82000],
                ['label' => '374 Diamonds', 'price' => 100000],
                ['label' => '429 Diamonds', 'price' => 110000],
                ['label' => '500 Diamonds', 'price' => 140000],
                ['label' => '568 Diamonds', 'price' => 145000],
                ['label' => '642 Diamonds', 'price' => 175000],
                ['label' => '717 Diamonds', 'price' => 185000],
                ['label' => '977 Diamonds', 'price' => 245000],
                ['label' => '1136 Diamonds', 'price' => 290000],
                ['label' => '1230 Diamonds', 'price' => 315000],
                ['label' => '1368 Diamonds', 'price' => 350000],
                ['label' => '1443 Diamonds', 'price' => 365000],
                ['label' => '1673 Diamonds', 'price' => 425000],
                ['label' => '2010 Diamonds', 'price' => 500000],
                ['label' => '2180 Diamonds', 'price' => 525000],
                ['label' => '2382 Diamonds', 'price' => 575000],
                ['label' => '2885 Diamonds', 'price' => 670000],
                ['label' => '3693 Diamonds', 'price' => 904000],
                ['label' => '4020 Diamonds', 'price' => 957000],
                ['label' => '4678 Diamonds', 'price' => 1123000],
                ['label' => '4830 Diamonds', 'price' => 1147000],
                ['label' => '5398 Diamonds', 'price' => 1292000],
                ['label' => '5940 Diamonds', 'price' => 1437000],
                ['label' => '6840 Diamonds', 'price' => 1625000],
                ['label' => '7723 Diamonds', 'price' => 1847000],
                ['label' => '8040 Diamonds', 'price' => 1907000],
                ['label' => '8303 Diamonds', 'price' => 2000000],
            ],
            'FF' => [
                ['label' => '5 Diamonds', 'price' => 1000],
                ['label' => '10 Diamonds', 'price' => 2000],
                ['label' => '15 Diamonds', 'price' => 3000],
                ['label' => '25 Diamonds', 'price' => 5000],
                ['label' => '55 Diamonds', 'price' => 7500],
                ['label' => '70 Diamonds', 'price' => 10000],
                ['label' => '80 Diamonds', 'price' => 11000],
                ['label' => '100 Diamonds', 'price' => 13000],
                ['label' => '130 Diamonds', 'price' => 16000],
                ['label' => '140 Diamonds', 'price' => 17000],
                ['label' => '145 Diamonds', 'price' => 20000],
                ['label' => '210 Diamonds', 'price' => 27000],
                ['label' => '260 Diamonds', 'price' => 33000],
                ['label' => '300 Diamonds', 'price' => 38000],
                ['label' => '405 Diamonds', 'price' => 50000],
                ['label' => '565 Diamonds', 'price' => 70000],
                ['label' => '720 Diamonds', 'price' => 85000],
                ['label' => '860 Diamonds', 'price' => 105000],
                ['label' => '1000 Diamonds', 'price' => 120000],
                ['label' => '2000 Diamonds', 'price' => 237000],
                ['label' => '3640 Diamonds', 'price' => 430000],
                ['label' => '7290 Diamonds', 'price' => 856000],
            ]
        ];
    }

    public function index()
    {
        $items = $this->getItems();
        $games = [
            'MLBB' => ['logo' => 'logo.ml.jpg', 'items' => $items['MLBB']],
            'FF'   => ['logo' => 'LOGO .FF.jpg', 'items' => $items['FF']],
        ];
        return view('topup', compact('games'));
    }

    // Method baru untuk menghandle halaman detail game
    public function showGame($gameName)
    {
        $items = $this->getItems();
        $games = [
            'MLBB' => ['logo' => 'logo.ml.jpg', 'items' => $items['MLBB']],
            'FF'   => ['logo' => 'LOGO .FF.jpg', 'items' => $items['FF']],
        ];

        // Validasi apakah game ada di daftar
        if (!array_key_exists($gameName, $games)) {
            abort(404);
        }

        return view('detail-game', [
            'gameName' => $gameName,
            'gameData' => $games[$gameName]
        ]);
    }

    public function pilihPembayaran(Request $request)
    {
        $request->validate(['game_name' => 'required', 'user_id' => 'required', 'nominal' => 'required']);
        
        $items = $this->getItems();
        $gameItems = $items[$request->game_name] ?? [];
        $selected_item = collect($gameItems)->firstWhere('label', $request->nominal);
        
        $order_data = $request->all();
        $order_data['price'] = $selected_item['price'] ?? 0;
        
        session(['order_data' => $order_data]);
        
        $metode_list = ['BCA' => '000088890 (a/n Admin)', 'GOPAY CUSTOMER' => 'NO WA KU (a/n S****)'];
        
        // TAMBAHKAN $gameName DI SINI AGAR DIKIRIM KE VIEW PEMBAYARAN
        $gameName = $request->game_name; 

        return view('pembayaran', compact('metode_list', 'gameName'));
    }

    public function kirimBukti(Request $request)
    {
        if (!session()->has('order_data')) return response()->json(['success' => false, 'message' => 'Sesi habis.']);
        
        $request->validate(['bukti' => 'required|image|mimes:jpeg,png,jpg|max:2048', 'metode_dipilih' => 'required']);

        $namaFile = time() . '_' . $request->file('bukti')->getClientOriginalName();
        $request->bukti->move(public_path('bukti'), $namaFile);

        $order = session('order_data');
        DB::table('transaksis')->insert([
            'trx_id' => 'TRX-' . time(),
            'game_name' => $order['game_name'],
            'user_id' => $order['user_id'],
            'nominal' => $order['nominal'],
            'price' => $order['price'],
            'metode' => $request->metode_dipilih,
            'bukti_transfer' => $namaFile,
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        session()->forget('order_data');
        
        return response()->json([
            'success' => true,
            'url' => "https://wa.me/6289696300447?text=Halo admin saya udah melakukan konfirmasi pembayaran tolong cek."
        ]);
    }

    public function adminIndex()
    {
        $transaksis = DB::table('transaksis')->orderBy('created_at', 'desc')->get();
        return view('admin', compact('transaksis'));
    }

    public function konfirmasi($id)
    {
        DB::table('transaksis')->where('id', $id)->update(['status' => 'sukses', 'updated_at' => now()]);
        return back()->with('success', 'Transaksi berhasil dikonfirmasi!');
    }

    public function cetakBukti($id)
    {
        $transaksi = DB::table('transaksis')->where('id', $id)->first();
        return view('cetak', compact('transaksi')); 
    }
}