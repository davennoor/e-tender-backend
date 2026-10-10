<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dokumen Penawaran - {{ $tender->nama_tender }}</title>
    <style>
        @page { margin: 2.2cm 2cm; }
        body { font-family: "Times New Roman", serif; font-size: 12pt; color: #000; line-height: 1.5; }
        .page-break { page-break-after: always; }
        .center { text-align: center; }
        .upper { text-transform: uppercase; }

        /* ---------- COVER ---------- */
        .cover { border: 3px double #000; padding: 70px 30px; margin-top: 30px; text-align: center; }
        .cover .judul { font-size: 22pt; font-weight: bold; letter-spacing: 1px; margin-bottom: 30px; }
        .cover .paket { font-size: 15pt; font-weight: bold; margin-bottom: 8px; }
        .cover .diajukan { margin-top: 140px; }
        .cover .perusahaan { font-size: 16pt; font-weight: bold; margin: 6px 0; }

        /* ---------- SURAT ---------- */
        .kop { text-align: center; border-bottom: 3px solid #000; padding-bottom: 8px; margin-bottom: 18px; }
        .kop h2 { margin: 0; font-size: 15pt; text-transform: uppercase; }
        table.info { width: 100%; border-collapse: collapse; margin: 8px 0 14px; }
        table.info td { padding: 3px 4px; vertical-align: top; font-size: 12pt; }
        table.info td.label { width: 32%; }
        table.info td.sep { width: 3%; }
        p { margin: 8px 0; text-align: justify; }
        table.ttd { width: 100%; margin-top: 30px; }
        table.ttd td { vertical-align: top; }

        /* ---------- TABEL DATA ---------- */
        h3.bagian { font-size: 13pt; text-transform: uppercase; border-bottom: 2px solid #000; padding-bottom: 4px; margin: 0 0 10px; }
        table.grid { width: 100%; border-collapse: collapse; }
        table.grid th, table.grid td { border: 1px solid #000; padding: 5px 7px; font-size: 10.5pt; vertical-align: top; }
        table.grid th { background: #e5e5e5; text-align: center; }
        .kanan { text-align: right; }
        .tengah { text-align: center; }
    </style>
</head>
<body>
@php
    $tanggal = $tender->tanggal_tender
        ? \Carbon\Carbon::parse($tender->tanggal_tender)->locale('id')->translatedFormat('d F Y')
        : '-';
    $rupiah = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    $nomor = 'TND/' . $tender->tahun_anggaran . '/' . str_pad($tender->id, 3, '0', STR_PAD_LEFT);
@endphp

    {{-- ================= HALAMAN 1: COVER ================= --}}
    <div class="cover">
        <div class="judul upper">Dokumen Penawaran</div>
        <div class="paket">{{ $tender->nama_tender }}</div>
        <div>{{ $tender->instansi }}</div>
        <div>{{ $tender->kota_pengadaan }}</div>

        <div class="diajukan">
            <div>Diajukan oleh:</div>
            <div class="perusahaan upper">{{ $tender->perusahaan->nama_perusahaan ?? '-' }}</div>
            <div>Tahun Anggaran {{ $tender->tahun_anggaran }}</div>
        </div>
    </div>

    <div class="page-break"></div>

    {{-- ================= HALAMAN 2: SURAT PENAWARAN ================= --}}
    <div class="kop">
        <h2>{{ $tender->perusahaan->nama_perusahaan ?? '-' }}</h2>
    </div>

    <table class="info">
        <tr><td class="label">Nomor</td><td class="sep">:</td><td>{{ $nomor }}</td></tr>
        <tr><td class="label">Lampiran</td><td class="sep">:</td><td>1 (satu) berkas</td></tr>
        <tr><td class="label">Perihal</td><td class="sep">:</td><td><b>Penawaran {{ $tender->nama_tender }}</b></td></tr>
    </table>

    <p>Kepada Yth.<br><b>{{ $tender->instansi }}</b><br>di {{ $tender->kota_pengadaan }}</p>

    <p>Dengan hormat,</p>
    <p>
        Yang bertanda tangan di bawah ini mewakili <b>{{ $tender->perusahaan->nama_perusahaan ?? '-' }}</b>
        dengan ini mengajukan penawaran untuk pekerjaan <b>{{ $tender->nama_tender }}</b>
        dengan rincian sebagai berikut:
    </p>

    <table class="info">
        <tr><td class="label">Lokasi Pekerjaan</td><td class="sep">:</td><td>{{ $tender->lokasi_pekerjaan }}</td></tr>
        <tr><td class="label">Tahun Anggaran</td><td class="sep">:</td><td>{{ $tender->tahun_anggaran }}</td></tr>
        <tr><td class="label">Jangka Waktu</td><td class="sep">:</td><td>{{ $tender->durasi }} hari kalender</td></tr>
        <tr><td class="label">Nilai Penawaran</td><td class="sep">:</td><td><b>{{ $rupiah($tender->nilai_penawaran) }}</b></td></tr>
    </table>

    <p>Demikian surat penawaran ini kami sampaikan. Atas perhatian dan kerja samanya, kami ucapkan terima kasih.</p>

    <table class="ttd">
        <tr>
            <td width="55%"></td>
            <td class="center">
                {{ $tender->kota_pengadaan }}, {{ $tanggal }}<br>
                Hormat kami,<br>
                <b>{{ $tender->perusahaan->nama_perusahaan ?? '-' }}</b>
                <br><br><br><br>
                ( ................................... )<br>
                Direktur
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

    {{-- ================= HALAMAN 3: PERSONEL ================= --}}
    <h3 class="bagian">Daftar Personel Kunci</h3>
    <table class="grid">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th width="10%">Pendidikan</th>
                <th>Sertifikasi / No.</th>
                <th width="12%">Pengalaman</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tender->personels as $i => $p)
                <tr>
                    <td class="tengah">{{ $i + 1 }}</td>
                    <td>{{ $p->nama_personel }}</td>
                    <td>{{ $p->jabatan }}</td>
                    <td class="tengah">{{ $p->pendidikan }}</td>
                    <td>{{ $p->sertifikasi }}<br><small>No: {{ $p->nomor_sertifikat }}</small></td>
                    <td class="tengah">{{ $p->pengalaman_kerja }} tahun</td>
                </tr>
            @empty
                <tr><td colspan="6" class="tengah">Tidak ada data personel.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="page-break"></div>

    {{-- ================= HALAMAN 4: PENGALAMAN PROYEK ================= --}}
    <h3 class="bagian">Daftar Pengalaman Proyek</h3>
    <table class="grid">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th>Nama Proyek</th>
                <th>Pemberi Kerja</th>
                <th>Lokasi</th>
                <th width="9%">Tahun</th>
                <th width="20%">Nilai Kontrak</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tender->pengalamanProyeks as $i => $pr)
                <tr>
                    <td class="tengah">{{ $i + 1 }}</td>
                    <td>{{ $pr->nama_proyek }}</td>
                    <td>{{ $pr->pemberi_kerja }}</td>
                    <td>{{ $pr->lokasi_proyek }}</td>
                    <td class="tengah">{{ $pr->tahun_pelaksanaan }}</td>
                    <td class="kanan">{{ $rupiah($pr->nilai_kontrak) }}</td>
                </tr>
            @empty
                <tr><td colspan="6" class="tengah">Tidak ada data pengalaman proyek.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{-- ================= DOKUMEN PENDUKUNG (hanya tampil jika ada) ================= --}}
    @if($tender->dokumenPendukungs->count())
        <div style="margin-top: 28px;">
            <h3 class="bagian">Dokumen Pendukung</h3>
            <table class="grid">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Dokumen</th>
                        <th>Jenis</th>
                        <th width="18%">Tanggal</th>
                        <th width="14%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tender->dokumenPendukungs as $i => $d)
                        <tr>
                            <td class="tengah">{{ $i + 1 }}</td>
                            <td>{{ $d->nama_dokumen }}</td>
                            <td>{{ $d->jenis_dokumen }}</td>
                            <td class="tengah">{{ $d->tanggal_upload }}</td>
                            <td class="tengah">{{ ucwords(str_replace('_', ' ', $d->status_dokumen)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</body>
</html>