<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Asset;
use App\Models\User;
use App\Models\Bidang;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class DashboardStats extends Component
{
    public $totalAssets;
    public $totalUsers;
    public $totalBidang;
    public $assetsByField;
    public $assetsByKondisi;
    public $chartData;
    public $kondisiData;
    public $recentAssets;

    // Listener diubah agar sesuai dengan method
    protected $listeners = ['refreshStats'];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->totalAssets = Cache::remember('dashboard.total_assets', 300, fn() => Asset::count());
        $this->totalUsers = Cache::remember('dashboard.total_users', 300, fn() => User::count());
        $this->totalBidang = Cache::remember('dashboard.total_bidang', 300, fn() => Bidang::count());

        $this->assetsByField = Cache::remember('dashboard.assets_by_field', 300, function () {
            return Asset::join('bidangs', 'assets.bidang_id', '=', 'bidangs.id')
                ->select(
                    'bidangs.nama_bidang',
                    'bidangs.kode_bidang',
                    DB::raw('count(assets.id) as total'),
                    DB::raw('sum(assets.jumlah_aset) as total_jumlah'),
                    DB::raw('sum(assets.harga) as total_harga'),
                    DB::raw('CASE 
                        WHEN sum(assets.jumlah_aset) > 0 
                        THEN sum(assets.harga) / sum(assets.jumlah_aset) 
                        ELSE 0 
                    END as harga_per_unit')
                )
                ->groupBy('bidangs.id', 'bidangs.nama_bidang', 'bidangs.kode_bidang')
                ->orderBy('total', 'desc')
                ->get();
        });

        $this->assetsByKondisi = Cache::remember('dashboard.assets_by_kondisi', 300, function () {
            return Asset::select('kondisi', DB::raw('count(*) as total'))
                ->groupBy('kondisi')
                ->get();
        });

        $this->recentAssets = Asset::with('bidang')
            ->latest('created_at')
            ->take(5)
            ->get();

        $this->chartData = [
            'labels' => $this->assetsByField->pluck('nama_bidang')->toArray(),
            'data' => $this->assetsByField->pluck('total')->toArray(),
            'harga_per_unit' => $this->assetsByField->pluck('harga_per_unit')->map(fn($v) => round($v, 0))->toArray(),
            'colors' => $this->generateColors(count($this->assetsByField)),
        ];

        $this->kondisiData = [
            'labels' => $this->assetsByKondisi->pluck('kondisi')->toArray(),
            'data' => $this->assetsByKondisi->pluck('total')->toArray(),
            'colors' => $this->generateKondisiColors(count($this->assetsByKondisi)),
        ];
    }

    private function generateColors($count)
    {
        $colors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0',
            '#9966FF', '#FF9F40', '#FF6384', '#C9CBCF',
            '#4BC0C0', '#FF6384', '#36A2EB', '#FFCE56',
        ];
        return array_slice($colors, 0, $count);
    }

    private function generateKondisiColors($count)
    {
        $kondisiColors = [
            '#28a745', // Baik
            '#ffc107', // Rusak Ringan
            '#fd7e14', // Rusak Sedang
            '#dc3545', // Rusak Berat
            '#6c757d', // Tidak Diketahui
        ];
        return array_slice($kondisiColors, 0, $count);
    }

    public function refreshStats()
    {
        Cache::forget('dashboard.total_assets');
        Cache::forget('dashboard.total_users');
        Cache::forget('dashboard.total_bidang');
        Cache::forget('dashboard.assets_by_field');
        Cache::forget('dashboard.assets_by_kondisi');

        $this->loadStats();

        $this->emit('chartUpdated', [
            'bidang' => $this->chartData,
            'kondisi' => $this->kondisiData,
        ]);

        session()->flash('message', 'Data berhasil di-refresh!');
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}
