@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Permission Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('permission.index') }}"
                            class="{{ request()->routeIs('permission.index') ? 'active' : '' }}">permission Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('permission.create') }}"
                            class="{{ request()->routeIs('permission.create') ? 'active' : '' }}">Create permission</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">

                <form method="POST" action="{{ route('permission.update', $permission->id) }}">
                    @csrf
                    @method('PUT')
                    <!-- Name -->
                    <div class="form-group">
                        <label for="name">Permission:</label>
                        <input type="text" name="name" id="name" placeholder="Role Name" value="{{ old('name', $permission->name) }}"
                            class="form-control @error('name') is-invalid @enderror">
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="guard_name">Guard Name:</label>
                        <input type="text" name="guard_name" id="guard_name" placeholder="Web" value="{{ old('guard_name', $permission->guard_name) }}"
                            class="form-control @error('guard_name') is-invalid @enderror">
                        @error('guard_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a class="btn btn-secondary" href="{{ route('permission.index') }}">Cancel</a>
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
    </style>
@endpush
