<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Struktural', 'uri' => 'struktural'], ['text' => 'Verifikasi Realisasi Kegiatan', 'uri' => 'struktural/realisasi/verifikasi'], ['text' => 'Detail Realisasi']]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<a href="<?= base_url('struktural/realisasi/verifikasi') ?>" class="btn btn-secondary px-3"><i class="fas fa-arrow-left mr-1"></i>Kembali ke Daftar Verifikasi</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<h2 class="h5 mt-0 pt-0">Verifikasi Realisasi</h2>
<p>Apakah anda ingin memverikasi realisasi kegiatan ini?</p>
<table class="table table-sm table-borderless table-responsive">
    <tbody>
        <tr>
            <th class="text-nowrap">Nama Kegiatan</th>
            <td><?= esc($detail_realisasi['nama_kegiatan']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Nama Pegawai</th>
            <td><?= esc($detail_realisasi['nama_pegawai']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Realisasi</th>
            <td><?= $detail_realisasi['jumlah_realisasi'] . ' ' . esc($detail_realisasi['satuan_target']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Waktu Realisasi</th>
            <td><?= date("d M Y", strtotime($detail_realisasi['tanggal_realisasi'])) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Keterangan Realisasi</th>
            <td><?= esc(!empty($detail_realisasi['keterangan_realisasi']) ? $detail_realisasi['keterangan_realisasi'] : '-') ?> </td>
        </tr>
    </tbody>
</table>

<form action="<?= base_url('struktural/realisasi/do-verifikasi/' . $detail_realisasi['id_realisasi']) ?>" method="POST">
    <?= csrf_field() ?>
    <div class="form-group">
        <input type="hidden" name="id" value="<?= $detail_realisasi['id_realisasi'] ?>">
        <a href="<?= base_url('struktural/realisasi/verifikasi') ?>" class="btn btn-secondary px-3"><i class="fas fa-arrow-left mr-1"></i>Kembali</a>
        <a href="<?= base_url('struktural/realisasi/ubah/' . $detail_realisasi['id_realisasi']) ?>" class="btn btn-primary px-3"><i class="fas fa-edit mr-1"></i>Ubah Realisasi</a>
        <button type="submit" class="btn btn-primary px-3"> <i class="fas fa-check mr-1"></i> Konfirmasi Realisasi</button>

    </div>
</form>
<hr>
<h2 class="h5">Detail Kegiatan</h2>
<table class="table table-sm table-borderless table-responsive">
    <tbody>
        <tr>
            <th class="text-nowrap">Nama Kegiatan</th>
            <td><?= esc($detail_realisasi['nama_kegiatan']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Jadwal Kegiatan</th>
            <td><?= date("d M Y", strtotime($detail_realisasi['tgl_mulai'])) . ' - ' . date("d M Y", strtotime($detail_realisasi['tgl_selesai'])) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Unit Kerja Kegiatan</th>
            <td><?= $detail_realisasi['unit_kerja_kegiatan'] ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Target Kegiatan</th>
            <td><?= $detail_realisasi['target_kegiatan'] . ' ' . esc($detail_realisasi['satuan_target']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Keterangan Kegiatan</th>
            <td><?= esc(!empty($detail_realisasi['keterangan']) ? $detail_realisasi['keterangan'] : '-') ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Target Personal <br>(<?= esc($detail_realisasi['nama_pegawai']) ?>)</th>
            <td><?= $detail_realisasi['target_pegawai'] . ' ' . esc($detail_realisasi['satuan_target']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Keterangan Personal <br>(<?= esc($detail_realisasi['nama_pegawai']) ?>)</th>
            <td><?= esc($detail_realisasi['keterangan_target'] ?? '-') ?> </td>
        </tr>
    </tbody>
</table>

<a href="<?= base_url('struktural/kegiatan/detail/' . $detail_realisasi['id_kegiatan']) ?>" class="btn btn-secondary px-3"><i class="fas fa-search mr-1"></i>Detail Kegiatan</a>
<?= $this->endSection() ?>