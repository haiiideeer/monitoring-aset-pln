@if($condition == 'baik')
    <span class="badge bg-success">
        <i class="fas fa-check-circle me-1" aria-hidden="true"></i>Baik
    </span>
@elseif($condition == 'rusak_ringan')
    <span class="badge bg-warning">
        <i class="fas fa-exclamation-triangle me-1" aria-hidden="true"></i>Rusak Ringan
    </span>
@elseif($condition == 'rusak_berat')
    <span class="badge bg-danger">
        <i class="fas fa-times-circle me-1" aria-hidden="true"></i>Rusak Berat
    </span>
@else
    <span class="badge bg-secondary">
        <i class="fas fa-question-circle me-1" aria-hidden="true"></i>Tidak Diketahui
    </span>
@endif