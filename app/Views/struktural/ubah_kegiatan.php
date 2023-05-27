<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [
    ['text' => 'Struktural', 'uri' => 'struktural'],
    ['text' => 'Kegiatan Unit Kerja', 'uri' => 'struktural/kegiatan/unit-kerja'],
    ['text' => 'Detail Kegiatan', 'uri' => 'struktural/kegiatan/detail/' . $kegiatan['id']],
    ['text' => 'Ubah Kegiatan']
]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<a class="btn btn-secondary" href="<?= base_url('struktural/kegiatan/detail/' . $kegiatan['id']) ?>"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
<a class="btn btn-danger" href="<?= base_url('struktural/kegiatan/detail/' . $kegiatan['id']) ?>"><i class="fas fa-trash mr-1"></i> Hapus Kegiatan</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <?= view_cell('\\App\\Libraries\\Cells\\Form::kegiatan', ['uri' => 'struktural/kegiatan/do-ubah/' . $kegiatan['id'], 'input' => $kegiatan]); ?>
    </div>
</div>
<?= $this->endSection() ?>