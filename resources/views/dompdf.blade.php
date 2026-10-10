<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dokumen Tender - {{ $tender->nama_tender }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* 1. KUNCI UTAMA: Pemicu Pindah Halaman https://ui.shadcn.com/blocks*/
        .page-break {
            page-break-after: always;
        }

        /* ================= DESAIN HALAMAN 1 (COVER) ================= */
        .cover-container {
            padding: 100px 40px;
            text-align: center;
            background-color: #f8fafc;
        }
        .cover-title {
            font-size: 26px;
            font-weight: bold;
            color: #1e3a8a;
            margin-bottom: 10px;
        }
        .cover-subtitle {
            font-size: 16px;
            color: #475569;
        }

        /* ================= DESAIN HALAMAN 2 (SURAT PENAWARAN) ================= */
        .surat-container {
            padding: 30px;
        }
        .header-surat {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            border: 1px solid #777;
            padding: 8px;
        }

        /* ================= DESAIN HALAMAN 3 (LAMPIRAN / PROYEK) ================= */
        .lampiran-container {
            padding: 30px;
        }
        .card-proyek {
            border: 1px solid #cbd5e1;
            padding: 12px;
            margin-bottom: 10px;
            background-color: #f1f5f9;
        }
    </style>
</head>
<body>

    <!-- ================= HALAMAN 1: COVER DEPAN ================= -->
    <div class="cover-container">
        <h1 class="cover-title">DOKUMEN PENAWARAN TENDER</h1>
        <p class="cover-subtitle">{{ $tender->nama_tender }}</p>
        <p class="cover-subtitle">Instansi: {{ $tender->instansi }}</p>

        <div style="margin-top: 150px;">
            <p><strong>Diajukan Oleh:</strong></p>
            <h3>{{ $tender->perusahaan->nama_perusahaan ?? '-' }}</h3>
            <p>Tahun Anggaran {{ $tender->tahun_anggaran }}</p>
        </div>
    </div>

    <!-- PAKSA DOMPDF PINDAH KE HALAMAN 2 -->
    <div class="page-break"></div>

    <!-- ================= HALAMAN 2: DETAIL TENDER & PERSONEL ================= -->
    <div class="surat-container">
        <div class="header-surat">
            <h2>RINCIAN DOKUMEN PENAWARAN</h2>
            <p>Nomor: TND/{{ $tender->tahun_anggaran }}/{{ $tender->id }}</p>
        </div>

        <h3>Daftar Personel Ahli</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Nama Personel</th>
                    <th>Posisi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tender->personels as $index => $personel)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $personel->nama_personel }}</td>
                    <td>{{ $personel->jabatan ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- PAKSA DOMPDF PINDAH KE HALAMAN 3 -->
    <div class="page-break"></div>

    <!-- ================= HALAMAN 3: LAMPIRAN PENGALAMAN PROYEK ================= -->
    <div class="lampiran-container">
        <h2 style="color: #1e3a8a; border-bottom: 1px solid #1e3a8a; padding-bottom: 5px;">
            LAMPIRAN: PENGALAMAN PROYEK
        </h2>

        @forelse($tender->pengalamanProyeks as $proyek)
            <div class="card-proyek">
                <strong>{{ $proyek->nama_proyek }}</strong><br>
                <small>Nilai Penawaran: Rp {{ number_format($proyek->nilai_kontrak ?? 0, 0, ',', '.') }}</small>
            </div>
        @empty
            <p>Tidak ada lampiran pengalaman proyek.</p>
        @endforelse
    </div>

</body>
</html>