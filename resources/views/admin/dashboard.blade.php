@extends('admin.layouts.app', ['pageSlug' => 'dashboard'])

@section('content')
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card card-chart">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-6 text-left">
                            <h5 class="card-category">Grafik Jumlah Temuan per Patrol</h5>
                            <h2 class="card-title">Temuan Berdasarkan Tahun</h2>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart">
                        {{-- Tempat untuk menampilkan grafik --}}
                        {!! $chart->container() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{{ $chart->cdn() }}"></script>
    {{ $chart->script() }}
@endpush
