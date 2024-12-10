@extends('admin.layouts.app', ['page' => 'Input Patrol', 'pageSlug' => 'input-perbaikan'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Patroli Perbaikan</h4>
                        </div>
                        <div class="col-4 text-right">
                            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                data-bs-target="#addperbaikan">
                                Add Perbaikan
                            </button>
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
                                    <th scope="col"class="text-center">Temuan Patroli</th>
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
                                            Nama Patroli
                                            @if ($sortBy === 'user_id')
                                                {{ $order === 'asc' ? '🔼' : '🔽' }}
                                            @endif
                                        </span>
                                    </th>
                                    <th scope="col">
                                        <span style="cursor: pointer;"
                                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort_by' => 'target', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}'">
                                            Target Perbaikan
                                            @if ($sortBy === 'target')
                                                {{ $order === 'asc' ? '🔼' : '🔽' }}
                                            @endif
                                        </span>
                                    </th>
                                    <th scope="col" colspan="2" class="text-center">Perbaikan Temuan</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $d)
                                    <tr>
                                        <td>
                                            @foreach ($apar as $p)
                                                @if ($p->apar_id == $d->apar_id)
                                                    {{ $p->temuan }}
                                                @endif
                                            @endforeach
                                        </td>
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
                                        <td>{{ $d->target }}</td>
                                        <td>{{ $d->perbaikan }}</td>
                                        <td> <img src="{{ asset('storage/' . $d->dokumentasi) }}"
                                                alt="{{ $d->dokumentasi }}" class="img-fluid" style="max-width: 200px">
                                        </td>
                                        <td>
                                            @switch($d->status)
                                                @case('Selesai')
                                                    <div class="rounded bg-success text-center p-1 fw-bolder" style="color: white">
                                                        {{ $d->status }}</div>
                                                @break

                                                @default
                                                    <div class="rounded bg-warning text-center p-1 fw-bolder" style="color: white">
                                                        {{ $d->status }}</div>
                                            @endswitch
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item edit-button" data-bs-toggle="modal"
                                                        data-bs-target="#editperbaikan" data-id="{{ $d->perbaikan_id }}"
                                                        data-divisi-id="{{ $d->divisi_id }}"
                                                        data-patrol-id="{{ $d->patrol_id }}"
                                                        data-user-id="{{ $d->user_id }}"
                                                        data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                        data-target="{{ $d->target }}"
                                                        data-perbaikan="{{ $d->perbaikan }}"
                                                        data-url="{{ url('perbaikan/' . $d->perbaikan_id) }}">Edit</a>
                                                    <a class="dropdown-item delete-button" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal" data-id="{{ $d->perbaikan_id }}"
                                                        data-url="{{ url('perbaikan/' . $d->perbaikan_id) }}">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data yang ditemukan</td>
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
        <div class="modal fade" id="addperbaikan" tabindex="-1" aria-labelledby="addperbaikanTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Perbaikan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form role="form" method="POST" action="{{ route('perbaikan.store') }}"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Role User -->
                            <div class="form-group">
                                <label for="patrol_id" class="col-form-label">Name Temuan:</label>
                                <select name="patrol_id" id="patrol_id"
                                    class="form-control{{ $errors->has('patrol_id') ? ' is-invalid' : '' }}"
                                    style="height: 50px">
                                    <option value="">- Select Patrol -</option>
                                    @foreach ($apar as $r)
                                        <option value="{{ $r->patrol_id }}"
                                            {{ old('patrol_id') == $r->patrol_id ? 'selected' : '' }}>
                                            {{ $r->temuan }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('patrol_id'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('patrol_id') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Role User -->
                            <div class="form-group">
                                <label for="divisi_id" class="col-form-label">Name Divisi:</label>
                                <select name="divisi_id" id="divisi_id"
                                    class="form-control{{ $errors->has('divisi_id') ? ' is-invalid' : '' }}"
                                    style="height: 50px">
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
                                        {{-- @if (auth()->user()->role_id == 3) --}}
                                        <option value="{{ $r->user_id }}"
                                            {{ old('user_id') == $r->user_id ? 'selected' : '' }}>
                                            {{ $r->name }}
                                        </option>

                                        {{-- @endif --}}
                                    @endforeach
                                </select>
                                @if ($errors->has('user_id'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('user_id') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Description feedback -->
                            <div class="">
                                <label for="perbaikan" class="col-form-label">Perbaikan Temuan: </label>
                                <textarea type="text" name="perbaikan" id="perbaikan"
                                    class="form-control{{ $errors->has('perbaikan') ? ' is-invalid' : '' }}" placeholder="Description Feedback">{{ old('perbaikan') }}</textarea>
                                @if ($errors->has('perbaikan'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('perbaikan') }}
                                    </span>
                                @endif
                            </div>

                            <div class="">
                                <label for="dokumentasi" class="col-form-label">Dokumentasi: </label>
                                <input type="file" name="dokumentasi" id="dokumentasi"
                                    class="form-control{{ $errors->has('dokumentasi') ? ' is-invalid' : '' }}"
                                    placeholder="Unggah file dalam format jpg/png/jpeg (maks. 2MB)">
                                @if ($errors->has('dokumentasi'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('dokumentasi') }}
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="target" class="col-form-label">Target Perbaikan: </label>
                                <input type="date" name="target" id="target"
                                    class="form-control{{ $errors->has('target') ? ' is-invalid' : '' }}"
                                    placeholder="Name Divisi" value="{{ old('target') }}">
                                @if ($errors->has('target'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('target') }}
                                    </span>
                                @endif
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Perbaikan</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Edit User -->
        <div class="modal fade" id="editperbaikan" tabindex="-1" aria-labelledby="editperbaikanTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editperbaikanTitle">Edit Perbaikan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form role="form" method="POST" action="" id="editperbaikanForm"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Role User -->
                            <div class="form-group">
                                <label for="edit-patrol-id" class="col-form-label">Name Temuan:</label>
                                <select name="edit_patrol_id" id="edit-patrol-id"
                                    class="form-control{{ $errors->has('edit_patrol_id') ? ' is-invalid' : '' }}"
                                    style="height: 50px">
                                    <option value="">- Select Patrol -</option>
                                    @foreach ($apar as $r)
                                        <option value="{{ $r->patrol_id }}"
                                            {{ old('edit_patrol_id') == $r->patrol_id ? 'selected' : '' }}>
                                            {{ $r->temuan }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('edit_patrol_id'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('edit_patrol_id') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Role User -->
                            <div class="form-group">
                                <label for="edit-divisi-id" class="col-form-label">Name Divisi:</label>
                                <select name="edit_divisi_id" id="edit-divisi-id"
                                    class="form-control{{ $errors->has('edit_divisi_id') ? ' is-invalid' : '' }}"
                                    style="height: 50px">
                                    <option value="">- Select Divisi -</option>
                                    @foreach ($divisi as $r)
                                        <option value="{{ $r->divisi_id }}"
                                            {{ old('edit_divisi_id') == $r->divisi_id ? 'selected' : '' }}>
                                            {{ $r->nama }}
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
                            <div class="form-group">
                                <label for="edit-user-id" class="col-form-label">Name User:</label>
                                <select name="edit_user_id" id="edit-user-id"
                                    class="form-control{{ $errors->has('edit_user_id') ? ' is-invalid' : '' }}"
                                    style="height: 50px">
                                    <option value="">- Select User -</option>
                                    @foreach ($user as $r)
                                        {{-- @if (auth()->user()->role_id == 3) --}}
                                        <option value="{{ $r->user_id }}"
                                            {{ old('edit_user_id') == $r->user_id ? 'selected' : '' }}>
                                            {{ $r->name }}
                                        </option>
                                        {{-- @endif --}}
                                    @endforeach
                                </select>
                                @if ($errors->has('edit_user_id'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('edit_user_id') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Description Feedback -->
                            <div class="">
                                <label for="edit-perbaikan" class="col-form-label">Perbaikan Patrol:
                                </label>
                                <textarea name="edit_perbaikan" id="edit-perbaikan"
                                    class="form-control{{ $errors->has('edit_perbaikan') ? ' is-invalid' : '' }}" placeholder="Description Feedback">{{ old('edit_perbaikan') }}</textarea>
                                @if ($errors->has('edit_perbaikan'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('edit_perbaikan') }}
                                    </span>
                                @endif
                            </div>

                            <div class="">
                                <label for="edit-dokumentasi" class="col-form-label">Dokumentasi: </label>
                                <input type="file" name="edit_dokumentasi" id="edit_dokumentasi"
                                    class="form-control{{ $errors->has('edit_dokumentasi') ? ' is-invalid' : '' }}"
                                    placeholder="Unggah file dalam format jpg/png/jpeg (maks. 2MB)">
                                <img id="current-icon" src="" alt="Current Icon"
                                    style="max-width: 100px; margin-top: 10px; display: none;">
                                @if ($errors->has('edit_dokumentasi'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('edit_dokumentasi') }}
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="edit-target" class="col-form-label">Target Perbaikan: </label>
                                <input type="date" name="edit_target" id="edit-target"
                                    class="form-control{{ $errors->has('edit_target') ? ' is-invalid' : '' }}"
                                    placeholder="Name Divisi" value="{{ old('edit_target') }}">
                                @if ($errors->has('edit_target'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('edit_target') }}
                                    </span>
                                @endif
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="text-white btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="text-white btn btn-primary">Update Perbaikan</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Delete User -->
        <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
            aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Delete User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure to delete data?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <form id="deleteperbaikanForm" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-primary">Delete</button>
                        </form>
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
