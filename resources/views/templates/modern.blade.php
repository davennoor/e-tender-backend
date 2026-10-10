<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dokumen Penawaran - {{ $tender->nama_tender }}</title>
    <style>
        @page { margin: 0; }
        body { font-family: Helvetica,Arial, sans-serif; font-size: 10pt; color: #1e293b; margin: 0; }
        .page-break { page-break-after: always; }

        /* Isi halaman diberi jarak sendiri karena @page margin = 0 (supaya header bisa full-width) */
        .isi { padding: 28px 40px 60px; }

        /* ---------- HEADER / HERO ---------- */
        table.hero { width: 100%; border-collapse: collapse; }
        table.hero td { background: #1e3a8a; color: #ffffff; padding: 38px 40px 30px; }
        .hero .label { font-size: 9pt; letter-spacing: 2px; color: #bfdbfe; }
        .hero .judul { font-size: 20pt; font-weight: bold; margin: 6px 0 4px; }
        .hero .sub { font-size: 11pt; color: #dbeafe; }
        table.garis { width: 100%; border-collapse: collapse; }
        table.garis td { background: #3b82f6; height: 6px; font-size: 1px; line-height: 1px; }

        /* ---------- KARTU RINGKASAN ---------- */
        table.kartu { width: 100%; border-collapse: separate; border-spacing: 8px 0; margin: 18px -8px 8px; }
        table.kartu td { background: #eff6ff; border: 1px solid #bfdbfe; padding: 12px 14px; width: 33%; }
        .kartu .k-label { font-size: 8pt; color: #64748b; text-transform: uppercase; }
        .kartu .k-nilai { font-size: 13pt; font-weight: bold; color: #1e3a8a; margin-top: 3px; }

        /* ---------- DETAIL ---------- */
        table.detail { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.detail td { padding: 6px 4px; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        table.detail td.label { width: 28%; color: #64748b; }

        h3.judul-bagian { font-size: 12pt; color: #1e3a8a; border-left: 5px solid #3b82f6; padding-left: 9px; margin: 22px 0 10px; }

        /* ---------- TABEL DATA ---------- */
        table.data { width: 100%; border-collapse: collapse; }
        table.data th { background: #1e3a8a; color: #ffffff; padding: 7px 8px; font-size: 9pt; text-align: left; }
        table.data td { padding: 7px 8px; font-size: 9pt; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        table.data tr.genap td { background: #f1f5f9; }
        .kanan { text-align: right; }
        .tengah { text-align: center; }

        .badge { background: #dbeafe; color: #1e40af; padding: 2px 6px; font-size: 8pt; }

        /* ---------- FOOTER NOMOR HALAMAN ---------- */
        .footer { position: fixed; bottom: 14px; left: 40px; right: 40px; font-size: 8pt; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 6px; }
        .footer .no:before { content: "Halaman " counter(page); }
    </style>
</head>
<body>
@php
    $tanggal = $tender->tanggal_tender
        ? \Carbon\Carbon::parse($tender->tanggal_tender)->locale('id')->translatedFormat('d F Y')
        : '-';
    $rupiah = fn($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
@endphp

    <div class="footer">
        {{ $tender->nama_tender }} &nbsp;|&nbsp; <span class="no"></span>
    </div>

    {{-- ================= HALAMAN 1: RINGKASAN ================= --}}
    <table class="hero">
        <tr>
            <td>
                <div class="label">DOKUMEN PENAWARAN TENDER</div>
                <div class="judul">{{ $tender->nama_tender }}</div>
                <div class="sub">{{ $tender->instansi }} &middot; {{ $tender->kota_pengadaan }}</div>
            </td>
        </tr>
    </table>
    <table class="garis"><tr><td>&nbsp;</td></tr></table>

    <div class="isi">
        <table class="kartu">
            <tr>
                <td>
                    <div class="k-label">Nilai Penawaran</div>
                    <div class="k-nilai">{{ $rupiah($tender->nilai_penawaran) }}</div>
                </td>
                <td>
                    <div class="k-label">Jangka Waktu</div>
                    <div class="k-nilai">{{ $tender->durasi }} Hari</div>
                </td>
                <td>
                    <div class="k-label">Tahun Anggaran</div>
                    <div class="k-nilai">{{ $tender->tahun_anggaran }}</div>
                </td>
            </tr>
        </table>

        <h3 class="judul-bagian">Informasi Tender</h3>
        <table class="detail">
            <tr><td class="label">Perusahaan Penawar</td><td><b>{{ $tender->perusahaan->nama_perusahaan ?? '-' }}</b></td></tr>
            <tr><td class="label">Instansi / Pemberi Kerja</td><td>{{ $tender->instansi }}</td></tr>
            <tr><td class="label">Lokasi Pekerjaan</td><td>{{ $tender->lokasi_pekerjaan }}</td></tr>
            <tr><td class="label">Kota Pengadaan</td><td>{{ $tender->kota_pengadaan }}</td></tr>
            <tr><td class="label">Tanggal Tender</td><td>{{ $tanggal }}</td></tr>
            <tr><td class="label">Nomor Dokumen</td><td>TND/{{ $tender->tahun_anggaran }}/{{ str_pad($tender->id, 3, '0', STR_PAD_LEFT) }}</td></tr>
        </table>
    </div>

    <div class="page-break"></div>

    {{-- ================= HALAMAN 2: PERSONEL ================= --}}
    <div class="isi">
        <h3 class="judul-bagian" style="margin-top:0;">Personel Kunci</h3>
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th width="10%">Pendidikan</th>
                    <th>Sertifikasi</th>
                    <th width="12%" class="tengah">Pengalaman</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tender->personels as $i => $p)
                    <tr class="{{ $i % 2 ? 'genap' : '' }}">
                        <td class="tengah">{{ $i + 1 }}</td>
                        <td><b>{{ $p->nama_personel }}</b></td>
                        <td>{{ $p->jabatan }}</td>
                        <td><span class="badge">{{ $p->pendidikan }}</span></td>
                        <td>{{ $p->sertifikasi }}<br><span style="color:#64748b;">No: {{ $p->nomor_sertifikat }}</span></td>
                        <td class="tengah">{{ $p->pengalaman_kerja }} th</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="tengah">Tidak ada data personel.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- ================= PENGALAMAN PROYEK ================= --}}
        <h3 class="judul-bagian">Pengalaman Proyek</h3>
        <table class="data">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Proyek</th>
                    <th>Pemberi Kerja</th>
                    <th width="9%" class="tengah">Tahun</th>
                    <th width="22%" class="kanan">Nilai Kontrak</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tender->pengalamanProyeks as $i => $pr)
                    <tr class="{{ $i % 2 ? 'genap' : '' }}">
                        <td class="tengah">{{ $i + 1 }}</td>
                        <td><b>{{ $pr->nama_proyek }}</b><br><span style="color:#64748b;">{{ $pr->lokasi_proyek }}</span></td>
                        <td>{{ $pr->pemberi_kerja }}</td>
                        <td class="tengah">{{ $pr->tahun_pelaksanaan }}</td>
                        <td class="kanan">{{ $rupiah($pr->nilai_kontrak) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="tengah">Tidak ada data pengalaman proyek.</td></tr>
                @endforelse
            </tbody>
        </table>

        {{-- ================= DOKUMEN PENDUKUNG ================= --}}
        @if($tender->dokumenPendukungs->count())
            <h3 class="judul-bagian">Dokumen Pendukung</h3>
            <table class="data">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama Dokumen</th>
                        <th>Jenis</th>
                        <th width="16%">Tanggal</th>
                        <th width="14%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tender->dokumenPendukungs as $i => $d)
                        <tr class="{{ $i % 2 ? 'genap' : '' }}">
                            <td class="tengah">{{ $i + 1 }}</td>
                            <td>{{ $d->nama_dokumen }}</td>
                            <td>{{ $d->jenis_dokumen }}</td>
                            <td>{{ $d->tanggal_upload }}</td>
                            <td>{{ ucwords(str_replace('_', ' ', $d->status_dokumen)) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</body>
</html>