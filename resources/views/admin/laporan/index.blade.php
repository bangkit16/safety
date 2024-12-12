@extends('admin.layouts.app', ['page' => 'Laporan Patroli', 'pageSlug' => 'laporan'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Laporan Patroli Keselamatan</h4>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.alerts.success')
                    @include('admin.alerts.alert')
                    <div class="container-fluid">
                        <form method="GET" action="{{ route('perbaikan.index') }}" class="d-flex w-100">
                            <div class="form-group flex-grow-1 me-2">
                                <input type="text" name="search" class="form-control form-control-sm mt-1"
                                    placeholder="Search by tanggal target, temuan, perbaikan, divisi, user, or status"
                                    value="{{ request()->get('search') }}">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary mt-1"><i
                                        class="tim-icons icon-zoom-split"></i></button>
                            </div>
                        </form>
                    </div>
                    <div class="">
                        <table class="table table-responsive-xl" style="width: 100%" id="">
                            <thead class="text-primary ">
                                <tr>
                                    <th scope="col">
                                        <span style="cursor: pointer;"
                                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort_by' => 'divisi_id', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}'">
                                            Nama Divisi
                                            @if ($sortBy === 'divisi_id')
                                                {{ $order === 'asc' ? '🔼' : '🔽' }}
                                            @endif
                                        </span>
                                    </th>
                                    <th scope="col">
                                        <span style="cursor: pointer;"
                                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort_by' => 'user_id', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}'">
                                            Nama Inspektor
                                            @if ($sortBy === 'user_id')
                                                {{ $order === 'asc' ? '🔼' : '🔽' }}
                                            @endif
                                        </span>
                                    </th>
                                    <th scope="col">
                                        <span style="cursor: pointer;"
                                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort_by' => 'target', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}'">
                                            Tanggal Inspeksi
                                            @if ($sortBy === 'target')
                                                {{ $order === 'asc' ? '🔼' : '🔽' }}
                                            @endif
                                        </span>
                                    </th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $d)
                                    <tr>
                                        <td>
                                            @foreach ($divisi as $s)
                                                @if ($s->divisi_id == $d->divisi_id)
                                                    {{ $s->nama }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($user as $s)
                                                @if ($s->user_id == $d->user_id)
                                                    {{ $s->name }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            {{ $d->tanggal }}
                                        </td>
                                        <td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item"
                                                        href="{{ url('download-pdf/' . $d->patrol_id) }}">Download
                                                        PDF</a>
                                                    <a class="dropdown-item"
                                                        href="{{ url('download-excel/' . $d->patrol_id) }}">Download
                                                        Excel</a>
                                                    <a class="dropdown-item"
                                                        href="{{ url('print/' . $d->patrol_id) }}">Print</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">Tidak ada data yang ditemukan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer ">
                        <nav class="d-flex justify-content-between align-items-center" aria-label="...">
                            <div class="form-group">
                                <select id="paginationLimit" class="form-control" onchange="updatePaginationLimit(this.value)"
                                    style="font-size: 12px">
                                    <option value="10" {{ request('limit') == 10 ? 'selected' : '' }}>10</option>
                                    <option value="25" {{ request('limit') == 25 ? 'selected' : '' }}>25</option>
                                    <option value="50" {{ request('limit') == 50 ? 'selected' : '' }}>50</option>
                                    <option value="all" {{ request('limit') == 'all' ? 'selected' : '' }}>All</option>
                                </select>
                            </div>

                            {{-- Tampilkan pagination hanya jika tidak memilih 'all' --}}
                            @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
                                {{ $data->links('vendor.pagination.bootstrap-5') }}
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    @endsection

    @stack('js')
    <script>
        function updatePaginationLimit(limit) {
            const url = new URL(window.location.href);
            url.searchParams.set('limit', limit); // Tambahkan atau update parameter 'limit'
            window.location.href = url.toString(); // Redirect ke URL baru
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Check and show the editlayanan modal if there are errors for edit layanan
            if (
                {{ $errors->has('patrol_id') || $errors->has('dokumentasi') || $errors->has('divisi_id') || $errors->has('user_id') || $errors->has('dokumentasi') || $errors->has('perbaikan') || $errors->has('target') ? 'true' : 'false' }}
            ) {
                var addperbaikanModal = new bootstrap.Modal(document.getElementById('addperbaikan'));
                addperbaikanModal.show();

                console.log(@json($errors->all()));
            }

            if (
                {{ $errors->has('edit_patrol_id') || $errors->has('edit_dokumentasi') || $errors->has('edit_divisi_id') || $errors->has('edit_user_id') || $errors->has('edit_dokumentasi') || $errors->has('edit_perbaikan') || $errors->has('edit_target') ? 'true' : 'false' }}
            ) {
                var editperbaikanModal = new bootstrap.Modal(document.getElementById('editperbaikan'));
                var url = localStorage.getItem('Url');
                editperbaikanModal.show();
                $('#editperbaikanForm').attr('action', url);

                console.log(@json($errors->all()));
            }

            if (
                {{ $errors->has('dokum_dokumentasi') ? 'true' : 'false' }}
            ) {
                var editdokumModal = new bootstrap.Modal(document.getElementById('dokumModal'));
                var url = localStorage.getItem('Url');
                editdokumModal.show();
                $('#dokumModalForm').attr('action', url);

                console.log(@json($errors->all()));
            }
        });


        document.addEventListener('DOMContentLoaded', function() {
            var editButtons = document.querySelectorAll('.edit-button');

            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var patrolId = this.getAttribute('data-id');
                    var patrolPerbaikan = this.getAttribute('data-perbaikan');
                    var patrolDokum = this.getAttribute('data-dokumentasi');
                    var patrolPatrol = this.getAttribute('data-patrol-id');
                    var patrolDivisi = this.getAttribute('data-divisi-id');
                    var patrolUser = this.getAttribute('data-user-id');
                    var patrolTarget = this.getAttribute('data-target');
                    var actionUrl = this.getAttribute('data-url');
                    localStorage.setItem('Url', actionUrl);
                    localStorage.setItem('Image', patrolDokum);

                    console.log(patrolId, patrolDivisi, patrolDokum, patrolPerbaikan, patrolTarget,
                        patrolUser, patrolPatrol, actionUrl);

                    // $('#edit-patrol-id').val(patrolId);
                    $('#edit-perbaikan').val(patrolPerbaikan);
                    $('#edit-divisi-id').val(patrolDivisi);
                    $('#edit-patrol-id').val(patrolPatrol);
                    $('#edit-user-id').val(patrolUser);
                    $('#edit-target').val(patrolTarget);

                    // Update gambar ikon jika ada
                    var iconImage = $('#current-icon');
                    if (patrolDokum) {
                        iconImage.attr('src', patrolDokum); // Menggabungkan string dengan benar
                        iconImage.show(); // Menampilkan gambar jika ada
                    } else {
                        iconImage.hide(); // Menyembunyikan gambar jika tidak ada
                    }

                    // Atur action form untuk update
                    $('#editperbaikanForm').attr('action', actionUrl);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var editButtons = document.querySelectorAll('.dokum-button');

            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var patrolId = this.getAttribute('data-id');
                    var patrolPerbaikan = this.getAttribute('data-perbaikan');
                    var patrolPatrol = this.getAttribute('data-temuan');
                    var actionUrl = this.getAttribute('data-url');
                    localStorage.setItem('Url', actionUrl);

                    console.log(patrolId, patrolPerbaikan, patrolPatrol, actionUrl);

                    // $('#edit-patrol-id').val(patrolId);
                    $('#dokum-perbaikan').val(patrolPerbaikan);
                    $('#dokum-patrol-id').val(patrolPatrol);

                    // Atur action form untuk update
                    $('#dokumModalForm').attr('action', actionUrl);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.show-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    // Ambil data dari tombol
                    const user = this.getAttribute('data-user');
                    const divisi = this.getAttribute('data-divisi');
                    const temuan = this.getAttribute('data-temuan');
                    const dokumentasi = this.getAttribute('data-dokumentasi');
                    const perbaikan = this.getAttribute('data-perbaikan');
                    const target = this.getAttribute('data-target');

                    // Isi data ke modal
                    document.getElementById('showUser').innerText = user;
                    document.getElementById('showDivisi').innerText = divisi;
                    document.getElementById('showTemuan').innerText = temuan;
                    document.getElementById('showPerbaikan').innerText = perbaikan;
                    document.getElementById('showTarget').innerText = target;

                    const dokumentasiImg = document.getElementById('showDokumentasi');
                    if (dokumentasi) {
                        dokumentasiImg.src = dokumentasi;
                        dokumentasiImg.style.display = "block";
                    } else {
                        dokumentasiImg.style.display = "none";
                    }
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Ketika tombol delete diklik
            document.querySelectorAll('.delete-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    // Ambil data dari atribut data-*
                    var userId = this.getAttribute('data-id');
                    var userDeleteUrl = this.getAttribute('data-url');

                    // Atur action form untuk delete
                    document.getElementById('deleteperbaikanForm').setAttribute('action',
                        userDeleteUrl);
                });
            });
        });
    </script>
