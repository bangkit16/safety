<div class="sidebar">
    <div class="sidebar-wrapper">
        <div class="logo">
            <a href="#" class="simple-text logo-mini">{{ 'PT' }}</a>
            <a href="#" class="simple-text logo-normal">{{ 'PERUSAHAAN' }}</a>
        </div>
        <ul class="nav">
            <li @if ($pageSlug == 'dashboard') class="active " @endif>
                <a href="{{ route('home') }}">
                    <i class="fas fa-home"></i> <!-- Ikon Dashboard -->
                    <p>{{ 'Dashboard' }}</p>
                </a>
            </li>
            <li>
                <a data-toggle="collapse" href="#example"
                    aria-expanded="{{ in_array($pageSlug, ['profile', 'users', 'roles', 'divisis']) ? 'true' : 'false' }}"
                    class="{{ in_array($pageSlug, ['profile', 'users', 'roles', 'divisis']) ? '' : 'collapsed' }}">
                    <i class="fas fa-user-cog"></i> <!-- Ikon User Configurasi -->
                    <span class="nav-link-text">{{ 'User Configurasi' }}</span>
                    <b class="caret mt-1"></b>
                </a>
                <div class="collapse {{ in_array($pageSlug, ['profile', 'users', 'roles', 'divisis']) ? 'show' : '' }}"
                    id="example">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'profile') class="active " @endif>
                            <a href="{{ route('profile.edit') }}">
                                <i class="fas fa-id-badge"></i> <!-- Ikon User Profile -->
                                <p>{{ 'User Profile' }}</p>
                            </a>
                        </li>
                        @if (auth()->user()->role_id == 1)
                            <li @if ($pageSlug == 'users') class="active " @endif>
                                <a href="{{ route('user.index') }}">
                                    <i class="fas fa-user-friends"></i> <!-- Ikon User Management -->
                                    <p>{{ 'User Management' }}</p>
                                </a>
                            </li>
                            <li @if ($pageSlug == 'roles') class="active " @endif>
                                <a href="{{ route('role.index') }}">
                                    <i class="fas fa-key"></i> <!-- Ikon Role Management -->
                                    <p>{{ 'Role Management' }}</p>
                                </a>
                            </li>
                            <li @if ($pageSlug == 'divisis') class="active " @endif>
                                <a href="{{ route('divisi.index') }}">
                                    <i class="fas fa-sitemap"></i> <!-- Ikon Divisi Management -->
                                    <p>{{ 'Divisi Management' }}</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
            <li>
                <a data-toggle="collapse" href="#patrol"
                    aria-expanded="{{ in_array($pageSlug, ['input-patrol', 'input-perbaikan', 'laporan']) ? 'true' : 'false' }}"
                    class="{{ in_array($pageSlug, ['input-patrol', 'input-perbaikan', 'laporan']) ? '' : 'collapsed' }}">
                    <i class="fas fa-shield-alt"></i> <!-- Ikon Patrol Keselamatan -->
                    <span class="nav-link-text">{{ 'Patrol Keselamatan' }}</span>
                    <b class="caret mt-1"></b>
                </a>
                <div class="collapse {{ in_array($pageSlug, ['input-patrol', 'input-perbaikan', 'laporan']) ? 'show' : '' }}"
                    id="patrol">
                    <ul class="nav pl-4">
                        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 3)
                            <li @if ($pageSlug == 'input-patrol') class="active " @endif>
                                <a href="{{ route('patrol.index') }}">
                                    <i class="fas fa-pencil-alt"></i> <!-- Ikon Input Patrol -->
                                    <p>{{ 'Input Patrol' }}</p>
                                </a>
                            </li>
                            <li @if ($pageSlug == 'input-perbaikan') class="active " @endif>
                                <a href="{{ route('perbaikan.index') }}">
                                    <i class="fas fa-tools"></i> <!-- Ikon Input Perbaikan -->
                                    <p>{{ 'Input Perbaikan' }}</p>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                            <li @if ($pageSlug == 'laporan') class="active " @endif>
                                <a href="{{ route('laporan.index') }}">
                                    <i class="fas fa-file-alt"></i> <!-- Ikon Laporan Patrol -->
                                    <p>{{ 'Laporan Patrol' }}</p>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</div>
