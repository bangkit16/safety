@extends('admin.layouts.app', ['page' => 'Input Patrol', 'pageSlug' => 'input-patrol'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Patroli Keselamatan</h4>
                        </div>
                        <div class="col-4 text-right">
                            @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                                <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                    data-bs-target="#addpatrol">
                                    Add Patroli
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.alerts.success')
                    @include('admin.alerts.alert')
                    <div class="container-fluid">
                        <form method="GET" action="{{ route('patrol.index') }}" class="d-flex w-100">
                            <div class="form-group flex-grow-1 me-2">
                                <input type="text" name="search" class="form-control form-control-sm mt-1"
                                    placeholder="Search by tanggal, divisi, user, temuan or status"
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
                                            Nama Pembuat
                                            @if ($sortBy === 'user_id')
                                                {{ $order === 'asc' ? '🔼' : '🔽' }}
                                            @endif
                                        </span>
                                    </th>
                                    <th scope="col">
                                        <span style="cursor: pointer;"
                                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort_by' => 'tanggal', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}'">
                                            Tanggal Pembuatan
                                            @if ($sortBy === 'tanggal')
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
                                            @foreach ($divisi as $p)
                                                @if ($p->divisi_id == $d->divisi_id)
                                                    {{ $p->nama }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            @foreach ($user as $p)
                                                @if ($p->user_id == $d->user_id)
                                                    {{ $p->name }}
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>{{ $d->tanggal }}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">

                                                    <a class="dropdown-item edit-button" data-bs-toggle="modal"
                                                        data-bs-target="#editpatrol" data-id="{{ $d->patrol_id }}"
                                                        data-divisi-id="{{ $d->divisi_id }}"
                                                        data-user-id="{{ $d->user_id }}"
                                                        data-url="{{ url('patrol/' . $d->patrol_id) }}">Edit</a>
                                                    @if (auth()->user()->user_id == $d->user_id)
                                                        <a class="dropdown-item temuan-button" data-bs-toggle="modal"
                                                            data-bs-target="#addtemuan" data-id="{{ $d->patrol_id }}"
                                                            data-divisi-id="{{ $d->divisi_id }}"
                                                            data-user-id="{{ $d->user_id }}"
                                                            data-url="{{ route('perbaikan.store') }}">Tambah Temuan</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Tidak ada data yang ditemukan</td>
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

    <!-- Modal Add User -->
    <div class="modal fade" id="addpatrol" tabindex="-1" aria-labelledby="addpatrolTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Patroli</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form role="form" method="POST" action="{{ route('patrol.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Role User -->
                        <div class="form-group">
                            <label for="divisi_id" class="col-form-label">Name Divisi:</label>
                            <select name="divisi_id" id="divisi_id"
                                class="form-control{{ $errors->has('divisi_id') ? ' is-invalid' : '' }}"
                                style="height: 50px; ">
                                <option value="">- Select Divisi -</option>
                                @foreach ($divisi as $r)
                                    <option value="{{ $r->divisi_id }}"
                                        {{ old('divisi_id') == $r->divisi_id ? 'selected' : '' }}>
                                        {{ $r->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('divisi_id'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('divisi_id') }}
                                </span>
                            @endif
                        </div>

                        <!-- Role User -->
                        <div class="form-group">
                            <label for="user_id" class="col-form-label">Name User:</label>
                            <select name="user_id" id="user_id"
                                class="form-control{{ $errors->has('user_id') ? ' is-invalid' : '' }}"
                                style="height: 50px">
                                <option value="">- Select User -</option>
                                @foreach ($user as $r)
                                    <option value="{{ $r->user_id }}"
                                        {{ old('user_id') == $r->user_id ? 'selected' : '' }}>
                                        {{ $r->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('user_id'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('user_id') }}
                                </span>
                            @endif
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Patroli</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div class="modal fade" id="editpatrol" tabindex="-1" aria-labelledby="editpatrolTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editpatrolTitle">Edit Patroli</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form role="form" method="POST" action="" id="editpatrolForm"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Role User -->
                        <div class="form-group{{ $errors->has('edit_divisi_id') ? ' has-danger' : '' }}">
                            <label for="edit-divisi-id" class="col-form-label">Name Divisi: </label>
                            <select name="edit_divisi_id"
                                class="form-control {{ $errors->has('edit_divisi_id') ? ' is-invalid' : '' }}"
                                id="edit-divisi-id" style="height: 50px">
                                <option value="">- Divisi -</option>
                                @foreach ($divisi as $p)
                                    <option value="{{ $p->divisi_id }}"
                                        {{ old('edit_divisi_id') == $p->divisi_id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('edit_divisi_id'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('edit_divisi_id') }}
                                </span>
                            @endif
                        </div>

                        <!-- Role User -->
                        <div class="form-group{{ $errors->has('edit_user_id') ? ' has-danger' : '' }}">
                            <label for="edit-user-id" class="col-form-label">Name user: </label>
                            <select name="edit_user_id"
                                class="form-control {{ $errors->has('edit_user_id') ? ' is-invalid' : '' }}"
                                id="edit-user-id" style="height: 50px">
                                <option value="">- User -</option>
                                @foreach ($user as $p)
                                    <option value="{{ $p->user_id }}"
                                        {{ old('edit_user_id') == $p->user_id ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('edit_user_id'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('edit_user_id') }}
                                </span>
                            @endif
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="text-white btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="text-white btn btn-primary">Update Patroli</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div class="modal fade" id="addtemuan" tabindex="-1" aria-labelledby="addtemuanTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addtemuanTitle">Tambah Temuan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form role="form" method="POST" action="" id="addtemuanForm"
                        enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <!-- Role User -->
                        <div class="form-group{{ $errors->has('temuan_patrol_id') ? ' has-danger' : '' }}">
                            <label for="temuan-patrol-id" class="col-form-label">Tanggal Patroli: </label>
                            <select name="temuan_patrol_id"
                                class="form-control {{ $errors->has('temuan_patrol_id') ? ' is-invalid' : '' }}"
                                id="temuan-patrol-id" style="height: 50px; pointer-events: none; background-color: #e9ecef;">
                                <option value="">- Tanggal -</option>
                                @foreach ($data as $p)
                                    <option value="{{ $p->patrol_id }}"
                                        {{ old('temuan_patrol_id') == $p->patrol_id ? 'selected' : '' }}>
                                        {{ $p->tanggal }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('temuan_patrol_id'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('temuan_patrol_id') }}
                                </span>
                            @endif
                        </div>

                        <!-- Role User -->
                        <div class="form-group{{ $errors->has('temuan_divisi_id') ? ' has-danger' : '' }}">
                            <label for="temuan-divisi-id" class="col-form-label">Name Divisi: </label>
                            <select name="temuan_divisi_id"
                                class="form-control {{ $errors->has('temuan_divisi_id') ? ' is-invalid' : '' }}"
                                id="temuan-divisi-id" style="height: 50px; pointer-events: none; background-color: #e9ecef;">
                                <option value="">- Divisi -</option>
                                @foreach ($divisi as $p)
                                    <option value="{{ $p->divisi_id }}"
                                        {{ old('temuan_divisi_id') == $p->divisi_id ? 'selected' : '' }}>
                                        {{ $p->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('temuan_divisi_id'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('temuan_divisi_id') }}
                                </span>
                            @endif
                        </div>

                        <!-- Role User -->
                        <div class="form-group{{ $errors->has('temuan_user_id') ? ' has-danger' : '' }}">
                            <label for="temuan-user-id" class="col-form-label">Name user: </label>
                            <select name="temuan_user_id"
                                class="form-control {{ $errors->has('temuan_user_id') ? ' is-invalid' : '' }}"
                                id="temuan-user-id" style="height: 50px; pointer-events: none; background-color: #e9ecef;">
                                <option value="">- User -</option>
                                @foreach ($user as $p)
                                    <option value="{{ $p->user_id }}"
                                        {{ old('temuan_user_id') == $p->user_id ? 'selected' : '' }}>
                                        {{ $p->name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('temuan_user_id'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('temuan_user_id') }}
                                </span>
                            @endif
                        </div>

                        <div class="">
                            <label for="temuan" class="col-form-label">Dokumentasi Temuan: </label>
                            <input type="file" name="temuan" id="temuan"
                                class="form-control{{ $errors->has('temuan') ? ' is-invalid' : '' }}"
                                placeholder="Unggah file dalam format jpg/png/jpeg (maks. 2MB)">
                            @if ($errors->has('temuan'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('temuan') }}
                                </span>
                            @endif
                        </div>

                        <!-- Description feedback -->
                        <div class="">
                            <label for="keterangan" class="col-form-label">Keterangan Temuan: </label>
                            <textarea type="text" name="keterangan" id="keterangan"
                                class="form-control{{ $errors->has('keterangan') ? ' is-invalid' : '' }}" placeholder="Keterangan Temuan">{{ old('keterangan') }}</textarea>
                            @if ($errors->has('keterangan'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('keterangan') }}
                                </span>
                            @endif
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="text-white btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="text-white btn btn-primary">Add Temuan</button>
                </div>
                </form>
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
            {{ $errors->has('user_id') || $errors->has('divisi_id') ? 'true' : 'false' }}
        ) {
            var addpatrolModal = new bootstrap.Modal(document.getElementById('addpatrol'));
            addpatrolModal.show();
            console.log(@json($errors->all()));
        }
        if (
            {{ $errors->has('edit_user_id') || $errors->has('edit_divisi_id') ? 'true' : 'false' }}
        ) {
            var editpatrolModal = new bootstrap.Modal(document.getElementById('editpatrol'));
            var url = localStorage.getItem('Url');
            editpatrolModal.show();
            $('#editpatrolForm').attr('action', url);

            console.log(@json($errors->all()));
        }
        if (
            {{ $errors->has('temuan') || $errors->has('keterangan') ? 'true' : 'false' }}
        ) {
            var editpatrolModal = new bootstrap.Modal(document.getElementById('addtemuan'));
            var url = localStorage.getItem('Url');
            editpatrolModal.show();
            $('#addtemuanForm').attr('action', url);

            console.log(@json($errors->all()));
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        var editButtons = document.querySelectorAll('.edit-button');

        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var patrolId = this.getAttribute('data-id');
                var patrolDivisi = this.getAttribute('data-divisi-id');
                var patrolUser = this.getAttribute('data-user-id');
                var actionUrl = this.getAttribute('data-url');
                localStorage.setItem('Url', actionUrl);

                console.log(patrolId, patrolDivisi, patrolUser, actionUrl, localStorage);

                // $('#edit-patrol-id').val(patrolId);
                $('#edit-user-id').val(patrolUser);
                $('#edit-divisi-id').val(patrolDivisi);

                // Atur action form untuk update
                $('#editpatrolForm').attr('action', actionUrl);
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        var editButtons = document.querySelectorAll('.temuan-button');

        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var patrolId = this.getAttribute('data-id');
                var patrolDivisi = this.getAttribute('data-divisi-id');
                var patrolUser = this.getAttribute('data-user-id');
                var actionUrl = this.getAttribute('data-url');
                localStorage.setItem('Url', actionUrl);

                console.log(patrolId, patrolDivisi, patrolUser, actionUrl);

                $('#temuan-patrol-id').val(patrolId);
                $('#temuan-user-id').val(patrolUser);
                $('#temuan-divisi-id').val(patrolDivisi);

                // Atur action form untuk update
                $('#addtemuanForm').attr('action', actionUrl);
            });
        });
    });

    // document.addEventListener('DOMContentLoaded', function() {
    //     // Event listener untuk semua tombol dengan class "admin-button"
    //     document.querySelectorAll('.admin-button').forEach(function(button) {
    //         button.addEventListener('click', function() {
    //             // Ambil URL dari atribut data-url
    //             const url = this.getAttribute('data-url');

    //             // Isi action pada form di dalam modal
    //             const form = document.getElementById('approveadminForm');
    //             form.setAttribute('action', url);
    //         });
    //     });
    // });

    // document.addEventListener('DOMContentLoaded', function() {
    //     // Event listener untuk semua tombol dengan class "admin-button"
    //     document.querySelectorAll('.manager-button').forEach(function(button) {
    //         button.addEventListener('click', function() {
    //             // Ambil URL dari atribut data-url
    //             const url = this.getAttribute('data-url');

    //             // Isi action pada form di dalam modal
    //             const form = document.getElementById('approvemanagerForm');
    //             form.setAttribute('action', url);
    //         });
    //     });
    // });

    // document.addEventListener('DOMContentLoaded', function() {
    //     var perbaikanButtons = document.querySelectorAll('.perbaikan-button');

    //     perbaikanButtons.forEach(function(button) {
    //         button.addEventListener('click', function() {
    //             var patrolId = this.getAttribute('data-patrol-id');
    //             var divisiId = this.getAttribute('data-divisi-id');
    //             var userId = this.getAttribute('data-user-id');
    //             var dokumentasiUrl = this.getAttribute('data-dokumentasi');

    //             // Debugging
    //             console.log({
    //                 patrolId,
    //                 divisiId,
    //                 userId,
    //                 dokumentasiUrl,
    //             });

    //             // Set value form input
    //             document.getElementById('patrol_id').value = patrolId;
    //             document.getElementById('divisi_id').value = divisiId;

    //             // Update ikon dokumentasi
    //             var patrolIcon = document.getElementById('patrol-icon');
    //             if (dokumentasiUrl) {
    //                 patrolIcon.src = dokumentasiUrl;
    //                 patrolIcon.style.display = 'block';
    //             } else {
    //                 patrolIcon.style.display = 'none';
    //             }
    //         });
    //     });
    // });
</script>
