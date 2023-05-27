<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [
    ['text' => 'Personal', 'uri' => 'personal'],
    ['text' => 'Kegiatan Saya', 'uri' => 'personal/kegiatan/bulanan'],
    ['text' => 'Detail Kegiatan', 'uri' => 'personal/kegiatan/detail/' . $detail_realisasi['id_kegiatan']],
    ['text' => 'Ubah Realisasi']
]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<a class="btn btn-secondary" href="<?= base_url('personal/kegiatan/detail/' . $detail_realisasi['id_kegiatan']) ?>"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Detail Kegiatan</a>
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
            <th class="text-nowrap">Target Personal</th>
            <td><?= $detail_realisasi['target_pegawai'] . ' ' . esc($detail_realisasi['satuan_target']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Total Realisasi Saat Ini</th>
            <td><?= $sum_realisasi_target . ' ' . esc($detail_realisasi['satuan_target']) . ' (' . $sum_realisasi_target_terverifikasi . ' Telah Dikonfirmasi)' ?></td>
        </tr>
    </tbody>
</table>

<div class="row">
    <div class="col-lg-8">
        <?= view_cell('\\App\\Libraries\\Cells\\Form::realisasiKegiatan', [
            'uri' => 'personal/realisasi/do-ubah/' . $detail_realisasi['id'],
            'input' => $input
        ]) ?>
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
            <form action="<?= base_url('personal/realisasi/do-hapus/' . $detail_realisasi['id_realisasi']) ?>" method="post">
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