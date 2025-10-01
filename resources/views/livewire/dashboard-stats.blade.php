<div>
    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistik Utama -->
    <div class="row mb-4">
        <!-- Total Aset -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0 rounded-lg h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle p-3 me-3">
                        <i class="fas fa-box fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Total Aset</h5>
                        <h3 class="mb-0 fw-bold">{{ number_format($totalAssets) }}</h3>
                        <small class="text-muted">Item aset</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total User -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0 rounded-lg h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle p-3 me-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Total User</h5>
                        <h3 class="mb-0 fw-bold">{{ number_format($totalUsers) }}</h3>
                        <small class="text-muted">Pengguna aktif</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Bidang -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0 rounded-lg h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info text-white rounded-circle p-3 me-3">
                        <i class="fas fa-building fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Total Bidang</h5>
                        <h3 class="mb-0 fw-bold">{{ number_format($totalBidang) }}</h3>
                        <small class="text-muted">Bidang kerja</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Refresh Button -->
        <div class="col-md-3 mb-4">
            <div class="card shadow-sm border-0 rounded-lg h-100">
                <div class="card-body d-flex align-items-center justify-content-center">
                    <button wire:click="refreshStats" class="btn btn-outline-primary" wire:loading.attr="disabled">
                        <div wire:loading.remove>
                            <i class="fas fa-sync-alt me-2"></i>
                            Refresh Data
                        </div>
                        <div wire:loading>
                            <i class="fas fa-spinner fa-spin me-2"></i>
                            Loading...
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Aset per Bidang -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm border-0 rounded-lg">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-bar text-primary me-2"></i>
                            Aset per Bidang
                        </h5>
                        <span class="badge bg-light text-dark">{{ count($assetsByField) }} Bidang</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($assetsByField as $index => $field)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="d-flex align-items-center p-3 bg-light rounded hover-shadow">
                                    <div class="flex-shrink-0">
                                        <div class="rounded-circle p-2" style="background-color: {{ $chartData['colors'][$index] ?? '#6c757d' }};">
                                            <i class="fas fa-folder text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0">{{ $field->nama_bidang }}</h6>
                                        <small class="text-muted">
                                            <span class="badge bg-secondary me-1">{{ $field->kode_bidang }}</span>
                                            {{ number_format($field->total) }} aset
                                            @if($field->total_jumlah)
                                                ({{ number_format($field->total_jumlah) }} unit)
                                            @endif
                                            <br>
                                            <span class="text-info">Average: Rp {{ number_format($field->harga_per_unit ?? 0) }}/unit</span>
                                        </small>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-primary fs-6">{{ $field->total }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Belum ada data aset</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik Visualisasi -->
    <div class="row mb-4">
        <!-- Distribusi per Bidang (Pie Chart) -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 rounded-lg h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie text-success me-2"></i>
                        Distribusi per Bidang
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="pieChart" width="300" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Nilai Aset per Unit per Bidang (Bar Chart) -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 rounded-lg h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-bar text-info me-2"></i>
                        Nilai Aset per Bidang
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="barChart" width="300" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Kondisi Aset (Doughnut Chart) -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm border-0 rounded-lg h-100">
                <div class="card-header bg-white border-0">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-doughnut text-warning me-2"></i>
                        Kondisi Aset
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="kondisiChart" width="300" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

  <!-- Aset Terbaru -->
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0 rounded-lg">
            <div class="card-header bg-white border-0">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock text-primary me-2"></i>
                    Aset Terbaru
                </h5>
            </div>
            <div class="card-body">
                @if($recentAssets && $recentAssets->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Aset</th>
                                    <th>Lokasi</th> 
                                    <th>Bidang</th>
                                    <th>unit</th>
                                    <th>Nilai</th>
                                    <th>Kondisi</th>
                                    <th>Tanggal Input</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentAssets as $asset)
                                    <tr>
                                        <td>{{ $asset->nama_aset }}</td>
                                        <td>{{ $asset->lokasi }}</td> 
                                        <td>
                                            <span class="badge bg-primary">{{ $asset->bidang->nama_bidang ?? '-' }}</span>
                                        </td>
                                        <td>{{ $asset->unit }}</td> 
                                        <td>Rp {{ number_format($asset->harga) }}</td> 
                                        <td>
                                            @php
                                                $kondisiClass = match($asset->kondisi) {
                                                    'Baik' => 'bg-success',
                                                    'Rusak' => 'bg-warning',
                                                    'Perlu Perbaikan' => 'bg-orange',
                                                    'Hilang' => 'bg-danger',
                                                    default => 'bg-secondary'
                                                };
                                            @endphp
                                            <span class="badge {{ $kondisiClass }}">{{ $asset->kondisi }}</span>
                                        </td>
                                        <td>{{ $asset->created_at->format('d-m-Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Belum ada data aset</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

</div>

@push('styles')
<style>
    .hover-shadow {
        transition: all 0.3s ease;
    }
    .hover-shadow:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .bg-orange {
        background-color: #fd7e14 !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data untuk charts
    const bidangData = @json($chartData);
    const kondisiData = @json($kondisiData);
    
    // Pie Chart - Distribusi per Bidang
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    let pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: bidangData.labels,
            datasets: [{
                data: bidangData.data,
                backgroundColor: bidangData.colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Bar Chart - Nilai Aset per Unit per Bidang
    const barCtx = document.getElementById('barChart').getContext('2d');
    let barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: bidangData.labels,
            datasets: [{
                label: 'Nilai per Unit (Rp)',
                data: bidangData.harga_per_unit,
                backgroundColor: bidangData.colors,
                borderColor: bidangData.colors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 0,
                        font: {
                            size: 10
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });

    // Doughnut Chart - Kondisi Aset
    const kondisiCtx = document.getElementById('kondisiChart').getContext('2d');
    let kondisiChart = new Chart(kondisiCtx, {
        type: 'doughnut',
        data: {
            labels: kondisiData.labels,
            datasets: [{
                data: kondisiData.data,
                backgroundColor: kondisiData.colors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '60%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        font: {
                            size: 11
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                        }
                    }
                }
            }
        }
    });

    // Listen untuk update chart real-time
    Livewire.on('chartUpdated', (data) => {
        // Update Pie Chart
        pieChart.data.labels = data.bidang.labels;
        pieChart.data.datasets[0].data = data.bidang.data;
        pieChart.data.datasets[0].backgroundColor = data.bidang.colors;
        pieChart.update();

        // Update Bar Chart dengan data harga_per_unit
        barChart.data.labels = data.bidang.labels;
        barChart.data.datasets[0].data = data.bidang.harga_per_unit;
        barChart.data.datasets[0].backgroundColor = data.bidang.colors;
        barChart.data.datasets[0].borderColor = data.bidang.colors;
        barChart.update();

        // Update Kondisi Chart
        kondisiChart.data.labels = data.kondisi.labels;
        kondisiChart.data.datasets[0].data = data.kondisi.data;
        kondisiChart.data.datasets[0].backgroundColor = data.kondisi.colors;
        kondisiChart.update();

        // Show success message
        if (typeof toastr !== 'undefined') {
            toastr.success('Dashboard berhasil di-refresh!');
        }
    });

    // Auto-refresh setiap 5 menit 
    setInterval(function() {
        Livewire.emit('refreshDashboard');
    }, 300000); // 5 menit = 300000ms
});
</script>
@endpush