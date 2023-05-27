<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [
    ['text' => 'Personal', 'uri' => 'personal'],
    ['text' => 'Kegiatan Saya', 'uri' => 'personal/kegiatan/bulanan'],
    ['text' => 'Detail Kegiatan', 'uri' => 'personal/kegiatan/detail/' . $target_kegiatan['id']],
    ['text' => 'Tambah Realisasi']
]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<a class="btn btn-secondary" href="<?= base_url('personal/kegiatan/detail/' . $target_kegiatan['id']) ?>"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Detail Kegiatan</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<table class="table table-sm table-borderless table-responsive">
    <tbody>
        <tr>
            <th class="text-nowrap">Nama Kegiatan</th>
            <td><?= esc($target_kegiatan['nama_kegiatan']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Jadwal Kegiatan</th>
            <td><?= date("d M Y", strtotime($target_kegiatan['tgl_mulai'])) . ' - ' . date("d M Y", strtotime($target_kegiatan['tgl_selesai'])) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Target Personal</th>
            <td><?= $target_kegiatan['target_pegawai'] . ' ' . esc($target_kegiatan['satuan_target']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Total Realisasi Saat Ini</th>
            <td><?= $sum_realisasi_target . ' ' . esc($target_kegiatan['satuan_target']) . ' (' . $sum_realisasi_target_terverifikasi . ' Telah Dikonfirmasi)' ?></td>
        </tr>
    </tbody>
</table>

<div class="row">
    <div class="col-lg-8">
        <?= view_cell('\\App\\Libraries\\Cells\\Form::realisasiKegiatan', ['uri' => 'personal/realisasi/do-tambah/' . $target_kegiatan['id']]) ?>
    </div>
</div>
<?= $this->endSection() ?>