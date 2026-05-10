@extends('layouts.app')
@section('title', 'Laporan')
@section('page-title', 'Laporan')
@section('page-sub', 'Lihat laporan produksi')

@section('content')

<div class="card">
    {{-- Filter --}}
    <form method="GET" action="{{ route('laporan.index') }}" id="form-laporan">
        <div class="filter-bar" style="margin-bottom:20px">
            <select name="customer" class="form-select" id="filter-customer-laporan">
                <option value="">Semua Customer</option>
                @foreach($customers as $c)
                    <option value="{{ $c }}" {{ $selectedCust == $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>

            <div style="display:flex;align-items:center;gap:6px">
                <label style="font-size:12px;color:var(--text-muted);white-space:nowrap">Tanggal Mulai</label>
                <input type="date" name="date_from" class="form-select" id="filter-date-from"
                       value="{{ $dateFrom }}">
            </div>

            <div style="display:flex;align-items:center;gap:6px">
                <label style="font-size:12px;color:var(--text-muted);white-space:nowrap">Tanggal Selesai</label>
                <input type="date" name="date_to" class="form-select" id="filter-date-to"
                       value="{{ $dateTo }}">
            </div>

            <button type="submit" name="tampilkan" value="1" class="btn btn-primary" id="btn-tampilkan-laporan">
                Tampilkan
            </button>

            <a href="{{ route('laporan.export') }}?{{ http_build_query(request()->all()) }}"
               class="btn btn-danger btn-sm" id="btn-export-laporan" style="margin-left:auto">
                <i class="ph ph-file-pdf"></i> Export PDF
            </a>
        </div>
    </form>

    {{-- Table --}}
    <div class="table-wrapper">
        <table class="table" id="table-laporan">
            <thead>
                <tr>
                    <th rowspan="2" style="vertical-align:middle">No</th>
                    <th rowspan="2" style="vertical-align:middle">Nama Customer</th>
                    <th rowspan="2" style="vertical-align:middle">Total Produksi</th>
                    <th colspan="2" style="text-align:center;border-left:1px solid var(--border)">Total QC</th>
                    <th colspan="2" style="text-align:center;border-left:1px solid var(--border)">Total Packing</th>
                    <th rowspan="2" style="vertical-align:middle">Keterangan</th>
                </tr>
                <tr>
                    <th style="border-left:1px solid var(--border)">FG (OK)</th>
                    <th>NG</th>
                    <th style="border-left:1px solid var(--border)">Finish Good</th>
                    <th>Not Good</th>
                </tr>
            </thead>
            <tbody>
                @forelse($productions as $i => $prod)
                @php
                    $fgOk  = optional($prod->qc)->jumlah_fg ?? 0;
                    $ng    = optional($prod->qc)->jumlah_ng ?? 0;
                    $packFg = optional(optional($prod->qc)->packing)->jumlah_fg ?? 0;
                    $packNg = optional(optional($prod->qc)->packing)->jumlah_ng ?? 0;
                    $ket   = optional(optional($prod->qc)->packing)->keterangan ?? optional($prod->qc)->keterangan ?? '-';
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ optional($prod->material)->nama_customer ?? '-' }}</td>
                    <td>{{ number_format($prod->jumlah_produksi) }}</td>
                    <td style="color:var(--fg-ok);font-weight:600;border-left:1px solid var(--border)">{{ number_format($fgOk) }}</td>
                    <td style="color:var(--ng);font-weight:600">{{ number_format($ng) }}</td>
                    <td style="color:var(--fg-ok);font-weight:600;border-left:1px solid var(--border)">{{ number_format($packFg) }}</td>
                    <td style="color:var(--ng);font-weight:600">{{ number_format($packNg) }}</td>
                    <td style="font-size:12px;color:var(--text-mid)">{{ $ket }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align:center;color:var(--text-muted);padding:40px">
                        @if(request()->has('tampilkan'))
                            <i class="ph ph-chart-bar" style="font-size:32px;display:block;margin-bottom:8px"></i>
                            Tidak ada data untuk periode yang dipilih
                        @else
                            <i class="ph ph-chart-bar" style="font-size:32px;display:block;margin-bottom:8px"></i>
                            Pilih rentang tanggal lalu klik <strong>Tampilkan</strong>
                        @endif
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($productions->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="2" style="font-weight:700;text-align:right">TOTAL</td>
                    <td style="font-weight:700">{{ number_format($totalProduksi) }}</td>
                    <td style="font-weight:700;color:var(--fg-ok);border-left:1px solid var(--border)">{{ number_format($totalFgOk) }}</td>
                    <td style="font-weight:700;color:var(--ng)">{{ number_format($totalNg) }}</td>
                    <td style="font-weight:700;color:var(--fg-ok);border-left:1px solid var(--border)">{{ number_format($totalPackFg) }}</td>
                    <td style="font-weight:700;color:var(--ng)">{{ number_format($totalPackNg) }}</td>
                    <td style="font-weight:700">—</td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@endsection
