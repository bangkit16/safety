@extends('admin.layouts.app', ['page' => ('Divisi Management'), 'pageSlug' => 'divisis'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Divisi</h4>
                        </div>
                        <div class="col-4 text-right">
                            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                data-bs-target="#adddivisi">
                                Add Divisi
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.alerts.success')
                    @include('admin.alerts.alert')
                    <div class="container-fluid">
                        <form method="GET" action="{{ route('role.index') }}" class="d-flex w-100">
                            <div class="form-group flex-grow-1 me-2">
                                <input type="text" name="search" class="form-control form-control-sm mt-1"
                                    placeholder="Cari berdasarkan nama role" value="{{ request()->get('search') }}">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary mt-1"><i
                                        class="tim-icons icon-zoom-split"></i></button>
                            </div>
                        </form>
                    </div>

                    <div class="">
                        <table class="table table-responsive-xl " id="">
                            <thead class="text-primary">
                                <tr>
                                    <th scope="col">
                                        <span style="cursor: pointer;"
                                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort_by' => 'nama', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}'">
                                            Nama Divisi
                                            @if ($sortBy === 'nama')
                                                {{ $order === 'asc' ? '🔼' : '🔽' }}
                                            @endif
                                        </span>
                                    </th>
                                    <th scope="col">
                                        <span style="cursor: pointer;"
                                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}'">
                                            Tanggal Dibuat
                                            @if ($sortBy === 'created_at')
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
                                        <td>{{ $d->nama }}</td>
                                        <td>{{ $d->created_at }}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item edit-button" data-bs-toggle="modal"
                                                        data-bs-target="#editdivisi" data-id="{{ $d->divisi_id }}"
                                                        data-nama="{{ $d->nama }}"
                                                        data-tanda-tangan="{{ $d->tanda_tangan ? asset('storage/' . $d->tanda_tangan) : '' }}"
                                                        data-url="{{ url('divisi/' . $d->divisi_id) }}">Edit</a>
                                                    <a class="dropdown-item
                                                        delete-button"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                        data-id="{{ $d->divisi_id }}"
                                                        data-url="{{ url('divisi/' . $d->divisi_id) }}">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada divisi yang ditemukan</td>
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
    <!-- Modal Add role-->
    <div class="modal fade" id="adddivisi" tabindex="-1" aria-labelledby="adddivisiTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Divisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form role="form" method="POST" action="{{ route('divisi.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Name role -->
                        <div class="form-group">
                            <label for="nama" class="col-form-label">Name Divisi: </label>
                            <input type="text" name="nama" id="nama"
                                class="form-control{{ $errors->has('nama') ? ' is-invalid' : '' }}"
                                placeholder="Name Divisi" value="{{ old('nama') }}">
                            @if ($errors->has('nama'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('nama') }}
                                </span>
                            @endif
                        </div>

                        <!-- Name role -->
                        <div class="">
                            <label for="tanda_tangan" class="col-form-label">Tanda Tangan: </label>

                            {{-- @if (isset($data->tanda_tangan))
                                <div class="mb-3">
                                    <label>Preview Tanda Tangan:</label>
                                    <img src="{{ asset('storage/' . $data->tanda_tangan) }}" alt="Tanda Tangan"
                                        class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            @endif --}}

                            <input type="file" name="tanda_tangan" id="tanda_tangan"
                                class="form-control{{ $errors->has('tanda_tangan') ? ' is-invalid' : '' }}"
                                placeholder="Unggah file tanda tangan dalam format jpg/png/jpeg (maks. 2MB)">

                            @if ($errors->has('tanda_tangan'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('tanda_tangan') }}
                                </span>
                            @endif
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Divisi</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit role -->
    <div class="modal fade" id="editdivisi" tabindex="-1" aria-labelledby="editdivisiTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editdivisiTitle">Edit Divisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form role="form" method="POST" action="" id="editdivisiForm"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name role -->
                        <div class="form-group">
                            <label for="edit-nama" class="col-form-label">Name Role: </label>
                            <input type="text" name="edit_nama" id="edit-nama"
                                class="form-control{{ $errors->has('edit_nama') ? ' is-invalid' : '' }}"
                                placeholder="Name Divisi" value="{{ old('edit_nama') }}">
                            @if ($errors->has('edit_nama'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('edit_nama') }}
                                </span>
                            @endif
                        </div>

                        <!-- Project Galery -->
                        <div class="">
                            <label for="edit-tanda-tangan" class="col-form-label">Project Image: </label>
                            <input type="file" name="edit_tanda_tangan" id="edit-tanda-tangan"
                                class="form-control{{ $errors->has('edit_tanda_tangan') ? ' is-invalid' : '' }}"
                                placeholder="Project Image" value="{{ old('edit_tanda_tangan') }}">
                            <img id="current-icon" src="" alt="Current Icon"
                                style="max-width: 100px; margin-top: 10px; display: none;">
                            @if ($errors->has('edit_tanda_tangan'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('edit_tanda_tangan') }}
                                </span>
                            @endif
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="text-white btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="text-white btn btn-primary">Update Tanda Tangan</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete role -->
    <div class="modal fade" id="deleteModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Delete Divisi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure to delete data divisi?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form id="deletedivisiForm" method="POST">
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
        if (
            {{ $errors->has('nama') || $errors->has('tanda_tangan') ? 'true' : 'false' }}
        ) {
            var adddivisiModal = new bootstrap.Modal(document.getElementById('adddivisi'));
            adddivisiModal.show();
        }

        // Check and show the editdivisi modal if there are errors for edit role
        if (
            {{ $errors->has('edit_nama') || $errors->has('edit_tanda_tangan') ? 'true' : 'false' }}
        ) {
            var editdivisiModal = new bootstrap.Modal(document.getElementById('editdivisi'));
            var url = localStorage.getItem('Url');
            editdivisiModal.show();
            $('#editdivisiForm').attr('action', url);

            console.log(@json($errors->all()));
        }
    });


    document.addEventListener('DOMContentLoaded', function() {
        var editButtons = document.querySelectorAll('.edit-button');

        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var divisiId = this.getAttribute('data-id');
                var divisiName = this.getAttribute('data-nama');
                var divisiDetail = this.getAttribute('data-tanda-tangan');
                var actionUrl = this.getAttribute('data-url');
                localStorage.setItem('Url', actionUrl);
                localStorage.setItem('Image', divisiName);

                console.log(divisiDetail, divisiName);

                $('#edit-divisi-id').val(divisiId);
                $('#edit-nama').val(divisiName);

                // Update gambar ikon jika ada
                var iconImage = $('#current-icon');
                if (divisiDetail) {
                    iconImage.attr('src', divisiDetail); // Menggabungkan string dengan benar
                    iconImage.show(); // Menampilkan gambar jika ada
                } else {
                    iconImage.hide(); // Menyembunyikan gambar jika tidak ada
                }

                // Atur action form untuk update
                $('#editdivisiForm').attr('action', actionUrl);
            });
        });
    });




    document.addEventListener('DOMContentLoaded', function() {
        // Ketika tombol delete diklik
        document.querySelectorAll('.delete-button').forEach(function(button) {
            button.addEventListener('click', function() {
                // Ambil data dari atribut data-*
                var roleId = this.getAttribute('data-id');
                var roleDeleteUrl = this.getAttribute('data-url');

                // Atur action form untuk delete
                document.getElementById('deletedivisiForm').setAttribute('action',
                    roleDeleteUrl);
            });
        });
    });
</script>
