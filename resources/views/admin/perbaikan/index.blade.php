@extends('admin.layouts.app', ['page' => 'Input Patrol', 'pageSlug' => 'input-perbaikan'])

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
                            {{-- @if (auth()->user()->role_id == 1 && auth()->user()->role_id == 3) --}}
                            {{-- <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal"
                                    data-bs-target="#addperbaikan">
                                    Add Temuan
                                </button> --}}
                            {{-- @endif --}}
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
                                    placeholder="Search by tanggal target, temuan, perbaikan or status"
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
                                    <th scope="col" colspan="2">
                                        <span style="cursor: pointer;"
                                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort_by' => 'keterangan', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}'">
                                            Keterangan Temuan
                                            @if ($sortBy === 'keterangan')
                                                {{ $order === 'asc' ? '🔼' : '🔽' }}
                                            @endif
                                        </span>
                                    </th>
                                    <th scope="col">
                                        <span style="cursor: pointer;"
                                            onclick="window.location.href='{{ request()->fullUrlWithQuery(['sort_by' => 'perbaikan', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}'">
                                            Rekomendasi Perbaikan
                                            @if ($sortBy === 'perbaikan')
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
                                    <th scope="col">Temuan Patroli</th>
                                    <th scope="col">Rekomendasi Perbaikan</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $d)
                                    <tr>
                                        <td>
                                            <img src="{{ asset('storage/' . $d->temuan) }}" alt="{{ $d->temuan }}"
                                                class="img-fluid" style="max-width: 200px">
                                        </td>
                                        <td>{{ $d->keterangan }}</td>
                                        <td>
                                            {{ $d->perbaikan }}
                                        </td>
                                        <td>
                                            {{ $d->target }}
                                        </td>
                                        <td> <img src="{{ asset('storage/' . $d->dokumentasi) }}"
                                                alt="{{ $d->dokumentasi }}" class="img-fluid" style="max-width: 200px">
                                        </td>
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

                                                @case('Lolos Admin')
                                                    <div class="rounded bg-warning text-center p-1 fw-bolder" style="color: white">
                                                        {{ $d->status }}</div>
                                                @break

                                                @case('Proses')
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
                                                        @case('Belum Dicek')
                                                            <a class="dropdown-item show-button" data-bs-toggle="modal"
                                                                data-bs-target="#showPatrolModal"
                                                                data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                data-keterangan="{{ $d->keterangan }}"
                                                                data-perbaikan="{{ $d->perbaikan }}"
                                                                data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                data-tanggal="{{ $d->target }}">
                                                                Show
                                                            </a>
                                                            @if (auth()->user()->role_id == 1)
                                                                <a class="dropdown-item edit-button" data-bs-toggle="modal"
                                                                    data-bs-target="#editperbaikan" data-id="{{ $d->patrol_id }}"
                                                                    data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                    data-keterangan="{{ $d->keterangan }}"
                                                                    data-perbaikan="{{ $d->perbaikan }}"
                                                                    data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                    data-tanggal="{{ $d->target }}"
                                                                    data-url="{{ url('perbaikan/' . $d->patrol_id) }}">Edit</a>
                                                                <a class="dropdown-item admin-button" data-bs-toggle="modal"
                                                                    data-bs-target="#approveadmin"
                                                                    data-url="{{ url('patrol/' . $d->patrol_id . '/admin') }}">
                                                                    Setuju Admin
                                                                </a>
                                                            @endif
                                                        @break

                                                        @case('Setuju Admin')
                                                            <a class="dropdown-item show-button" data-bs-toggle="modal"
                                                                data-bs-target="#showPatrolModal"
                                                                data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                data-keterangan="{{ $d->keterangan }}"
                                                                data-perbaikan="{{ $d->perbaikan }}"
                                                                data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                data-tanggal="{{ $d->target }}">
                                                                Show
                                                            </a>
                                                            @if (auth()->user()->role_id == 1)
                                                                <a class="dropdown-item edit-button" data-bs-toggle="modal"
                                                                    data-bs-target="#editperbaikan" data-id="{{ $d->patrol_id }}"
                                                                    data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                    data-keterangan="{{ $d->keterangan }}"
                                                                    data-perbaikan="{{ $d->perbaikan }}"
                                                                    data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                    data-tanggal="{{ $d->target }}"
                                                                    data-url="{{ url('perbaikan/' . $d->patrol_id) }}">Edit</a>
                                                            @endif
                                                            @if (auth()->user()->role_id == 2)
                                                                <a class="dropdown-item manager-button" data-bs-toggle="modal"
                                                                    data-bs-target="#approvemanager"
                                                                    data-url="{{ url('patrol/' . $d->patrol_id . '/manager') }}">
                                                                    Setuju Management
                                                                </a>
                                                            @endif
                                                        @break

                                                        @case('Setuju Semua')
                                                            <a class="dropdown-item show-button" data-bs-toggle="modal"
                                                                data-bs-target="#showPatrolModal"
                                                                data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                data-keterangan="{{ $d->keterangan }}"
                                                                data-perbaikan="{{ $d->perbaikan }}"
                                                                data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                data-tanggal="{{ $d->target }}">
                                                                Show
                                                            </a>
                                                            @if (auth()->user()->role_id == 1)
                                                                <a class="dropdown-item edit-button" data-bs-toggle="modal"
                                                                    data-bs-target="#editperbaikan" data-id="{{ $d->patrol_id }}"
                                                                    data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                    data-keterangan="{{ $d->keterangan }}"
                                                                    data-perbaikan="{{ $d->perbaikan }}"
                                                                    data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                    data-tanggal="{{ $d->target }}"
                                                                    data-url="{{ url('perbaikan/' . $d->patrol_id) }}">Edit</a>
                                                                <a class="dropdown-item perbaikan-button" data-bs-toggle="modal"
                                                                    data-bs-target="#addperbaikan"
                                                                    data-id="{{ $d->perbaikan_id }}"
                                                                    data-patrol-id="{{ $d->patrol_id }}"
                                                                    data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                    data-keterangan="{{ $d->keterangan }}"
                                                                    data-url="{{ url('perbaikan/form/' . $d->perbaikan_id) }}">Form
                                                                    Perbaikan</a>
                                                            @endif
                                                            @if (auth()->user()->role_id == 3)
                                                                <a class="dropdown-item perbaikan-button" data-bs-toggle="modal"
                                                                    data-bs-target="#addperbaikan"
                                                                    data-id="{{ $d->perbaikan_id }}"
                                                                    data-patrol-id="{{ $d->patrol_id }}"
                                                                    data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                    data-keterangan="{{ $d->keterangan }}"
                                                                    data-url="{{ url('perbaikan/form/' . $d->perbaikan_id) }}">Form
                                                                    Perbaikan</a>
                                                            @endif
                                                        @break

                                                        @case('Proses')
                                                            <a class="dropdown-item show-button" data-bs-toggle="modal"
                                                                data-bs-target="#showPatrolModal"
                                                                data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                data-keterangan="{{ $d->keterangan }}"
                                                                data-perbaikan="{{ $d->perbaikan }}"
                                                                data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                data-tanggal="{{ $d->target }}">
                                                                Show
                                                            </a>
                                                            @if (auth()->user()->role_id == 1)
                                                                <a class="dropdown-item edit-button" data-bs-toggle="modal"
                                                                    data-bs-target="#editperbaikan" data-id="{{ $d->patrol_id }}"
                                                                    data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                    data-keterangan="{{ $d->keterangan }}"
                                                                    data-perbaikan="{{ $d->perbaikan }}"
                                                                    data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                    data-tanggal="{{ $d->target }}"
                                                                    data-url="{{ url('perbaikan/' . $d->patrol_id) }}">Edit</a>
                                                            @endif
                                                            @if (auth()->user()->role_id == 3 && auth()->user()->role_id == 1)
                                                                <a class="dropdown-item dokum-button" data-bs-toggle="modal"
                                                                    data-bs-target="#dokumModal" data-id="{{ $d->perbaikan_id }}"
                                                                    data-perbaikan="{{ $d->perbaikan }}"
                                                                    data-url="{{ url('perbaikan/dokumentasi/' . $d->perbaikan_id) }}">Dokumentasi
                                                                    Perbaikan</a>
                                                            @endif
                                                        @break

                                                        @case('Selesai')
                                                            <a class="dropdown-item show-button" data-bs-toggle="modal"
                                                                data-bs-target="#showPatrolModal"
                                                                data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                data-keterangan="{{ $d->keterangan }}"
                                                                data-perbaikan="{{ $d->perbaikan }}"
                                                                data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                data-tanggal="{{ $d->target }}">
                                                                Show
                                                            </a>
                                                            @if (auth()->user()->role_id == 1)
                                                                <a class="dropdown-item edit-button" data-bs-toggle="modal"
                                                                    data-bs-target="#editperbaikan" data-id="{{ $d->patrol_id }}"
                                                                    data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                    data-keterangan="{{ $d->keterangan }}"
                                                                    data-perbaikan="{{ $d->perbaikan }}"
                                                                    data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                    data-tanggal="{{ $d->target }}"
                                                                    data-url="{{ url('perbaikan/' . $d->patrol_id) }}">Edit</a>
                                                                <a class="dropdown-item admin-setuju" data-bs-toggle="modal"
                                                                    data-bs-target="#setujuadmin"
                                                                    data-url="{{ url('perbaikan/' . $d->patrol_id . '/admin') }}">
                                                                    Approve Admin
                                                                </a>
                                                            @endif
                                                        @break

                                                        @case('Lolos Admin')
                                                            <a class="dropdown-item show-button" data-bs-toggle="modal"
                                                                data-bs-target="#showPatrolModal"
                                                                data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                data-keterangan="{{ $d->keterangan }}"
                                                                data-perbaikan="{{ $d->perbaikan }}"
                                                                data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                data-tanggal="{{ $d->target }}">
                                                                Show
                                                            </a>
                                                            @if (auth()->user()->role_id == 1)
                                                                <a class="dropdown-item edit-button" data-bs-toggle="modal"
                                                                    data-bs-target="#editperbaikan"
                                                                    data-id="{{ $d->patrol_id }}"
                                                                    data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                    data-keterangan="{{ $d->keterangan }}"
                                                                    data-perbaikan="{{ $d->perbaikan }}"
                                                                    data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                    data-tanggal="{{ $d->target }}"
                                                                    data-url="{{ url('perbaikan/' . $d->patrol_id) }}">Edit</a>
                                                            @endif
                                                            @if (auth()->user()->role_id == 2)
                                                                <a class="dropdown-item manager-setuju" data-bs-toggle="modal"
                                                                    data-bs-target="#setujumanager"
                                                                    data-url="{{ url('perbaikan/' . $d->patrol_id . '/manager') }}">
                                                                    Approve Management
                                                                </a>
                                                            @endif
                                                        @break

                                                        @case('Lolos Semua')
                                                            <a class="dropdown-item show-button" data-bs-toggle="modal"
                                                                data-bs-target="#showPatrolModal"
                                                                data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                data-keterangan="{{ $d->keterangan }}"
                                                                data-perbaikan="{{ $d->perbaikan }}"
                                                                data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                data-tanggal="{{ $d->target }}">
                                                                Show
                                                            </a>
                                                            @if (auth()->user()->role_id == 1)
                                                                <a class="dropdown-item edit-button" data-bs-toggle="modal"
                                                                    data-bs-target="#editperbaikan"
                                                                    data-id="{{ $d->patrol_id }}"
                                                                    data-temuan="{{ $d->temuan ? asset('storage/' . $d->temuan) : '' }}"
                                                                    data-keterangan="{{ $d->keterangan }}"
                                                                    data-perbaikan="{{ $d->perbaikan }}"
                                                                    data-dokumentasi="{{ $d->dokumentasi ? asset('storage/' . $d->dokumentasi) : '' }}"
                                                                    data-tanggal="{{ $d->target }}"
                                                                    data-url="{{ url('perbaikan/' . $d->patrol_id) }}">Edit</a>
                                                            @endif
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

        <!-- Modal Add User -->
        <div class="modal fade" id="addperbaikan" tabindex="-1" aria-labelledby="addperbaikanTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Perbaikan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form role="form" method="POST" action="" id="addperbaikanForm"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Role User -->
                            <div class="form-group">
                                <label for="patrol_id" class="col-form-label">Tanggal Patroli:</label>
                                <select name="patrol_id" id="patrol_id"
                                    class="form-control{{ $errors->has('patrol_id') ? ' is-invalid' : '' }}"
                                    style="height: 50px; pointer-events: none; background-color: #e9ecef;">
                                    <option value="">- Select Patrol -</option>
                                    @foreach ($apar as $r)
                                        <option value="{{ $r->patrol_id }}"
                                            {{ old('patrol_id') == $r->patrol_id ? 'selected' : '' }}>
                                            {{ $r->tanggal }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('patrol_id'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('patrol_id') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Description feedback -->
                            <img id="temuan-gambar" src="" alt="Current Icon"
                                style="max-width: 100px; margin-top: 10px; display: none;">
                            <div class="">
                                <label for="keterangan" class="col-form-label">Keterangan Temuan: </label>
                                <textarea type="text" name="keterangan" id="keterangan"
                                    class="form-control{{ $errors->has('keterangan') ? ' is-invalid' : '' }}" placeholder="Description Feedback" readonly>{{ old('keterangan') }}</textarea>
                                @if ($errors->has('keterangan'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('keterangan') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Description feedback -->
                            <div class="">
                                <label for="perbaikan" class="col-form-label">Rekomendasi Perbaikan: </label>
                                <textarea type="text" name="perbaikan" id="perbaikan"
                                    class="form-control{{ $errors->has('perbaikan') ? ' is-invalid' : '' }}" placeholder="Description Feedback">{{ old('perbaikan') }}</textarea>
                                @if ($errors->has('perbaikan'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('perbaikan') }}
                                    </span>
                                @endif
                            </div>

                            {{-- <div class="">
                                <label for="dokumentasi" class="col-form-label">Dokumentasi: </label>
                                <input type="file" name="dokumentasi" id="dokumentasi"
                                    class="form-control{{ $errors->has('dokumentasi') ? ' is-invalid' : '' }}"
                                    placeholder="Unggah file dalam format jpg/png/jpeg (maks. 2MB)">
                                @if ($errors->has('dokumentasi'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('dokumentasi') }}
                                    </span>
                                @endif
                            </div> --}}

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
                                <label for="edit-patrol-id" class="col-form-label">Tanggal Patroli:</label>
                                <select name="edit_patrol_id" id="edit-patrol-id"
                                    class="form-control{{ $errors->has('edit_patrol_id') ? ' is-invalid' : '' }}"
                                    style="height: 50px; pointer-events: none; background-color: #e9ecef;">
                                    <option value="">- Select Patrol -</option>
                                    @foreach ($apar as $r)
                                        <option value="{{ $r->patrol_id }}"
                                            {{ old('edit_patrol_id') == $r->patrol_id ? 'selected' : '' }}>
                                            {{ $r->tanggal }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('edit_patrol_id'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('edit_patrol_id') }}
                                    </span>
                                @endif
                            </div>

                            <div class="">
                                <label for="edit-temuan" class="col-form-label">Temuan: </label>
                                <input type="file" name="edit_temuan" id="edit_temuan"
                                    class="form-control{{ $errors->has('edit_temuan') ? ' is-invalid' : '' }}"
                                    placeholder="Unggah file dalam format jpg/png/jpeg (maks. 2MB)">
                                <img id="temuan-icon" src="" alt="Current Icon"
                                    style="max-width: 100px; margin-top: 10px; display: none;">
                                @if ($errors->has('edit_temuan'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('edit_temuan') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Description Feedback -->
                            <div class="">
                                <label for="edit-keterangan" class="col-form-label">Keterangan Temuan:
                                </label>
                                <textarea name="edit_keterangan" id="edit-keterangan"
                                    class="form-control{{ $errors->has('edit_keterangan') ? ' is-invalid' : '' }}"
                                    placeholder="Description Feedback">{{ old('edit_keterangan') }}</textarea>
                                @if ($errors->has('edit_keterangan'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('edit_keterangan') }}
                                    </span>
                                @endif
                            </div>

                            <!-- Description Feedback -->
                            <div class="">
                                <label for="edit-perbaikan" class="col-form-label">Perbaikan Temuan:
                                </label>
                                <textarea name="edit_perbaikan" id="edit-perbaikan"
                                    class="form-control{{ $errors->has('edit_perbaikan') ? ' is-invalid' : '' }}"
                                    placeholder="Description Feedback">{{ old('edit_perbaikan') }}</textarea>
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

        <div class="modal fade" id="showPatrolModal" tabindex="-1" aria-labelledby="showPatrolModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header text-white">
                        <h5 class="modal-title" id="showPatrolModalLabel"><i class="fas fa-info-circle me-2"></i>Detail
                            Perbaikan</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-3 text-center mb-3">
                                <img id="showTemuan" src="" alt="Dokumentasi" class="img-thumbnail"
                                    style="max-width: 100%; max-height: 300px">
                            </div>
                            <div class="col-md-6">
                                <ul class="list-group">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>Keterangan:</strong>
                                        <span id="showKeterangan" class="text-muted"></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>Perbaikan:</strong>
                                        <span id="showPerbaikan" class="text-muted"></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <strong>Target:</strong>
                                        <span id="showTarget" class="text-muted"></span>
                                    </li>
                                </ul>
                            </div>
                            <div class="col-md-3 text-center mb-3">
                                <img id="showDokumentasi" src="" alt="Dokumentasi" class="img-thumbnail"
                                    style="max-width: 100%; max-height: 300px">
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
        <div class="modal fade" id="dokumModal" tabindex="-1" aria-labelledby="dokumModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="dokumModalTitle">Edit Perbaikan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form role="form" method="POST" action="" id="dokumModalForm"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Description Feedback -->
                            <div class="">
                                <label for="dokum-perbaikan" class="col-form-label">Perbaikan Temuan:
                                </label>
                                <textarea name="dokum_perbaikan" id="dokum-perbaikan"
                                    class="form-control{{ $errors->has('dokum_perbaikan') ? ' is-invalid' : '' }}"
                                    placeholder="Description Feedback" readonly>{{ old('dokum_perbaikan') }}</textarea>
                                @if ($errors->has('dokum_perbaikan'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('dokum_perbaikan') }}
                                    </span>
                                @endif
                            </div>

                            <div class="">
                                <label for="dokum-dokumentasi" class="col-form-label">Dokumentasi Perbaikan: </label>
                                <input type="file" name="dokum_dokumentasi" id="dokum_dokumentasi"
                                    class="form-control{{ $errors->has('dokum_dokumentasi') ? ' is-invalid' : '' }}"
                                    placeholder="Unggah file dalam format jpg/png/jpeg (maks. 2MB)">
                                <img id="current-icon" src="" alt="Current Icon"
                                    style="max-width: 100px; margin-top: 10px; display: none;">
                                @if ($errors->has('dokum_dokumentasi'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $errors->first('dokum_dokumentasi') }}
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

        <!-- Modal Konfirmasi Approve -->
        <div class="modal fade" id="approveadmin" tabindex="-1" aria-labelledby="approveadminLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approveadminLabel">Konfirmasi Approve</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menyetujui tindakan ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form id="approveadminForm" method="POST" action="">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-primary">Ya, Setujui</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Approve -->
        <div class="modal fade" id="approvemanager" tabindex="-1" aria-labelledby="approvemanagerLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="approvemanagerLabel">Konfirmasi Approve</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menyetujui tindakan ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form id="approvemanagerForm" method="POST" action="">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-primary">Ya, Setujui</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Approve -->
        <div class="modal fade" id="setujuadmin" tabindex="-1" aria-labelledby="setujuadminLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="setujuadminLabel">Konfirmasi Approve</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menyetujui tindakan ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form id="setujuadminForm" method="POST" action="">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-primary">Ya, Setujui</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Konfirmasi Approve -->
        <div class="modal fade" id="setujumanager" tabindex="-1" aria-labelledby="setujumanagerLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="setujumanagerLabel">Konfirmasi Approve</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menyetujui tindakan ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form id="setujumanagerForm" method="POST" action="">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-primary">Ya, Setujui</button>
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
                {{ $errors->has('patrol_id') || $errors->has('keterangan') || $errors->has('perbaikan') || $errors->has('target') ? 'true' : 'false' }}
            ) {
                var addperbaikanModal = new bootstrap.Modal(document.getElementById('addperbaikan'));
                var url = localStorage.getItem('Url');
                var image = localStorage.getItem('Image');
                addperbaikanModal.show();
                $('#addperbaikanForm').attr('action', url);
                $('#temuan-gambar').attr('src', image);
                console.log(@json($errors->all()));
            }

            if (
                {{ $errors->has('edit_patrol_id') ||
                $errors->has('edit_dokumentasi') ||
                $errors->has('edit_perbaikan') ||
                $errors->has('edit_temuan') ||
                $errors->has('edit_keterangan') ||
                $errors->has('edit_target')
                    ? 'true'
                    : 'false' }}
            ) {
                // Ambil modal dan tampilkan
                var editperbaikanModal = new bootstrap.Modal(document.getElementById('editperbaikan'));
                var url = localStorage.getItem('Url') || '';
                var image1 = localStorage.getItem('Image1') || '';
                var image2 = localStorage.getItem('Image2') || '';

                // Set action URL dan gambar di form
                document.getElementById('editperbaikanForm').setAttribute('action', url);
                if (image1) {
                    document.getElementById('temuan-icon').style.display = 'block';
                    document.getElementById('temuan-icon').setAttribute('src', image1);
                }
                if (image2) {
                    document.getElementById('current-icon').style.display = 'block';
                    document.getElementById('current-icon').setAttribute('src', image2);
                }

                // Debugging: log errors dan data gambar
                console.log(@json($errors->all()));
                console.log(image1, image2);

                // Tampilkan modal
                editperbaikanModal.show();
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
                    var patrolTemuan = this.getAttribute('data-temuan');
                    var patrolKeterangan = this.getAttribute('data-keterangan');
                    var patrolPerbaikan = this.getAttribute('data-perbaikan');
                    var patrolDokum = this.getAttribute('data-dokumentasi');
                    var patrolTarget = this.getAttribute('data-tanggal');
                    var actionUrl = this.getAttribute('data-url');
                    localStorage.setItem('Url', actionUrl);
                    localStorage.setItem('Image1', patrolTemuan);
                    localStorage.setItem('Image2', patrolDokum);

                    console.log(patrolId, patrolTemuan, patrolKeterangan, patrolDokum,
                        patrolPerbaikan, patrolTarget, actionUrl, localStorage);

                    $('#edit-patrol-id').val(patrolId);
                    $('#edit-keterangan').val(patrolKeterangan);
                    $('#edit-perbaikan').val(patrolPerbaikan);
                    $('#edit-target').val(patrolTarget);

                    // Update gambar ikon jika ada
                    var iconImage = $('#current-icon');
                    if (patrolDokum) {
                        iconImage.attr('src', patrolDokum); // Menggabungkan string dengan benar
                        iconImage.show(); // Menampilkan gambar jika ada
                    } else {
                        iconImage.hide(); // Menyembunyikan gambar jika tidak ada
                    }
                    var icontemuan = $('#temuan-icon');
                    if (patrolDokum) {
                        icontemuan.attr('src', patrolDokum); // Menggabungkan string dengan benar
                        icontemuan.show(); // Menampilkan gambar jika ada
                    } else {
                        icontemuan.hide(); // Menyembunyikan gambar jika tidak ada
                    }

                    // Atur action form untuk update
                    $('#editperbaikanForm').attr('action', actionUrl);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            var editButtons = document.querySelectorAll('.perbaikan-button');

            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var patrolId = this.getAttribute('data-id');
                    var patrolKeterangan = this.getAttribute('data-keterangan');
                    var patrolTemuan = this.getAttribute('data-temuan');
                    var patrolPatrol = this.getAttribute('data-patrol-id');
                    var actionUrl = this.getAttribute('data-url');
                    localStorage.setItem('Url', actionUrl);
                    localStorage.setItem('Image', patrolTemuan);

                    console.log(patrolId, patrolTemuan, patrolKeterangan, patrolPatrol, actionUrl,
                        localStorage);

                    // Set nilai untuk elemen dalam modal
                    $('#keterangan').val(patrolKeterangan || ''); // Default kosong jika tidak ada
                    $('#patrol_id').val(patrolPatrol || ''); // Default kosong jika tidak ada

                    // Update gambar ikon
                    var iconImage = $('#temuan-gambar');
                    if (patrolTemuan) {
                        iconImage.attr('src', patrolTemuan); // Menampilkan gambar jika ada
                        iconImage.show();
                    } else {
                        iconImage.hide(); // Menyembunyikan gambar jika tidak ada
                    }

                    // Set action form
                    $('#addperbaikanForm').attr('action', actionUrl);
                });
            });
        });


        document.addEventListener('DOMContentLoaded', function() {
            var editButtons = document.querySelectorAll('.dokum-button');

            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    var patrolId = this.getAttribute('data-id');
                    var patrolPerbaikan = this.getAttribute('data-perbaikan');
                    var actionUrl = this.getAttribute('data-url');
                    localStorage.setItem('Url', actionUrl);

                    console.log(patrolId, patrolPerbaikan, actionUrl);

                    // $('#edit-patrol-id').val(patrolId);
                    $('#dokum-perbaikan').val(patrolPerbaikan);

                    // Atur action form untuk update
                    $('#dokumModalForm').attr('action', actionUrl);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk semua tombol dengan class "admin-button"
            document.querySelectorAll('.admin-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    // Ambil URL dari atribut data-url
                    const url = this.getAttribute('data-url');

                    // Isi action pada form di dalam modal
                    const form = document.getElementById('approveadminForm');
                    console.log(form, url);
                    form.setAttribute('action', url);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk semua tombol dengan class "admin-button"
            document.querySelectorAll('.manager-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    // Ambil URL dari atribut data-url
                    const url = this.getAttribute('data-url');

                    // Isi action pada form di dalam modal
                    const form = document.getElementById('approvemanagerForm');
                    console.log(form, url);
                    form.setAttribute('action', url);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk semua tombol dengan class "admin-button"
            document.querySelectorAll('.admin-setuju').forEach(function(button) {
                button.addEventListener('click', function() {
                    // Ambil URL dari atribut data-url
                    const url = this.getAttribute('data-url');

                    // Isi action pada form di dalam modal
                    const form = document.getElementById('setujuadminForm');
                    console.log(form, url);
                    form.setAttribute('action', url);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk semua tombol dengan class "admin-button"
            document.querySelectorAll('.manager-setuju').forEach(function(button) {
                button.addEventListener('click', function() {
                    // Ambil URL dari atribut data-url
                    const url = this.getAttribute('data-url');

                    // Isi action pada form di dalam modal
                    const form = document.getElementById('setujumanagerForm');
                    console.log(form, url);
                    form.setAttribute('action', url);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.show-button').forEach(function(button) {
                button.addEventListener('click', function() {
                    // Ambil data dari tombol
                    const keterangan = this.getAttribute('data-keterangan');
                    const temuan = this.getAttribute('data-temuan');
                    const dokumentasi = this.getAttribute('data-dokumentasi');
                    const perbaikan = this.getAttribute('data-perbaikan');
                    const target = this.getAttribute('data-tanggal');

                    // Isi data ke modal
                    document.getElementById('showKeterangan').innerText = keterangan;
                    document.getElementById('showPerbaikan').innerText = perbaikan;
                    document.getElementById('showTarget').innerText = target;

                    const dokumentasiImg = document.getElementById('showDokumentasi');
                    const temuanImg = document.getElementById('showTemuan');
                    if (dokumentasi) {
                        dokumentasiImg.src = dokumentasi;
                        dokumentasiImg.style.display = "block";
                    } else {
                        dokumentasiImg.style.display = "none";
                    }
                    if (temuan) {
                        temuanImg.src = temuan;
                        temuanImg.style.display = "block";
                    } else {
                        temuanImg.style.display = "none";
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
