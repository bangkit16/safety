@extends('admin.layouts.app', ['page' => __('Role Management'), 'pageSlug' => 'roles'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Roles</h4>
                        </div>
                        <div class="col-4 text-right">
                            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                data-bs-target="#addrole">
                                Add Role
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
                                <button type="submit" class="btn btn-secondary mt-1"><i class="tim-icons icon-zoom-split"></i></button>
                            </div>
                        </form>
                    </div>

                    <div class="">
                        <table class="table table-responsive-xl " id="">
                            <thead class="text-primary">
                                <tr>
                                    <th scope="col">
                                        <span style="cursor: pointer;"
                                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort_by' => 'role_name', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}'">
                                            Nama Role
                                            @if ($sortBy === 'role_name')
                                                {{ $order === 'asc' ? '🔼' : '🔽' }}
                                            @endif
                                        </span>
                                    </th>
                                    <th scope="col">
                                        <span style="cursor: pointer;"
                                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort_by' => 'role_description', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}'">
                                            Deskripsi Role
                                            @if ($sortBy === 'role_description')
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
                                        <td>{{ $d->role_name }}</td>
                                        <td>{{ $d->role_description }}</td>
                                        </td>
                                        <td>{{ $d->created_at }}</td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item edit-button" data-bs-toggle="modal"
                                                        data-bs-target="#editrole" data-id="{{ $d->role_id }}"
                                                        data-name="{{ $d->role_name }}"
                                                        data-description="{{ $d->role_description }}"
                                                        data-url="{{ url('role/' . $d->role_id) }}">Edit</a>
                                                    <a class="dropdown-item
                                                        delete-button"
                                                        data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                        data-id="{{ $d->role_id }}"
                                                        data-url="{{ url('role/' . $d->role_id) }}">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center">Tidak ada role yang ditemukan</td>
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
    <div class="modal fade" id="addrole" tabindex="-1" aria-labelledby="addroleTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form role="form" method="POST" action="{{ route('role.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Name role -->
                        <div class="form-group">
                            <label for="role_name" class="col-form-label">Name Role: </label>
                            <input type="text" name="role_name" id="role_name"
                                class="form-control{{ $errors->has('role_name') ? ' is-invalid' : '' }}"
                                placeholder="Name Role" value="{{ old('role_name') }}">
                            @if ($errors->has('role_name'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('role_name') }}
                                </span>
                            @endif
                        </div>

                        <!-- Description role -->
                        <div class="form-group">
                            <label for="role_description" class="col-form-label">Description Role:
                            </label>
                            <textarea type="text" name="role_description" id="role_description"
                                class="form-control{{ $errors->has('role_description') ? ' is-invalid' : '' }}" placeholder="Description Role">{{ old('role_description') }}</textarea>
                            @if ($errors->has('role_description'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('role_description') }}
                                </span>
                            @endif
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Role</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit role -->
    <div class="modal fade" id="editrole" tabindex="-1" aria-labelledby="editroleTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editroleTitle">Edit role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form role="form" method="POST" action="" id="editroleForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name role -->
                        <div class="form-group">
                            <label for="edit-role-name" class="col-form-label">Name Role: </label>
                            <input type="text" name="edit_role_name" id="edit-role-name"
                                class="form-control{{ $errors->has('edit_role_name') ? ' is-invalid' : '' }}"
                                placeholder="Name Role" value="{{ old('edit_role_name') }}">
                            @if ($errors->has('edit_role_name'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('edit_role_name') }}
                                </span>
                            @endif
                        </div>

                        <!-- Description role -->
                        <div class="form-group">
                            <label for="edit-role-description" class="col-form-label">Description Role:
                            </label>
                            <textarea type="text" name="edit_role_description" id="edit-role-description"
                                class="form-control{{ $errors->has('edit_role_description') ? ' is-invalid' : '' }}"
                                placeholder="Description Role">{{ old('edit_role_description') }}</textarea>
                            @if ($errors->has('edit_role_description'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('edit_role_description') }}
                                </span>
                            @endif
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="text-white btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="text-white btn btn-primary">Update Role</button>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Delete Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure to delete data role?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form id="deleteroleForm" method="POST">
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
            {{ $errors->has('role_name') || $errors->has('role_description') ? 'true' : 'false' }}
        ) {
            var addroleModal = new bootstrap.Modal(document.getElementById('addrole'));
            addroleModal.show();
        }

        // Check and show the editrole modal if there are errors for edit role
        if (
            {{ $errors->has('edit_role_name') || $errors->has('edit_role_description') ? 'true' : 'false' }}
        ) {
            var editroleModal = new bootstrap.Modal(document.getElementById('editrole'));
            var url = localStorage.getItem('Url');
            editroleModal.show();
            $('#editroleForm').attr('action', url);

            console.log(@json($errors->all()));
        }
    });


    document.addEventListener('DOMContentLoaded', function() {
        var editButtons = document.querySelectorAll('.edit-button');

        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var roleId = this.getAttribute('data-id');
                var roleName = this.getAttribute('data-name');
                var roleDescription = this.getAttribute('data-description');
                var actionUrl = this.getAttribute('data-url');
                localStorage.setItem('Url', actionUrl);

                console.log(actionUrl);

                $('#edit-id').val(roleId);
                $('#edit-role-name').val(roleName);
                $('#edit-role-description').val(roleDescription);

                // Atur action form untuk update
                $('#editroleForm').attr('action', actionUrl);
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
                document.getElementById('deleteroleForm').setAttribute('action',
                    roleDeleteUrl);
            });
        });
    });
</script>
