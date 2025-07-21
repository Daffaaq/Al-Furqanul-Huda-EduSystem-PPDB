@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Menu Group Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('menu-group.index') }}"
                            class="{{ request()->routeIs('menu-group.index') ? 'active' : '' }}">Menu Group Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('menu-group.create') }}"
                            class="{{ request()->routeIs('menu-group.create') ? 'active' : '' }}">Create Menu Group</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">
                <!-- Menu Group Create Form -->
                <form method="POST" action="{{ route('menu-group.update', $menuGroup->id) }}">
                    @csrf
                    @method('PUT')
                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">Menu Group Name:</label>
                        <input type="text" name="name" id="name" placeholder="Menu Group Name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $menuGroup->name) }}">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a class="btn btn-secondary" href="{{ route('menu-group.index') }}">Cancel</a>
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
