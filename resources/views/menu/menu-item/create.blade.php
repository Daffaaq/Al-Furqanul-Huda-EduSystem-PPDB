@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Menu Item Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('menu-item.index') }}"
                            class="{{ request()->routeIs('menu-item.index') ? 'active' : '' }}">Menu Item Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('menu-item.create') }}"
                            class="{{ request()->routeIs('menu-item.create') ? 'active' : '' }}">Create Menu Item</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Menu Item Create Form -->
                <form method="POST" action="{{ route('menu-item.store') }}">
                    @csrf

                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">Menu Item Name:</label>
                        <input type="text" name="name" id="name" placeholder="Menu Item Name"
                            class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Route -->
                    <div class="form-group">
                        <label for="route">Route:</label>
                        <select name="route" class="form-control @error('route') is-invalid @enderror">
                            @foreach ($routeCollection as $route)
                                <option value="{{ $route->uri }}" {{ old('route') == $route->uri ? 'selected' : '' }}>
                                    {{ $route->uri }}</option>
                            @endforeach
                        </select>
                        @error('route')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Permission Name -->
                    <div class="form-group">
                        <label for="permission_name">Permission Name:</label>
                        <input type="text" name="permission_name" id="permission_name" placeholder="Permission Name"
                            class="form-control @error('permission_name') is-invalid @enderror"
                            value="{{ old('permission_name') }}">
                        @error('permission_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Menu Group -->
                    <div class="form-group">
                        <label for="menu_group_id">Menu Group:</label>
                        <select name="menu_group_id" id="menu_group_id"
                            class="form-control @error('menu_group_id') is-invalid @enderror">
                            @foreach ($menuGroups as $menuGroup)
                                <option value="{{ $menuGroup->id }}"
                                    {{ old('menu_group_id') == $menuGroup->id ? 'selected' : '' }}>
                                    {{ $menuGroup->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('menu_group_id')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a class="btn btn-secondary" href="{{ route('menu-item.index') }}">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 0;
        }

        .breadcrumb-item {
            font-size: 0.875rem;
        }

        .breadcrumb-item a {
            color: #464646;
            text-decoration: none;
        }

        .breadcrumb-item a:hover {
            text-decoration: underline;
        }

        .breadcrumb-item a.active {
            font-weight: bold;
            color: #007bff;
            pointer-events: none;
        }

        .position-relative {
            position: relative;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // You can add additional scripts here if needed, such as for a custom icon picker or validation enhancements.
    </script>
@endpush
