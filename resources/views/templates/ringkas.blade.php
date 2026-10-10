<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Ringkasan Penawaran - {{ $tender->nama_tender }}</title>
    <style>
        @page { margin: 1.4cm 1.5cm; }
        body { font-family: Helvetica,Arial, sans-serif; font-size: 8.5pt; color: #111827; line-height: 1.35; }

        .judul { font-size: 14pt; font-weight: bold; margin: 0; }
        .sub { color: #4b5563; margin: 2px 0 8px; }
        .garis { border-top: 2px solid #111827; margin: 6px 0 10px; }

        h4 { font-size: 9.5pt; margin: 12px 0 4px; padding: 3px 6px; background: #e5e7eb; text-transform: uppercase; }

        table { width: 100%; border-collapse: collapse; }
        table.kv td { padding: 2px 4px; vertical-align: top; }
        table.kv td.k { width: 22%; color: #4b5563; }
        table.kv td.s { width: 2%; }

        table.rapat th, table.rapat td { border: 1px solid #9ca3af; padding: 3px 5px; vertical-align: top; }
        table.rapat th { background: #f3f4f6; text-align: left; }
        .tengah { text-align: center; }
        .kanan { text-align: right; }

        .catatan { margin-top: 10px; color: #6b7280; font-size: 7.5pt; }
    </style>
</head>
<body>
@php
    $tanggal = $tender->tanggal_tender
        ? \Carbon\Carbon::parse($tender->tanggal_tender)->locale('id')->translatedFormat('d F Y')
        : '-';
    $rupiah = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
@endphp

    <p class="judul">RINGKASAN PENAWARAN TENDER</p>
    <p class="sub">No. TND/{{ $tender->tahun_anggaran }}/{{ str_pad($tender->id, 3, '0', STR_PAD_LEFT) }} &middot; Dicetak {{ now()->locale('id')->translatedFormat('d F Y') }}</p>
    <div class="garis"></div>

    {{-- ===== DATA TENDER (2 kolom) ===== --}}
    <table>
        <tr>
            <td width="50%" style="vertical-align: top;">
                <table class="kv">
                    <tr><td class="k">Paket</td><td class="s">:</td><td><b>{{ $tender->nama_tender }}</b></td></tr>
                    <tr><td class="k">Instansi</td><td class="s">:</td><td>{{ $tender->instansi }}</td></tr>
                    <tr><td class="k">Penawar</td><td class="s">:</td><td>{{ $tender->perusahaan->nama_perusahaan ?? '-' }}</td></tr>
                    <tr><td class="k">Tanggal</td><td class="s">:</td><td>{{ $tanggal }}</td></tr>
                </table>
            </td>
            <td width="50%" style="vertical-align: top;">
                <table class="kv">
                    <tr><td class="k">Lokasi</td><td class="s">:</td><td>{{ $tender->lokasi_pekerjaan }}</td></tr>
                    <tr><td class="k">Kota</td><td class="s">:</td><td>{{ $tender->kota_pengadaan }}</td></tr>
                    <tr><td class="k">Durasi</td><td class="s">:</td><td>{{ $tender->durasi }} hari kalender</td></tr>
                    <tr><td class="k">Nilai</td><td class="s">:</td><td><b>{{ $rupiah($tender->nilai_penawaran) }}</b></td></tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- ===== PERSONEL ===== --}}
    <h4>Personel Kunci ({{ $tender->personels->count() }})</h4>
    <table class="rapat">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th width="9%">Pendidikan</th>
                <th>Sertifikasi</th>
                <th width="9%" class="tengah">Pengalaman</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tender->personels as $i => $p)
                <tr>
                    <td class="tengah">{{ $i + 1 }}</td>
                    <td>{{ $p->nama_personel }}</td>
                    <td>{{ $p->jabatan }}</td>
                    <td class="tengah">{{ $p->pendidikan }}</td>
                    <td>{{ $p->sertifikasi }} ({{ $p->nomor_sertifikat }})</td>
                    <td class="tengah">{{ $p->pengalaman_kerja }} th</td>
                </tr>
            @empty
                <tr><td colspan="6" class="tengah">Tidak ada data personel.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- ===== PENGALAMAN PROYEK ===== --}}
    <h4>Pengalaman Proyek ({{ $tender->pengalamanProyeks->count() }})</h4>
    <table class="rapat">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th>Nama Proyek</th>
                <th>Pemberi Kerja</th>
                <th width="8%" class="tengah">Tahun</th>
                <th width="20%" class="kanan">Nilai Kontrak</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tender->pengalamanProyeks as $i => $pr)
                <tr>
                    <td class="tengah">{{ $i + 1 }}</td>
                    <td>{{ $pr->nama_proyek }}</td>
                    <td>{{ $pr->pemberi_kerja }}</td>
                    <td class="tengah">{{ $pr->tahun_pelaksanaan }}</td>
                    <td class="kanan">{{ $rupiah($pr->nilai_kontrak) }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="tengah">Tidak ada data pengalaman proyek.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- ===== DOKUMEN PENDUKUNG (daftar singkat) ===== --}}
    <h4>Dokumen Pendukung ({{ $tender->dokumenPendukungs->count() }})</h4>
    @if($tender->dokumenPendukungs->count())
        <p style="margin: 2px 0;">
            @foreach($tender->dokumenPendukungs as $d)
                {{ $d->nama_dokumen }} ({{ $d->jenis_dokumen }}){{ $loop->last ? '' : ' · ' }}
            @endforeach
        </p>
    @else
        <p style="margin: 2px 0; color:#6b7280;">Tidak ada dokumen pendukung.</p>
    @endif

    <p class="catatan">Dokumen ini dibuat otomatis oleh E-Tender App.</p>

</body>
</html>