<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [
    ['text' => 'Struktural', 'uri' => 'struktural'],
    ['text' => 'Kegiatan Unit Kerja', 'uri' => 'struktural/kegiatan/unit-kerja'],
    ['text' => 'Detail Kegiatan', 'uri' => 'struktural/kegiatan/detail/' . $detail_realisasi['id_kegiatan']],
    ['text' => 'Ubah Realisasi Kegiatan']
]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<a href="<?= base_url('struktural/kegiatan/detail/' . $detail_realisasi['id_kegiatan']) ?>" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali ke detail kegiatan</a>
<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#hapus-modal"><i class="fas fa-trash mr-1"></i> Hapus Realisasi</button>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<table class="table table-sm table-borderless table-responsive">
    <tbody>
        <tr>
            <th class="text-nowrap">Nama Kegiatan</th>
            <td><?= esc($detail_realisasi['nama_kegiatan']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Jadwal Kegiatan</th>
            <td><?= date("d M Y", strtotime($detail_realisasi['tgl_mulai'])) . ' - ' . date("d M Y", strtotime($detail_realisasi['tgl_selesai'])) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Nama Pegawai</th>
            <td><?= esc($detail_realisasi['nama_pegawai']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Target Personal</th>
            <td><?= $detail_realisasi['target_pegawai'] . ' ' . esc($detail_realisasi['satuan_target']) ?></td>
        </tr>
    </tbody>
</table>

<div class="row">
    <div class="col-lg-8">
        <?= view_cell('\\App\\Libraries\\Cells\\Form::realisasiKegiatan', ['uri' => 'struktural/realisasi/do-ubah/' . $detail_realisasi['id_realisasi'], 'input' => $input, 'struktural' => true]) ?>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="hapus-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Hapus Realisasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?= base_url('struktural/realisasi/do-hapus/' . $detail_realisasi['id_realisasi']) ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="realisasi[id_kegiatan]" value="<?= $detail_realisasi['id_kegiatan'] ?>">
                <input type="hidden" name="realisasi[id_realisasi]" value="<?= $detail_realisasi['id_realisasi'] ?>">
                <div class="modal-body">
                    <p>Apakah anda ingin menghapus realisasi ini?</p>

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