<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [
    ['text' => 'Struktural', 'uri' => 'struktural'],
    ['text' => 'Kegiatan Unit Kerja', 'uri' => 'struktural/kegiatan/unit-kerja'],
    ['text' => 'Detail Kegiatan', 'uri' => 'struktural/kegiatan/detail/' . $target_kegiatan['id_kegiatan']],
    ['text' => 'Ubah Alokasi & Nilai']
]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<a href="<?= base_url('struktural/kegiatan/detail/' . $target_kegiatan['id_kegiatan']) ?>" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali ke detail kegiatan</a>
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
            <th class="text-nowrap">Nama Pegawai</th>
            <td><?= esc($target_kegiatan['nama_pegawai']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Realisasi Pegawai</th>
            <td><?= $sum_realisasi_target . ' ' . esc($target_kegiatan['satuan_target']) . ' (' . $sum_realisasi_target_terverifikasi . ' Telah Dikonfirmasi)' ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">&Sigma; Bobot Pegawai</th>
            <td><?= $target_kegiatan['target_pegawai'] ?> &times; <?= $target_kegiatan['bobot'] ?> = <?= $target_kegiatan['bobot'] * $target_kegiatan['jumlah_target'] ?></td>
        </tr>
    </tbody>
</table>

<div class="row">
    <div class="col-lg-8">
        <?= view_cell('\\App\\Libraries\\Cells\\Form::nilaiAlokasi', [
            'uri' => 'struktural/alokasi/do-ubah/' . $target_kegiatan['id_kegiatan'] . '/' . $target_kegiatan['id_pegawai'],
            'input' => $input
        ]) ?>
    </div>
</div>
<?= $this->endSection() ?>