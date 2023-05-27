<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [
    ['text' => 'Struktural', 'uri' => 'struktural'],
    ['text' => 'Indeks Template Kegiatan', 'uri' => 'struktural/template/kegiatan'],
    ['text' => 'Ubah Template Kegiatan']
]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<a href="<?= base_url('struktural/template/kegiatan') ?>" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i> Kembali</a>
<a class=" btn btn-primary" href="<?= base_url('struktural/template/kegiatan/pakai/' . $template_kegiatan['id']) ?>"> <i class="fas fa-edit"></i> Gunakan</a>
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hapus-modal"><i class="fas fa-trash mr-1"></i> Hapus Template</button>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-lg-8">
        <?= view_cell('\\App\\Libraries\\Cells\\Form::kegiatan', [
            'uri' => 'struktural/template/kegiatan/do-ubah/' . $template_kegiatan['id'],
            'input' => $template_kegiatan,
            'template' => true
        ]); ?>
    </div>
</div>
<!-- Modal -->
<div class="modal fade" id="hapus-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Template Kegiatan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('struktural/template/kegiatan/do-hapus/' . $template_kegiatan['id']) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="kegiatan[id_template]" value="<?= $template_kegiatan['id'] ?>">
                <div class="modal-body">
                    <p>Apakah anda ingin menghapus template kegiatan ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>