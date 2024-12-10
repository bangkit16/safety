@extends('admin.layouts.app', ['pageSlug' => 'dashboard'])

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header ">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h5 class="card-category">Grafik Jumlah Apar per Bulan</h5>
                            <h2 class="card-title">Apar Aktivitas</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@stack('js')
