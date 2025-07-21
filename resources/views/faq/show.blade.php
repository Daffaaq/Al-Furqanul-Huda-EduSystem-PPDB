@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Detail FAQ</h6>
            </div>
            <div class="card-body">
                <div class="row mt-4">
                    <!-- Pertanyaan -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Pertanyaan</h6>
                            </div>
                            <div class="card-body">
                                <p>{{ $faq->question }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <!-- Jawaban -->
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Jawaban</h6>
                            </div>
                            <div class="card-body">
                                <p>{!! $faq->answer !!}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <!-- Status Aktif -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Status</h6>
                            </div>
                            <div class="card-body">
                                @if ($faq->is_active)
                                    <span class="badge bg-success text-white">Aktif</span>
                                @else
                                    <span class="badge bg-secondary text-white">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Urutan FAQ -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-primary">Urutan</h6>
                            </div>
                            <div class="card-body">
                                <p>{{ $faq->order }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($faq->periode_id)
                    <div class="row mt-4">
                        <!-- Periode Terkait -->
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-primary">Periode Terkait</h6>
                                </div>
                                <div class="card-body">
                                    <p>Nama Periode: {{ $faq->periode->nama_periode }}</p>
                                    {{-- Bisa diganti dengan nama periode jika ada relasi --}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="text-center mt-4">
                    <a href="{{ route('faq.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
