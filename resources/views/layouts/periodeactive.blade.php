@php
    $periodeActive = DB::table('periodes')->select('nama_periode')->where('status_periode', '1')->first();
@endphp

<form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
    <div class="input-group">
        @if (isset($periodeActive))
            <button class="btn btn-light btn-sm mr-2">
                Periode : &nbsp;
                <i class="fa fa-check-square text-primary"></i>&nbsp;
                <span class="text-primary font-weight-bold">
                    <strong>{{ $periodeActive->nama_periode }}</strong>
                </span>
            </button>
        @else
            <button class="btn btn-light btn-sm mr-2">
                Periode : &nbsp;
                <i class="fa fa-times-circle text-danger"></i>&nbsp;
                <span class="text-danger font-weight-bold">
                    <strong>Periode Belum Diset</strong>
                </span>
            </button>
        @endif
    </div>
</form>
