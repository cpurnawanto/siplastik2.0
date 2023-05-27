<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Personal', 'uri' => 'personal'], ['text' => 'Kegiatan Saya', 'uri' => 'personal/kegiatan/bulanan'], ['text' => 'Detail Kegiatan']]) ?>
<?= $this->endSection() ?>


<?= $this->section('title_widget') ?>
<a class="btn btn-secondary" href="<?= base_url('personal/kegiatan/bulanan') ?>"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Kegiatan</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<table class="table table-sm table-borderless table-responsive">
    <tbody>
        <tr>
            <th class="text-nowrap">Nama Kegiatan</th>
            <td><?= esc($target_kegiatan_pegawai['nama_kegiatan']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Angka Kredit Kegiatan</th>
            <?php if ($target_kegiatan_pegawai['id_fungsional_pegawai']) : ?>
                <td>
                    <span id="outer-kredit-kegiatan-hint" class="mb-1">(Mohon tunggu)</span>
                    <br>
                    <button id="lihat-detail-kredit-kegiatan" role="button" class="btn btn-sm btn-primary mt-2" style="display: none;"><i class="fas fa-eye"></i> Lihat Detail</button>
                    <button id="ubah-kredit-kegiatan" role="button" class="btn btn-sm btn-primary mt-2"><i class="fas fa-edit"></i> Edit Angka Kredit Terhubung</button>
                    <br>
                    <br>
                </td>
            <?php else : ?>
                <td>
                    <i>Tidak memiliki jabatan fungsional</i>
                </td>
            <?php endif; ?>
        </tr>
        <tr>
            <th class="text-nowrap">Jadwal Kegiatan</th>
            <td><?= date("d M Y", strtotime($target_kegiatan_pegawai['tgl_mulai'])) . ' - ' . date("d M Y", strtotime($target_kegiatan_pegawai['tgl_selesai'])) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Unit Kerja</th>
            <td><?= $unit_kerja_kegiatan['unit_kerja'] ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Keterangan Kegiatan</th>
            <td><?= esc(!empty($target_kegiatan_pegawai['keterangan']) ? $target_kegiatan_pegawai['keterangan'] : '-') ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Target Personal</th>
            <td><?= $target_kegiatan_pegawai['target_pegawai'] . ' ' . esc($target_kegiatan_pegawai['satuan_target']) ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Total Realisasi Personal</th>
            <td><?= $sum_realisasi_target . ' ' . esc($target_kegiatan_pegawai['satuan_target']) . ' (' . $sum_realisasi_target_terverifikasi . ' Telah Dikonfirmasi)' ?></td>
        </tr>
        <tr>
            <th class="text-nowrap">Keterangan Personal</th>
            <td>
                <span> <?= esc($target_kegiatan_pegawai['keterangan_target'] ?? '-') ?></span>
                <br>
                <!-- <button role="button" class="btn btn-primary btn-sm mt-2" id="show-edit-keterangan-button"><i class="fas fa-edit"></i> Edit Keterangan</button> -->
            </td>
        </tr>
    </tbody>
</table>
<hr>
<div class="clearfix">
    <div class="float-left">
        <h2 class="h4">Daftar Realisasi Kegiatan</h2>
    </div>

    <div class="float-right">
        <a href="<?= base_url('personal/realisasi/tambah/' . $target_kegiatan_pegawai['id_kegiatan']) ?>" class="btn btn-primary"><i class="fas fa-plus mr-1"></i> Tambah Realisasi</a>
    </div>
</div>
<small><i>*) Klik baris untuk mengedit realisasi</i> <br><br></small>
<table class="table table-hover" id="tabel-realisasi">
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Realisasi</th>
            <th>Keterangan</th>
            <th>Verifikasi</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($realisasi_target as $realisasi) : ?>
            <tr class="<?= $realisasi['waktu_acc'] ? 'table-success' : 'table-warning clickable' ?>" data-id-realisasi="<?= $realisasi['waktu_acc'] ? '' : $realisasi['id'] ?>">
                <td><?= $realisasi['tanggal_realisasi'] ?></td>
                <td><?= $realisasi['jumlah_realisasi'] . ' ' . esc($target_kegiatan_pegawai['satuan_target']) ?> </td>
                <td><?= esc($realisasi['keterangan']) ?></td>
                <td><?= $realisasi['waktu_acc'] ? '<i class="fas fa-check"></i> Diverifikasi pada ' . date("d M Y - H:i:s", strtotime($realisasi['waktu_acc'])) : '<i class="fas fa-clock"></i> Belum diverifikasi' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<div class=" modal fade" id="modal-edit-kredit-kegiatan" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Ubah Angka Kredit Terhubung</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-button">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="form-edit-kredit-kegiatan" method="POST" action="<?= base_url('personal/kegiatan/do-ubah-kredit-kegiatan-terhubung/' . $target_kegiatan_pegawai['id_kegiatan']) ?>">
                <div class="modal-body">
                    <?= csrf_field() ?>
                    <input type="hidden" name="kredit_kegiatan[id_kegiatan]" value="<?= $target_kegiatan_pegawai['id_kegiatan'] ?>">
                    <div class="form-group" id="control-angka-kredit">
                        <p class="my-2">Hubungkan Angka Kredit : <br><span id="kredit-kegiatan-hint">(Tidak terhubung)</span></p>
                        <input type="hidden" name="kredit_kegiatan[id_kredit_kegiatan]" id="id-kredit-kegiatan">
                        <button type="button" class="btn btn-primary btn-sm mt-2" id="show-kredit-kegiatan-modal" data-toggle="modal" data-target="#modal-prt1-kredit-kegiatan">
                            Pilih Angka Kredit
                        </button>
                        <button type="button" class="btn btn-danger btn-sm mt-2" id="reset-kredit-kegiatan">
                            Reset Pilihan
                        </button>
                        <br>
                        <br>
                        <small>(Fungsional saat alokasi kegiatan : <?= $fungsional_target ?> )</small>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancel-button">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submit-button">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->include('partials/modal_lihat_kredit_kegiatan') ?>
<?= $this->include('partials/modal_ubah_kredit_kegiatan') ?>
<?= $this->endSection() ?>


<?= $this->section('script') ?>
<script>
    $(
        function() {
            $('#tabel-realisasi').DataTable();
            $('#tabel-realisasi tbody').on('click', 'tr', function() {
                var data = $(this).data();
                if (data.idRealisasi) {
                    window.location.href = '<?= base_url('personal/realisasi/ubah') ?>/' + data.idRealisasi;
                }
            });

            var ajaxGetKreditKegiatan = function(id, callback) {
                $.ajax('<?= base_url('api/ajax/get-kredit-kegiatan/') ?>/' + id, {
                    success: function(data) {
                        callback(data)
                    }
                })
            }

            var currIdKegiatan = <?= $target_kegiatan_pegawai['id_kredit_kegiatan'] ?? 0 ?>;
            var isAhli = <?= esc(in_array($target_kegiatan_pegawai['id_fungsional_pegawai'], [21, 22, 23, 24, 41, 42, 43, 44]) ? 'true' : 'false') ?>;

            if (currIdKegiatan != false) {
                $('#lihat-detail-kredit-kegiatan').show();
                ajaxGetKreditKegiatan(currIdKegiatan, function(data) {
                    var d = data.data.kredit_kegiatan;
                    var descSelectedKegiatanId = `${d.uraian_singkat} ${d.kegiatan ? ('- '+d.kegiatan) : ''} (${d.kode}-${d.nama_tingkat} ${d.kode_perka})`;
                    $("#outer-kredit-kegiatan-hint").text(descSelectedKegiatanId);
                })
            } else {
                $("#outer-kredit-kegiatan-hint").text('(Tidak terhubung)');
            }

            $('#ubah-kredit-kegiatan').click(function() {
                $("#kredit-kegiatan-hint").text("(tidak terhubung)");
                $('#id-kredit-kegiatan').val(currIdKegiatan);

                if ($('#id-kredit-kegiatan').val() != false) {
                    ajaxGetKreditKegiatan($('#id-kredit-kegiatan').val(), function(data) {
                        var d = data.data.kredit_kegiatan;
                        var descSelectedKegiatanId = `${d.uraian_singkat} ${d.kegiatan ? ('- '+d.kegiatan) : ''} (${d.kode}-${d.nama_tingkat} ${d.kode_perka})`;
                        $("#kredit-kegiatan-hint").text(descSelectedKegiatanId);
                    });
                }

                $('#show-kredit-kegiatan-modal').click(function(e) {
                    prt1.isKegiatanAhli = isAhli;
                    if ($('#id-kredit-kegiatan').val() != false) {
                        prt1.getKreditKegiatan($('#id-kredit-kegiatan').val());
                        prt1.showModalDetail();
                    } else {
                        prt1.showModalPencarian();
                    }
                })

                $('#reset-kredit-kegiatan').click(function(e) {
                    $('#id-kredit-kegiatan').val(0);
                    $("#kredit-kegiatan-hint").text("(tidak terhubung)");
                })

                $('#prt1-header-control-pilih').click(function(e) {
                    var descSelectedKegiatanId = `${prt1.selectedKegiatanDetail.uraian_singkat} ${prt1.selectedKegiatanDetail.kegiatan ? ('- '+prt1.selectedKegiatanDetail.kegiatan) : ''} (${prt1.selectedKegiatanDetail.kode}-${prt1.selectedKegiatanDetail.nama_tingkat} ${prt1.selectedKegiatanDetail.kode_perka})`;
                    $('#kredit-kegiatan-hint').text(descSelectedKegiatanId);
                    $('#id-kredit-kegiatan').val(prt1.selectedKegiatanDetail.id);
                    $('#modal-prt1-kredit-kegiatan').modal('hide');
                });

                $('#modal-edit-kredit-kegiatan').modal('show');
            });

            $('#lihat-detail-kredit-kegiatan').click(function() {
                $('#modal-prt2-kredit-kegiatan').modal('show');
                prt2.getKreditKegiatan(currIdKegiatan);
                prt2.showModalDetail();
            });
        }
    )
</script>
<?= $this->endSection() ?>

<?= $this->section('head') ?>
<style>
    #tabel-realisasi tr.clickable {
        cursor: pointer;
    }
</style>
<?= $this->endSection() ?>