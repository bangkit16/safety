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
                                    <th scope="col" colspan="2" class="text-center">Temuan Patroli</th>
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
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $d)
                                    <tr>
                                        <td>{{ $d->temuan }}</td>
                                        <td> <img src="{{ asset('storage/' . $d->dokumentasi) }}"
                                                alt="{{ $d->dokumentasi }}" class="img-fluid" style="max-width: 200px">
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
                                        <td>{{ $d->tanggal }}</td>
                                        <td>
                                            @switch($d->status)
                                                @case('Belum Dicek')
                                                    <div class="rounded bg-danger text-center p-1 fw-bolder" style="color: white">
                                                        {{ $d->status }}</div>
                                                @break

                                                @case('Setuju Admin')
                                                    <div class="rounded bg-warning text-center p-1 fw-bolder" style="color: white">
                                                        {{ $d->status }}</div>
                                                @break

                                                @default
                                                    <div class="rounded bg-success text-center p-1 fw-bolder" style="color: white">
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
                                                    @switch($d->status)
                                                        @case('Setuju Semua')
                                                            <a class="dropdown-item show-button" data-bs-toggle="modal"
                                                                data-bs-target="#showPatrolModal" data-user="{{ $d->user->name }}"
                                                                data-divisi="{{ $d->divisi->nama }}"
                                                                data-temuan="{{ $d->temuan }}"
                                                                data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}">
                                                                Show
                                                            </a>

                                                            @if (auth()->user()->role_id == 1)
                                                                <a class="dropdown-item edit-button" data-bs-toggle="modal"
                                                                    data-bs-target="#editpatrol" data-id="{{ $d->patrol_id }}"
                                                                    data-divisi-id="{{ $d->divisi_id }}"
                                                                    data-temuan="{{ $d->temuan }}"
                                                                    data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                    data-url="{{ url('patrol/' . $d->patrol_id) }}">Edit</a>
                                                            @endif
                                                        @break

                                                        @case('Belum Dicek')
                                                            <a class="dropdown-item show-button" data-bs-toggle="modal"
                                                                data-bs-target="#showPatrolModal" data-user="{{ $d->user->name }}"
                                                                data-divisi="{{ $d->divisi->nama }}"
                                                                data-temuan="{{ $d->temuan }}"
                                                                data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}">
                                                                Show
                                                            </a>

                                                            @if (auth()->user()->role_id == 1)
                                                                <a class="dropdown-item edit-button" data-bs-toggle="modal"
                                                                    data-bs-target="#editpatrol" data-id="{{ $d->patrol_id }}"
                                                                    data-divisi-id="{{ $d->divisi_id }}"
                                                                    data-temuan="{{ $d->temuan }}"
                                                                    data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                    data-url="{{ url('patrol/' . $d->patrol_id) }}">Edit</a>
                                                                <a class="dropdown-item approve-button" data-bs-toggle="modal"
                                                                    data-bs-target="#editperbaikan"
                                                                    data-divisi-id="{{ $d->divisi_id }}"
                                                                    data-patrol-id="{{ $d->patrol_id }}"
                                                                    data-user-id="{{ $d->user_id }}"
                                                                    data-url="{{ route('patrol.approve.admin') }}">Approve Admin</a>
                                                            @endif
                                                        @break

                                                        @case('Setuju Admin')
                                                            <a class="dropdown-item show-button" data-bs-toggle="modal"
                                                                data-bs-target="#showPatrolModal"
                                                                data-user="{{ $d->user->name }}"
                                                                data-divisi="{{ $d->divisi->nama }}"
                                                                data-temuan="{{ $d->temuan }}"
                                                                data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}">
                                                                Show
                                                            </a>

                                                            @if (auth()->user()->role_id == 1)
                                                                <a class="dropdown-item edit-button" data-bs-toggle="modal"
                                                                    data-bs-target="#editpatrol" data-id="{{ $d->patrol_id }}"
                                                                    data-divisi-id="{{ $d->divisi_id }}"
                                                                    data-temuan="{{ $d->temuan }}"
                                                                    data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                    data-url="{{ url('patrol/' . $d->patrol_id) }}">Edit</a>
                                                            @endif
                                                            @if (auth()->user()->role_id == 2)
                                                                <a class="dropdown-item approve-button" data-bs-toggle="modal"
                                                                    data-bs-target="#approveModal" data-id="{{ $d->patrol_id }}"
                                                                    data-url="{{ url('patrol/' . $d->patrol_id . '/manager') }}"
                                                                    title="Approve oleh Manager">
                                                                    Approve Management
                                                                </a>
                                                            @endif
                                                        @break

                                                        @default
                                                            <a class="dropdown-item show-button" data-bs-toggle="modal"
                                                                data-bs-target="#showPatrolModal"
                                                                data-user="{{ $d->user->name }}"
                                                                data-divisi="{{ $d->divisi->nama }}"
                                                                data-temuan="{{ $d->temuan }}"
                                                                data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}">
                                                                Show
                                                            </a>
                                                        @break
                                                    @endswitch
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
                                <select id="paginationLimit" class="form-control"
                                    onchange="updatePaginationLimit(this.value)" style="font-size: 12px">
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

        <div class="modal fade" id="showPatrolModal" tabindex="-1" aria-labelledby="showPatrolModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="showPatrolModalLabel"><i class="fas fa-info-circle me-2"></i>Detail
                            Patroli</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 text-center mb-3">
                                <img id="showDokumentasi" src="" alt="Dokumentasi" class="img-thumbnail"
                                    style="max-width: 100%; max-height: 300px">
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>Temuan:</strong>
                                        <span id="showTemuan" class="text-muted"></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>User:</strong>
                                        <span id="showUser" class="text-muted"></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>Divisi:</strong>
                                        <span id="showDivisi" class="text-muted"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Tutup
                        </button>
                    </div>
                </div>
            </div>
        </div>


        <!-- Modal Edit User -->
        <div class="modal fade" id="editperbaikan" tabindex="-1" aria-labelledby="editperbaikanTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editperbaikanTitle">Add Perbaikan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form role="form" method="POST" action="" id="editperbaikanForm"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Role User -->
                            <div class="form-group">
                                <label for="approve-patrol-id" class="col-form-label">Name Temuan:</label>
                                <select name="approve_patrol_id" id="approve-patrol-id"
                                    class="form-control{{ $errors->has('approve_patrol_id') ? ' is-invalid' : '' }}"
                                    style="height: 50px">
                                    <option value="">- Select Patrol -</option>
                                    @foreach ($data as $r)
                                        @if ($r->temuan == !null)
                                            <option value="{{ $r->patrol_id }}"
                                                {{ old('approve_patrol_id') == $r->patrol_id ? 'selected' : '' }}>
                                                {{ $r->temuan }}
                                            </option>
                                        @else
                                            <option value="{{ $r->patrol_id }}"
                                                {{ old('approve_patrol_id') == $r->patrol_id ? 'selected' : '' }}>
                                                Tidak ada temuan
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @if ($errors->has('approve_patrol_id'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('approve_patrol_id') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Role User -->
                            <div class="form-group">
                                <label for="approve-divisi-id" class="col-form-label">Name Divisi:</label>
                                <select name="approve_divisi_id" id="approve-divisi-id"
                                    class="form-control{{ $errors->has('approve_divisi_id') ? ' is-invalid' : '' }}"
                                    style="height: 50px">
                                    <option value="">- Select Divisi -</option>
                                    @foreach ($divisi as $r)
                                        <option value="{{ $r->divisi_id }}"
                                            {{ old('approve_divisi_id') == $r->divisi_id ? 'selected' : '' }}>
                                            {{ $r->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('approve_divisi_id'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('approve_divisi_id') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Role User -->
                            <div class="form-group">
                                <label for="approve-user-id" class="col-form-label">Name User:</label>
                                <select name="approve_user_id" id="approve-user-id"
                                    class="form-control{{ $errors->has('approve_user_id') ? ' is-invalid' : '' }}"
                                    style="height: 50px">
                                    <option value="">- Select User -</option>
                                    @foreach ($user as $r)
                                        {{-- @if (auth()->user()->role_id == 3) --}}
                                        <option value="{{ $r->user_id }}"
                                            {{ old('approve_user_id') == $r->user_id ? 'selected' : '' }}>
                                            {{ $r->name }}
                                        </option>
                                        {{-- @endif --}}
                                    @endforeach
                                </select>
                                @if ($errors->has('approve_user_id'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('approve_user_id') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Description Feedback -->
                            <div class="">
                                <label for="approve-perbaikan" class="col-form-label">Perbaikan Temuan:
                                </label>
                                <textarea name="approve_perbaikan" id="approve-perbaikan"
                                    class="form-control{{ $errors->has('approve_perbaikan') ? ' is-invalid' : '' }}" placeholder="Description Feedback">{{ old('approve_perbaikan') }}</textarea>
                                @if ($errors->has('approve_perbaikan'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('approve_perbaikan') }}
                                    </span>
                                @endif
                            </div>

                            <div class="">
                                <label for="approve-dokumentasi" class="col-form-label">Dokumentasi: </label>
                                <input type="file" name="approve_dokumentasi" id="approve_dokumentasi"
                                    class="form-control{{ $errors->has('approve_dokumentasi') ? ' is-invalid' : '' }}"
                                    placeholder="Unggah file dalam format jpg/png/jpeg (maks. 2MB)">
                                {{-- <img id="current-icon" src="" alt="Current Icon"
                                    style="max-width: 100px; margin-top: 10px; display: none;"> --}}
                                @if ($errors->has('approve_dokumentasi'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('approve_dokumentasi') }}
                                    </span>
                                @endif
                            </div>

                            <div class="form-group">
                                <label for="approve-target" class="col-form-label">Target Perbaikan: </label>
                                <input type="date" name="approve_target" id="approve-target"
                                    class="form-control{{ $errors->has('approve_target') ? ' is-invalid' : '' }}"
                                    placeholder="Name Divisi" value="{{ old('approve_target') }}">
                                @if ($errors->has('approve_target'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('approve_target') }}
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

        <!-- Modal Add User -->
        <div class="modal fade" id="addpatrol" tabindex="-1" aria-labelledby="addpatrolTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Patroli</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form role="form" method="POST" action="{{ route('patrol.store') }}"
                            enctype="multipart/form-data">
                            @csrf
                            <!-- Name role -->
                            <div class="">
                                <label for="temuan" class="col-form-label">Temuan Patroli:
                                </label>
                                <textarea name="temuan" id="temuan" class="form-control{{ $errors->has('temuan') ? ' is-invalid' : '' }}"
                                    placeholder="Description Feedback">{{ old('temuan') }}</textarea>
                                @if ($errors->has('temuan'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('temuan') }}
                                    </span>
                                @endif
                            </div>

                            <div class="">
                                <label for="dokumentasi" class="col-form-label">Temuan Dokumentasi: </label>
                                <input type="file" name="dokumentasi" id="dokumentasi"
                                    class="form-control{{ $errors->has('dokumentasi') ? ' is-invalid' : '' }}"
                                    placeholder="Unggah file dalam format jpg/png/jpeg (maks. 2MB)">
                                @if ($errors->has('dokumentasi'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('dokumentasi') }}
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

                            <!-- Description feedback -->
                            {{-- <div class="">
                                <label for="perbaikan" class="col-form-label">Perbaikan Temuan: </label>
                                <textarea type="text" name="perbaikan" id="perbaikan"
                                    class="form-control{{ $errors->has('perbaikan') ? ' is-invalid' : '' }}" placeholder="Description Feedback">{{ old('perbaikan') }}</textarea>
                                @if ($errors->has('perbaikan'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('perbaikan') }}
                                    </span>
                                @endif
                            </div> --}}
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

                            <!-- Description Feedback -->
                            <div class="">
                                <label for="edit-temuan" class="col-form-label">Temuan Patroli:
                                </label>
                                <textarea name="edit_temuan" id="edit-temuan"
                                    class="form-control{{ $errors->has('edit_temuan') ? ' is-invalid' : '' }}" placeholder="Description Feedback">{{ old('edit_temuan') }}</textarea>
                                @if ($errors->has('edit_temuan'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('edit_temuan') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Name User -->
                            <div class="">
                                <label for="edit-dokumentasi" class="col-form-label">Dokumentasi Temuan: </label>
                                <input type="file" name="edit_dokumentasi" id="edit-dokumentasi"
                                    class="form-control{{ $errors->has('edit_dokumentasi') ? ' is-invalid' : '' }}"
                                    placeholder="Project Image" value="{{ old('edit_dokumentasi') }}">
                                <img id="current-icon" src="" alt="Current Icon"
                                    style="max-width: 100px; margin-top: 10px; display: none;">
                                @if ($errors->has('edit_dokumentasi'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('edit_dokumentasi') }}
                                    </span>
                                @endif
                            </div>

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

                            <!-- Description Feedback -->
                            {{-- <div class="">
                                <label for="edit-perbaikan" class="col-form-label">Perbaikan Patrol:
                                </label>
                                <textarea  name="edit_perbaikan" id="edit-perbaikan"
                                    class="form-control{{ $errors->has('edit_perbaikan') ? ' is-invalid' : '' }}" placeholder="Description Feedback">{{ old('edit_perbaikan') }}</textarea>
                                @if ($errors->has('edit_perbaikan'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('edit_perbaikan') }}
                                    </span>
                                @endif
                            </div> --}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="text-white btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="text-white btn btn-primary">Update Patroli</button>
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
                        <form id="deletepatrolForm" method="POST">
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
                {{ $errors->has('temuan') || $errors->has('dokumentasi') || $errors->has('divisi_id') ? 'true' : 'false' }}
            ) {
                var addpatrolModal = new bootstrap.Modal(document.getElementById('addpatrol'));
                addpatrolModal.show();
                console.log(@json($errors->all()));
            }
            if (
                {{ $errors->has('approve_patrol_id') || $errors->has('approve_dokumentasi') || $errors->has('approve_divisi_id') || $errors->has('approve_user_id') || $errors->has('approve_perbaikan') || $errors->has('approve_target') ? 'true' : 'false' }}
            ) {
                var editperbaikanModal = new bootstrap.Modal(document.getElementById('editperbaikan'));
                var url = localStorage.getItem('Url');
                editperbaikanModal.show();
                $('#editperbaikanForm').attr('action', url);

                console.log(@json($errors->all()));
            }
            if (
                {{ $errors->has('edit_temuan') || $errors->has('edit_dokumentasi') || $errors->has('edit_divisi_id') ? 'true' : 'false' }}
            ) {
                var editpatrolModal = new bootstrap.Modal(document.getElementById('editpatrol'));
                var url = localStorage.getItem('Url');
                editpatrolModal.show();
                $('#editpatrolForm').attr('action', url);

                console.log(@json($errors->all()));
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            var editButtons = document.querySelectorAll('.approve-button');

            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var patrolId = this.getAttribute('data-id');
                    var patrolPatrol = this.getAttribute('data-patrol-id');
                    var patrolDivisi = this.getAttribute('data-divisi-id');
                    var patrolUser = this.getAttribute('data-user-id');
                    var actionUrl = this.getAttribute('data-url');
                    localStorage.setItem('Url', actionUrl);

                    console.log(patrolId, patrolDivisi, patrolUser, patrolPatrol, actionUrl);

                    // $('#edit-patrol-id').val(patrolId);
                    $('#approve-divisi-id').val(patrolDivisi);
                    $('#approve-patrol-id').val(patrolPatrol);
                    $('#approve-user-id').val(patrolUser);

                    // Atur action form untuk update
                    $('#editperbaikanForm').attr('action', actionUrl);
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

                    // Isi data ke modal
                    document.getElementById('showUser').innerText = user;
                    document.getElementById('showDivisi').innerText = divisi;
                    document.getElementById('showTemuan').innerText = temuan;

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
            var editButtons = document.querySelectorAll('.edit-button');

            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var patrolId = this.getAttribute('data-id');
                    var patrolName = this.getAttribute('data-temuan');
                    var patrolDokum = this.getAttribute('data-dokumentasi');
                    var patrolDivisi = this.getAttribute('data-divisi-id');
                    var actionUrl = this.getAttribute('data-url');
                    localStorage.setItem('Url', actionUrl);
                    localStorage.setItem('Image', patrolDokum);

                    console.log(patrolId, patrolName, patrolDokum, patrolDivisi, actionUrl);

                    // $('#edit-patrol-id').val(patrolId);
                    $('#edit-temuan').val(patrolName);
                    $('#edit-divisi-id').val(patrolDivisi);

                    // Update gambar ikon jika ada
                    var iconImage = $('#current-icon');
                    if (patrolDokum) {
                        iconImage.attr('src', patrolDokum); // Menggabungkan string dengan benar
                        iconImage.show(); // Menampilkan gambar jika ada
                    } else {
                        iconImage.hide(); // Menyembunyikan gambar jika tidak ada
                    }

                    // Atur action form untuk update
                    $('#editpatrolForm').attr('action', actionUrl);
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
                    document.getElementById('deletepatrolForm').setAttribute('action',
                        userDeleteUrl);
                });
            });
        });
    </script>
