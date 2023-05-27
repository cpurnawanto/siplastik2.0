<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Administrator', 'uri' => 'admin'], ['text' => 'Indeks Kredit Kegiatan', 'uri' => 'admin/kredit_kegiatan'], ['text' => 'Import Data Kegiatan']]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<a class="btn btn-primary" href="<?= base_url('assets/template_kredit_kegiatan.xls') ?>" role="button"><i class="fas fa-download mr-1"></i> Download template excel</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<p>Silahkan download template excel, lalu isikan data kegiatan dan angka kreditnya sesuai petunjuk di dalamnya. <br> Setelah selesai, silahkan upload kembali file templatenya ke form di bawah ini</p>
<form action="do-import" method="POST" action="<?= base_url('admin/kredit-kegiatan/do-import') ?>" enctype="multipart/form-data">
    <?= csrf_field() ?>
    <div class="row">
        <div class="col-lg-4">
            <div class="form-group">
                <label for="excel_import">File data kredit kegiatan</label>
                <input type="file" class="form-control-file" name="excel_import" id="excel_import" class="form-control">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary px-4"> <i class="fas fa-upload mr-1"></i>Import</button>
</form>

<br>
<?= view_cell('\\App\\Libraries\\Cells\\Form::importStatusTable') ?>

<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(function() {
        $('table').DataTable();
    })
</script>
<?= $this->endSection() ?>