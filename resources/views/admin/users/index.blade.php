@extends('admin.layouts.app', ['page' => __('User Management'), 'pageSlug' => 'users'])

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <h4 class="card-title">Users</h4>
                        </div>
                        <div class="col-4 text-right">
                            <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                data-bs-target="#adduser">
                                Add User
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @include('admin.alerts.success')
                    @include('admin.alerts.alert')
                    <div class="container-fluid">
                        <form method="GET" action="{{ route('user.index') }}" class="d-flex w-100">
                            <div class="form-group flex-grow-1 me-2">
                                <input type="text" name="search" class="form-control form-control-sm mt-1"
                                    placeholder="Search by name, email, or role" value="{{ request()->get('search') }}">
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
                                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}'">
                                            Nama
                                            @if ($sortBy === 'name')
                                                {{ $order === 'asc' ? '🔼' : '🔽' }}
                                            @endif
                                        </span>
                                    </th>
                                    <th scope="col">
                                        <span style="cursor: pointer;"
                                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort_by' => 'email', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}'">
                                            Email
                                            @if ($sortBy === 'email')
                                                {{ $order === 'asc' ? '🔼' : '🔽' }}
                                            @endif
                                        </span>
                                    </th>
                                    <th scope="col">
                                        <span style="cursor: pointer;"
                                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort_by' => 'role_id', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}'">
                                            Role
                                            @if ($sortBy === 'role_id')
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
                                        <td>{{ $d->name }}</td>
                                        <td>
                                            <a href="mailto:{{ $d->email }}">{{ $d->email }}</a>
                                        </td>
                                        <td>
                                            @foreach ($role as $p)
                                                @if ($p->role_id === $d->role_id)
                                                    {{ $p->role_name }}
                                                @endif
                                            @endforeach
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
                                                        data-bs-target="#editUser" data-id="{{ $d->user_id }}"
                                                        data-name="{{ $d->name }}" data-email="{{ $d->email }}"
                                                        data-role="{{ $d->role_id }}"
                                                        data-url="{{ url('user/' . $d->user_id) }}">Edit</a>
                                                    <a class="dropdown-item delete-button" data-bs-toggle="modal"
                                                        data-bs-target="#deleteModal" data-id="{{ $d->user_id }}"
                                                        data-url="{{ url('user/' . $d->user_id) }}">Delete</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada user yang ditemukan</td>
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
    <div class="modal fade" id="adduser" tabindex="-1" aria-labelledby="adduserTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form role="form" method="POST" action="{{ route('user.store') }}"
                        enctype="multipart/form-data">
                        @csrf

                        <!-- Name User -->
                        <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                            <label for="name" class="col-form-label">Name User:</label>
                            <input type="text" name="name" id="name"
                                class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                                placeholder="Name User" value="{{ old('name') }}">
                            @if ($errors->has('name'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('name') }}
                                </span>
                            @endif
                        </div>

                        <!-- Email -->
                        <div class="form-group{{ $errors->has('email') ? ' has-danger' : '' }}">
                            <label for="email" class="col-form-label">Email User:</label>
                            <input type="email" name="email" id="email"
                                class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="Email"
                                value="{{ old('email') }}">
                            @if ($errors->has('email'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('email') }}
                                </span>
                            @endif
                        </div>

                        <!-- Password -->
                        <div class="form-group{{ $errors->has('password') ? ' has-danger' : '' }}">
                            <label for="password" class="col-form-label">Password:</label>
                            <input type="password" name="password" id="password"
                                class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                                placeholder="Password">
                            @if ($errors->has('password'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('password') }}
                                </span>
                            @endif
                        </div>

                        <!-- Confirm Password -->
                        <div class="form-group">
                            <label for="password_confirmation" class="col-form-label">Confirm Password:</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                class="form-control" placeholder="Confirm Password">
                        </div>

                        <!-- Role User -->
                        <div class="form-group{{ $errors->has('role_id') ? ' has-danger' : '' }}">
                            <label for="role_id" class="col-form-label">Name Role:</label>
                            <select name="role_id" id="role_id"
                                class="form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}"
                                style="height: 50px">
                                <option value="">- Select Role -</option>
                                @foreach ($role as $r)
                                    <option value="{{ $r->role_id }}" {{ old('role_id') == $r->role_id ? 'selected' : '' }}>
                                        {{ $r->role_name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('role_id'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('role_id') }}
                                </span>
                            @endif
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit User -->
    <div class="modal fade" id="editUser" tabindex="-1" aria-labelledby="editUserTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserTitle">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form role="form" method="POST" action="" id="editUserForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name User -->
                        <div class="form-group{{ $errors->has('edit_name') ? ' has-danger' : '' }}">
                            <label for="edit-name" class="col-form-label">Name User: </label>
                            <input type="text" name="edit_name" id="edit-name"
                                class="form-control{{ $errors->has('edit_name') ? ' is-invalid' : '' }}"
                                placeholder="Name" value="{{ old('edit_name') }}">
                            @if ($errors->has('edit_name'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('edit_name') }}
                                </span>
                            @endif
                        </div>

                        <!-- Role User -->
                        <div class="form-group{{ $errors->has('edit_role_id') ? ' has-danger' : '' }}">
                            <label for="edit-role-id" class="col-form-label">Name Role: </label>
                            <select name="edit_role_id"
                                class="form-control {{ $errors->has('edit_role_id') ? ' is-invalid' : '' }}"
                                id="edit-role-id" style="height: 50px">
                                <option value="">- Role -</option>
                                @foreach ($role as $p)
                                    <option value="{{ $p->role_id }}"
                                        {{ old('edit_role_id') == $p->role_id ? 'selected' : '' }}>
                                        {{ $p->role_name }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('edit_role_id'))
                                <span class="invalid-feedback" role="alert">
                                    {{ $errors->first('edit_role_id') }}
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
                    Are you sure to delete data User?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <form id="deleteUserForm" method="POST">
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
            {{ $errors->has('name') || $errors->has('email') || $errors->has('password') || $errors->has('role_id') ? 'true' : 'false' }}
        ) {
            var adduserModal = new bootstrap.Modal(document.getElementById('adduser'));
            adduserModal.show();
        }
        if (
            {{ $errors->has('edit_user_name') || $errors->has('edit_role_id') ? 'true' : 'false' }}
        ) {
            var edituserModal = new bootstrap.Modal(document.getElementById('editUser'));
            var url = localStorage.getItem('Url');
            edituserModal.show();
            $('#editUserForm').attr('action', url);

            console.log(@json($errors->all()));
        }
    });


    document.addEventListener('DOMContentLoaded', function() {
        var editButtons = document.querySelectorAll('.edit-button');

        editButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var userId = this.getAttribute('data-id');
                var userName = this.getAttribute('data-name');
                var userRole = this.getAttribute('data-role');
                var actionUrl = this.getAttribute('data-url');
                localStorage.setItem('Url', actionUrl);

                console.log(actionUrl);

                $('#edit-id').val(userId);
                $('#edit-name').val(userName);
                $('#edit-role-id').val(userRole);

                // Atur action form untuk update
                $('#editUserForm').attr('action', actionUrl);
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
                document.getElementById('deleteUserForm').setAttribute('action',
                    userDeleteUrl);
            });
        });
    });
</script>
