<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="container">
    <?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Administrator', 'uri' => 'admin'], ['text' => 'Kegiatan SKP']]) ?>
</div>
<?= $this->endSection() ?>