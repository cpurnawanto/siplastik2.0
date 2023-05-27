<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Struktural', 'uri' => 'struktural'], ['text' => 'Kegiatan Unit Kerja', 'uri' => 'struktural/kegiatan/unit-kerja'], ['text' => 'Detail Kegiatan']]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<a href="<?= base_url('struktural/kegiatan/unit-kerja') ?>" class="btn btn-secondary"> <i class="fas fa-arrow-left"></i> Kembali</a>
<a class="btn btn-primary" href="<?= base_url('struktural/kegiatan/ubah/' . $kegiatan['id']) ?>"><i class="fas fa-edit"></i> Ubah Detail Kegiatan </a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<table class="table table-sm table-borderless table-responsive">
    <tbody>
        <tr>
            <th class="text-nowrap">Nama Kegiatan</th>
            <td><?= esc($kegiatan['nama_kegiatan']) ?></td>
        </tr>

        <tr>
            <th class="text-nowrap">Angka Kredit Disarankan (Ahli)</th>
            <?php if ($kegiatan['id_kredit_ahli']) : ?>
                <th>
                    <a class="lihat-detail" href="#" class="text-primary" data-id="<?= $kegiatan['id_kredit_ahli'] ?>">Tampilkan detail (Ahli)</a>
                </th>
            <?php else : ?>
                <td>
                    -
                </td>
            <?php endif; ?>
        </tr>

        <tr>
            <th class="text-nowrap">Angka Kredit Disarankan (Terampil)</th>
            <?php if ($kegiatan['id_kredit_terampil']) : ?>
                <th>
                    <a class="lihat-detail" href="#" class="text-primary" data-id="<?= $kegiatan['id_kredit_terampil'] ?>">Tampilkan detail (Terampil)</a>
                </th>
            <?php else : ?>
                <td>
                    -
                </td>
            <?php endif; ?>
        </tr>

        <tr>
            <th class="text-nowrap">Jadwal Kegiatan</th>
            <td><?= date("d M Y", strtotime($kegiatan['tgl_mulai'])) . ' - ' . date("d M Y", strtotime($kegiatan['tgl_selesai'])) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Unit Kerja</th>
            <td><?= $kegiatan['unit_kerja'] ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Keterangan</th>
            <td><?= esc(!empty($kegiatan['keterangan']) ? $kegiatan['keterangan'] :  '-') ?> </td>
        </tr>
        <tr>
            <th class="text-nowrap">Bobot</th>
            <td><?= $kegiatan['bobot'] ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Target (&Sigma; Bobot)</th>
            <td><?= $kegiatan['jumlah_target'] . ' ' . esc($kegiatan['satuan_target']) . ' (' . $kegiatan['jumlah_target'] * $kegiatan['bobot'] . ')' ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Dialokasikan</th>
            <td><?= $sum_alokasi_kegiatan . ' ' . esc($kegiatan['satuan_target']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Realisasi (&Sigma; Bobot)</th>
            <td><?= $sum_realisasi_kegiatan . ' ' . esc($kegiatan['satuan_target']) . ' (' . $sum_realisasi_kegiatan * $kegiatan['bobot'] . '), '  . $sum_realisasi_kegiatan_terverifikasi . ' Telah Dikonfirmasi' ?></td>
        </tr>
        <tr>
            <td><a href="#tabel-realisasi" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Lihat Daftar Realisasi</a></td>
        </tr>
    </tbody>
</table>
<hr>
<div class="clearfix">
    <div class="float-left">
        <h2 class="h4">Alokasi dan Nilai Pegawai</h2>
    </div>

    <div class="float-right">
        <a href="<?= base_url('struktural/alokasi/kegiatan/' . $kegiatan['id']) ?>" class="btn btn-secondary"><i class="fas fa-edit mr-1"></i> Ubah Alokasi</a>
    </div>
</div>
<small><i>*) Klik baris untuk mengedit alokasi target pegawai</i> <br><br></small>
<table class="table table-hover" id="tabel-target">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Pegawai</th>
            <th>Alokasi (&Sigma; Bobot)</th>
            <th>Progress</th>
            <th>Telat</th>
            <th>Nilai</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php $c = 0;
        foreach ($alokasi_kegiatan as $alokasi) : ?>
            <tr data-id-target='<?= $alokasi['id_target'] ?>' data-id-pegawai='<?= $alokasi['id_pegawai'] ?>'>
                <td><?= ++$c ?></td>
                <td><?= esc($alokasi['nama_pegawai']) ?></td>
                <td><?= esc($alokasi['target_pegawai'] . ' (' . $alokasi['target_pegawai'] * $kegiatan['bobot'] . ')') ?></td>
                <td>
                    <?php
                    $real_target = $realisasi_target[$alokasi['id_pegawai']] ?? 0;
                    $real_target_verif = $realisasi_target_terverifikasi[$alokasi['id_pegawai']] ?? 0;
                    $real_target_terlambat = $realisasi_target_terlambat[$alokasi['id_pegawai']] ?? 0;

                    $percent_real_target = $real_target / $alokasi['target_pegawai'] * 100;
                    $percent_real_target_verif = $real_target_verif / $alokasi['target_pegawai']  * 100;
                    $percent_real_target_terlambat = $real_target_terlambat / $alokasi['target_pegawai']  * 100;
                    ?>
                    <span class="d-none"><?= str_pad(number_format($percent_real_target, 2), 6, 0, STR_PAD_LEFT) . str_pad(number_format($percent_real_target_verif, 2), 6, 0, STR_PAD_LEFT) ?></span>
                    <div class="progress" data-toggle="tooltip" title="Realisasi : <?= $real_target ?> (<?= number_format($percent_real_target, 2) ?>%), Diverifikasi : <?= $real_target_verif ?> (<?= number_format($percent_real_target_verif, 2) ?>%)">
                        <div class="progress-bar bg-success" role="progressbar" style="width: <?= $percent_real_target_verif ?>%"></div>
                        <div class="progress-bar bg-warning" role="progressbar" style="width: <?= $percent_real_target -  $percent_real_target_verif ?>%"></div>
                    </div>
                </td>
                <td><span class="d-none"><?= str_pad(number_format($percent_real_target_terlambat, 2), 6, 0, STR_PAD_LEFT) ?></span>
                    <?= $real_target_terlambat ?> (<?= number_format($percent_real_target_terlambat, 1) ?>%)</td>
                <td><?= esc(!empty($alokasi['persen_kualitas']) ? $alokasi['persen_kualitas'] : '-') ?></td>
                <td><?= esc(!empty($alokasi['keterangan']) ? $alokasi['keterangan'] : '-') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<br>
<div class="clearfix mt-5">
    <div class="float-left">
        <h2 class="h4">Realisasi Pegawai</h2>
    </div>
    <div class="float-right">
        <a href="<?= base_url('struktural/realisasi/tambah/' . $kegiatan['id']) ?>" class="btn btn-secondary"><i class="fas fa-plus mr-1"></i> Tambah Realisasi</a>
    </div>
</div>
<small><i>*) Klik baris untuk melihat detail realisasi pegawai</i> <br><br></small>
<table class="table table-hover" id="tabel-realisasi">
    <thead>
        <tr>
            <th>#</th>
            <th>Tanggal Realisasi</th>
            <th>Nama Pegawai</th>
            <th>Jumlah Realisasi</th>
            <th>Diverifikasi</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        <?php $c = 0;
        foreach ($realisasi_kegiatan as $realisasi) : ?>
            <tr data-id-realisasi="<?= $realisasi['id_realisasi'] ?>">
                <td><?= ++$c ?></td>
                <td><?= esc(date('d M Y', strtotime($realisasi['tanggal_realisasi']))) ?></td>
                <td><?= esc($realisasi['nama_pegawai']) ?></td>
                <td><?= esc($realisasi['jumlah_realisasi']) ?></td>
                <td><?= $realisasi['waktu_acc'] ? '<i class="fas fa-check text-success"></i> Sudah' : '<i class="fas fa-clock text-warning"></i> Belum' ?></td>
                <td><?= esc(!empty($realisasi['keterangan_realisasi']) ? $realisasi['keterangan_realisasi'] : '-') ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="modal fade" id="modal-kredit-kegiatan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Cari kegiatan</h5>
                <div id="header-control" class="ml-auto" style="display: none;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div id="modal-detail">
                    <div class="row">
                        <div class="col-lg-8 mt-3">
                            <div class="overflow-auto">
                                <table class="table table-responsive table-striped table-sm">
                                    <thead>
                                        <tr>
                                            <th colspan="2">
                                                Detail Kegiatan
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody id="detail-kegiatan-body">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-4 mt-3">
                            <table class="table table-striped table-sm">
                                <thead>
                                    <tr class="text-nowrap">
                                        <th>
                                            Fungsional
                                        </th>
                                        <th>
                                            Angka Kredit
                                        </th>
                                    </tr>
                                </thead>
                                <tbody id="detail-fungsional-body">


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->include('partials/modal_lihat_kredit_kegiatan') ?>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>
    $(
        function() {
            $('[data-toggle="tooltip"]').tooltip()
            $('#tabel-target').DataTable();
            $('#tabel-target tbody').on('click', 'tr', function() {
                var data = $(this).data();
                if (data.idPegawai) {
                    window.location.href = '<?= base_url('struktural/alokasi/ubah/' . $kegiatan['id']) ?>/' + data.idPegawai
                }
            });

            $('#tabel-realisasi').DataTable();
            $('#tabel-realisasi tbody').on('click', 'tr', function() {
                var data = $(this).data();
                if (data.idRealisasi) {
                    window.location.href = '<?= base_url('struktural/realisasi/ubah') ?>/' + data.idRealisasi
                }
            });

            /** onclick baris untuk melihat detail */
            $('.lihat-detail').click(function() {
                var data = $(this).data();
                $('#modal-prt2-kredit-kegiatan').modal('show');
                prt2.getKreditKegiatan(data.id);
                prt2.showModalDetail();
            });
        }
    )
</script>
<?= $this->endSection() ?>

<?= $this->section('head') ?>
<style>
    #tabel-target tr,
    #tabel-realisasi tr {
        cursor: pointer;
    }
</style>
<?= $this->endSection() ?>