<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Struktural', 'uri' => 'struktural'], ['text' => 'Indeks Template Kegiatan']]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<a class="btn btn-primary" href="<?= base_url('struktural/template/kegiatan/tambah') ?>"><i class="fas fa-plus"></i> Tambah Template Baru</a>
<?= $this->endSection() ?>

<?= $this->section('table') ?>
<small><i>*) Klik untuk melihat template kegiatan</i> <br><br></small>
<div class="overflow-auto">
    <table class="table table-hover" id="list-template">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Kegiatan</th>
                <th>Unit Kerja</th>
                <th>Satuan Target</th>
                <th class="text-right">Bobot per Target</th>
                <th class="text-right">Pakai Template</th>
            </tr>
        </thead>
        <tbody>
            <?php $c = 0;
            foreach ($template_kegiatan as $t) : ?>
                <tr data-id="<?= $t['id'] ?>">
                    <td><?= ++$c ?></td>
                    <td><?= esc($t['nama_kegiatan']) ?></td>
                    <td><?= esc($t['unit_kerja']) ?></td>
                    <td><?= esc($t['satuan_target']) ?></td>
                    <td class="text-right"><?= esc($t['bobot']) ?></td>
                    <td class="text-right"><a class=" btn btn-primary" href="<?= base_url('struktural/template/kegiatan/pakai/' . $t['id']) ?>">Gunakan Template</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    $(function() {
        $('#list-template').DataTable();
        $('#list-template tbody').on('click', 'tr', function() {
            var data = $(this).data();
            if (data.id) {
                window.location.href = '<?= base_url('struktural/template/kegiatan/ubah') ?>/' + data.id;
            }
        });
    })
</script>
<?= $this->endSection() ?>