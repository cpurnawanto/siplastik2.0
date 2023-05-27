<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Unduh Peta Ter-import']]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Form::importStatusTable') ?>
<td>
    <a class="btn btn-primary" href="<?= base_url(); ?>">Kembali Ke Beranda</a>
    <a class="btn btn-secondary" href="<?= base_url("berkas/import"); ?>">Impor File Lainnya</a>
    <a class="btn btn-success" href="<?= base_url("file.zip"); ?>">Download</a>
</td>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(function() {
        $('table').DataTable();
    })
</script>
<?= $this->endSection() ?>