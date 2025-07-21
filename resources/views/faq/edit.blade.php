@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">Edit FAQ</h6>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">
                        <a href="{{ route('faq.index') }}"
                            class="{{ request()->routeIs('faq.index') ? 'active' : '' }}">FAQ</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('faq.edit', $faq->id) }}"
                            class="{{ request()->routeIs('faq.edit') ? 'active' : '' }}">
                            Edit Faq
                        </a>
                    </li>
                </ol>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('faq.update', $faq->id) }}">
                    @csrf
                    @method('PUT')

                    <!-- Pertanyaan -->
                    <div class="form-group">
                        <label for="question">Pertanyaan:</label>
                        <textarea name="question" id="question" rows="2" maxlength="1000"
                            class="form-control @error('question') is-invalid @enderror">{{ old('question', $faq->question) }}</textarea>
                        @error('question')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Jawaban -->
                    <div class="form-group">
                        <label for="answer">Jawaban:</label>
                        <textarea name="answer" id="answer" class="form-control @error('answer') is-invalid @enderror">{{ old('answer', $faq->answer) }}</textarea>
                        @error('answer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="is_active">Status:</label>
                        <select name="is_active" id="is_active"
                            class="form-control @error('is_active') is-invalid @enderror">
                            <option value="">-- Pilih Status --</option>
                            <option value="1" {{ old('is_active', $faq->is_active) == '1' ? 'selected' : '' }}>Aktif
                            </option>
                            <option value="0" {{ old('is_active', $faq->is_active) == '0' ? 'selected' : '' }}>Tidak
                                Aktif</option>
                        </select>
                        @error('is_active')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Periode -->
                    <div class="form-group">
                        <label for="periode_id">Periode:</label>
                        <select name="periode_id" id="periode_id"
                            class="form-control @error('periode_id') is-invalid @enderror">
                            <option value="">-- Pilih Periode --</option>
                            @foreach ($periodelist as $periode)
                                <option value="{{ $periode->id }}"
                                    {{ old('periode_id', $faq->periode_id) == $periode->id ? 'selected' : '' }}>
                                    {{ $periode->nama_periode }} {{ $periode->status_periode ? '(Aktif)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('periode_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <div class="mt-4 text-right">
                        <a href="{{ route('faq.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
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

@push('scripts')
    <!-- jQuery & Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#answer').summernote({
                height: 200,
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['font', ['strikethrough', 'superscript', 'subscript']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['height', ['height']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onImageUpload: function(files) {
                        alert('Upload gambar tidak diizinkan.');
                    }
                }
            });
        });
    </script>
@endpush
