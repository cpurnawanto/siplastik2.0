<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [
    ['text' => 'Struktural', 'uri' => 'struktural'],
    ['text' => 'Kegiatan Unit Kerja', 'uri' => 'struktural/kegiatan/unit-kerja'],
    ['text' => 'Detail Kegiatan', 'uri' => 'struktural/kegiatan/detail/' . $kegiatan['id']],
    ['text' => 'Tambah Realisasi Kegiatan']
]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<a href="<?= base_url('struktural/kegiatan/detail/' . $kegiatan['id']) ?>" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali ke detail kegiatan</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<table class="table table-sm table-borderless table-responsive">
    <tbody>
        <tr>
            <th class="text-nowrap">Nama Kegiatan</th>
            <td><?= esc($kegiatan['nama_kegiatan']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Jadwal Kegiatan</th>
            <td><?= date("d M Y", strtotime($kegiatan['tgl_mulai'])) . ' - ' . date("d M Y", strtotime($kegiatan['tgl_selesai'])) ?></td>
        </tr>
    </tbody>
</table>

<div class="row">
    <div class="col-lg-8">
        <?= view_cell('\\App\\Libraries\\Cells\\Form::realisasiKegiatan', [
            'uri' => 'struktural/realisasi/do-tambah/' . $kegiatan['id'],
            'struktural' => true,
            'target_pegawai' => $target_pegawai
        ]) ?>
    </div>
</div>
<?= $this->endSection() ?>