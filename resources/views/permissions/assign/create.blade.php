@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Role and Permission Management</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('assign.index') }}"
                            class="{{ request()->routeIs('assign.index') ? 'active' : '' }}">Role and Permission
                            Management</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('assign.create') }}"
                            class="{{ request()->routeIs('assign.create') ? 'active' : '' }}">Assign Role</a>
                    </li>
                </ol>
            </div>
            <div class="card-body">

                <form method="POST" action="{{ route('assign.store') }}">
                    @csrf
                    <!-- Name -->
                    <div class="form-group">
                        <label for="role">Roles:</label>
                        <select name="role" class="form-control select2">
                            <option value="">Choose Role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="permissions[]">Permission</label>
                        <select name="permissions[]" class="form-control choices" multiple>
                            @foreach ($permissions as $permission)
                                <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                            @endforeach
                        </select>
                        @error('guard_name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <!-- Submit Button -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a class="btn btn-secondary" href="{{ route('assign.index') }}">Cancel</a>
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

@push('styles')
    <!-- Add this in the head section for Choices.js -->
    <link href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <!-- Add this script before your closing body tag -->
    <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Choices.js on the select element
            new Choices('.choices', {
                removeItemButton: true,
                placeholderValue: 'Select Permissions'
            });
        });
    </script>
@endpush
