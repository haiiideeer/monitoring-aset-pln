@extends('layouts.app')

@section('content')
    <div class="container-fluid">
       <h1 class="dashboard-title mb-4">Dashboard Admin</h1>

<style>
.dashboard-title {
  font-size: 1rem; /* besar dan tegas */
  font-weight: 500;
  background: linear-gradient(90deg, #007bff, #00d4ff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  text-transform: uppercase;
  letter-spacing: 2px;
  text-shadow: 2px 2px 8px rgba(0, 123, 255, 0.4);
  animation: fadeIn 1s ease-in-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(-20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>

        <!-- Komponen Livewire -->
        <livewire:dashboard-stats />
    </div>
@endsection
