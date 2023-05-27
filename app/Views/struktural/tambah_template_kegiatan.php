<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [
    ['text' => 'Struktural', 'uri' => 'struktural'],
    ['text' => 'Indeks Template Kegiatan', 'uri' => 'struktural/template/kegiatan'],
    ['text' => 'Tambah Template Kegiatan']
]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<a href="<?= base_url('struktural/template/kegiatan') ?>" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i> Kembali ke Daftar</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <?= view_cell('\\App\\Libraries\\Cells\\Form::kegiatan', ['uri' => 'struktural/template/kegiatan/do-tambah', 'template' => true]); ?>
    </div>
</div>
<?= $this->endSection() ?>