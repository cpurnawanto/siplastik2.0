<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => '[Admin] Daftar Pengguna', 'uri' => 'admin/pegawai'], ['text' => 'Ubah Data Pegawai']]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<a class="btn btn-danger px-3" href="<?= base_url('admin/pegawai/hapus/' . $input['id']) ?>"><i class="fas fa-trash mr-1"></i>Hapus</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <?= view_cell('\\App\\Libraries\\Cells\\Form::pegawai', ['uri' => 'admin/pegawai/do-ubah/' . $input['id'], 'input' => $input]) ?>
    </div>
</div>
<?= $this->endSection() ?>