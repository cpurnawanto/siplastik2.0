<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => '[Admin] Daftar Pengguna', 'uri' => 'admin/pegawai'], ['text' => 'Tambah Pengguna']]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <?= view_cell('\\App\\Libraries\\Cells\\Form::pegawai', ['uri' => 'admin/pegawai/do-tambah']) ?>
    </div>
</div>
<?= $this->endSection() ?>