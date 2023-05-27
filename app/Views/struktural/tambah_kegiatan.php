<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Struktural', 'uri' => 'struktural'], ['text' => 'Indeks Kegiatan', 'uri' => 'struktural/kegiatan'], ['text' => 'Tambah Kegiatan']]) ?>
<?= $this->endSection() ?>


<?= $this->section('title_widget') ?>
<a href="<?= base_url('struktural/kegiatan/unit-kerja') ?>" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i> Daftar Kegiatan</a>
<a class=" btn btn-primary" href="<?= base_url('struktural/template/kegiatan/') ?>"> <i class="fas fa-search"></i> Cari Template</a>
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <?= view_cell('\\App\\Libraries\\Cells\\Form::kegiatan', ['uri' => 'struktural/kegiatan/do-tambah']); ?>
    </div>
</div>
<?= $this->endSection() ?>