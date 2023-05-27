<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [
    ['text' => 'Struktural', 'uri' => 'struktural'],
    ['text' => 'Kegiatan Unit Kerja', 'uri' => 'struktural/kegiatan/unit-kerja'],
    ['text' => 'Detail Kegiatan', 'uri' => 'struktural/kegiatan/detail/' . $kegiatan['id']],
    ['text' => 'Alokasi Target Kegiatan']
]) ?>
<?= $this->endSection() ?>

<?= $this->section('title_widget') ?>
<a href="<?= base_url('struktural/kegiatan/detail/' . $kegiatan['id']) ?>" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali ke detail kegiatan</a>
<?= $this->endSection() ?>


<?= $this->section('content') ?>
<table class="table table-responsive table-sm table-borderless">
    <tr>
        <th>Nama Kegiatan</th>
        <td><?= esc($kegiatan['nama_kegiatan']) ?></td>
    </tr>
    <tr>
        <th>Tanggal Mulai</th>
        <td><?= $kegiatan['tgl_mulai'] ?></td>
    </tr>
    <tr>
        <th>Tanggal Akhir</th>
        <td><?= $kegiatan['tgl_selesai'] ?></td>
    </tr>
    <tr>
        <th>Total Target (&commat;Bobot)</th>
        <td><?= $kegiatan['jumlah_target'] . ' ' . esc($kegiatan['satuan_target']) . ' (&commat;' . $kegiatan['bobot'] . ')' ?></td>
    </tr>
    <tr>
        <th>Teralokasi</th>
        <td><span class="total-alloc">0</span> <?= esc($kegiatan['satuan_target']) ?></td>
    </tr>
    <tr>
        <th class="text-nowrap">AK Disarankan (Ahli)</th>
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
        <th class="text-nowrap">AK Disarankan (Terampil)</th>
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
</table>
<p class="small">
    <i>*) Klik baris untuk mengubah alokasi target </i>
</p>
<table id="alloc" class="table table-hover">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Pegawai</th>
            <th>Jabatan</th>
            <th>Target (<?= $kegiatan['satuan_target'] ?>)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $c = 0;
        foreach ($data_target as $target) : ?>
            <tr data-id-pegawai="<?= $target['id_pegawai'] ?>" data-jml-target="<?= $target['jumlah_target'] ?? 0 ?>" data-nama-pegawai="<?= esc($target['nama_pegawai']) ?>" data-fungsional-pegawai="<?= esc($target['fungsional']) ?>" data-id-kredit-kegiatan="<?= esc($target['id_kredit_kegiatan']) ?>" data-is-ahli="<?= esc(in_array($target['id_fungsional'], [21, 22, 23, 24, 41, 42, 43, 44]) ? 'true' : 'false') ?>">
                <td><?= ++$c ?></td>
                <td><?= esc($target['nama_pegawai']) ?></td>
                <td><?= $target['unit_kerja'] ?></td>
                <td class="jml"><?= $target['jumlah_target'] ?? '-' ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<!-- <div class=" modal fade" id="modal-alloc" tabindex="-1" role="dialog"  data-backdrop="static" data-keyboard="false">-->
<div class=" modal fade" id="modal-alloc" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Input Alokasi Modal</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-button">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="form-alloc">
                <div class="modal-body">
                    <p>
                        <?= esc($kegiatan['nama_kegiatan']) ?> <br>Nama Pegawai: <span id="nama-pegawai"></span><br>Jabatan Fungsional: <span id="fungsional-pegawai">-</span>
                        <br>
                        <small>Teralokasi <span class="total-alloc">0</span> dari <?= $kegiatan['jumlah_target'] . ' ' . esc($kegiatan['satuan_target']) ?></small>
                    </p>
                    <input type="hidden" name="alokasi[id_kegiatan]" value="<?= $kegiatan['id'] ?>">
                    <input type="hidden" name="alokasi[id_pegawai]" id="id-pegawai">
                    <div class="form-group">
                        <label for="jumlah-target">Target (<?= $kegiatan['satuan_target'] ?>)</label>
                        <input type="number" class="form-control" name="alokasi[jumlah_target]" id="jumlah-target" placeholder="Masukkan jumlah satuan" required>
                    </div>
                    <div class="form-group mt-5" id="control-angka-kredit">
                        <p class="my-2">Hubungkan Angka Kredit : <br><span id="kredit-kegiatan-hint">(Tidak terhubung)</span></p>
                        <input type="hidden" name="alokasi[id_kredit_kegiatan]" id="id-kredit-kegiatan">
                        <button type="button" class="btn btn-primary btn-sm mt-2" id="show-kredit-kegiatan-modal" data-toggle="modal" data-target="#modal-prt1-kredit-kegiatan">
                            Pilih Angka Kredit
                        </button>
                        <button type="button" class="btn btn-danger btn-sm mt-2" id="reset-kredit-kegiatan">
                            Reset Pilihan
                        </button>

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

<!-- Modal Error-->
<div class="modal fade" tabindex="-1" role="dialog" id="error-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Error</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Ada kesalahan saat mengumpulkan data alokasi. <br>
                <span id="modal-error-desc"></span>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<?= $this->include('partials/modal_lihat_kredit_kegiatan') ?>
<?= $this->include('partials/modal_ubah_kredit_kegiatan') ?>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<script>
    var nowEdited = null;

    $(function() {
        $('#alloc').DataTable();

        var ajaxGetKreditKegiatan = function(id, callback) {
            $.ajax('<?= base_url('api/ajax/get-kredit-kegiatan/') ?>/' + id, {
                success: function(data) {
                    callback(data)
                }
            })
        }
        $('#alloc tbody').on('click', 'tr', function() {
            nowEdited = $(this);
            var data = $(this).data();
            $('#id-pegawai').val(data.idPegawai);
            $('#jumlah-target').val(data.jmlTarget);
            $('#nama-pegawai').text(data.namaPegawai);
            $("#kredit-kegiatan-hint").text("(tidak terhubung)");


            if (data.fungsionalPegawai) {
                $('#fungsional-pegawai').text(data.fungsionalPegawai);
                $('#control-angka-kredit').show();
            } else {
                $('#fungsional-pegawai').text("-");
                $('#control-angka-kredit').hide();
            }

            if (data.idKreditKegiatan) {
                $('#id-kredit-kegiatan').val(data.idKreditKegiatan);
                ajaxGetKreditKegiatan(data.idKreditKegiatan, function(data) {
                    var d = data.data.kredit_kegiatan;
                    var descSelectedKegiatanId = `${d.uraian_singkat} ${d.kegiatan ? ('- '+d.kegiatan) : ''} (${d.kode}-${d.nama_tingkat} ${d.kode_perka})`;
                    $("#kredit-kegiatan-hint").text(descSelectedKegiatanId);
                })
            } else {
                if (data.jmlTarget) {
                    $('#id-kredit-kegiatan').val(0);
                } else {
                    if (data.isAhli) {
                        $('#id-kredit-kegiatan').val(<?= $kegiatan['id_kredit_ahli'] ?? 0 ?>);
                    } else {
                        $('#id-kredit-kegiatan').val(<?= $kegiatan['id_kredit_terampil'] ?? 0 ?>);
                    }

                    ajaxGetKreditKegiatan($('#id-kredit-kegiatan').val(), function(data) {
                        var d = data.data.kredit_kegiatan;
                        var descSelectedKegiatanId = `${d.uraian_singkat} ${d.kegiatan ? ('- '+d.kegiatan) : ''} (${d.kode}-${d.nama_tingkat} ${d.kode_perka})`;
                        $("#kredit-kegiatan-hint").text(descSelectedKegiatanId);
                    })
                }
            }

            $('#show-kredit-kegiatan-modal').click(function(e) {
                prt1.isKegiatanAhli = data.isAhli;
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
            })

            $('#modal-alloc').modal('show');
        });

        $('#modal-alloc').on('shown.bs.modal', function() {
            $('#jumlah-target').trigger('focus')
        })

        var disableControl = function() {
            $('#submit-button').prop('disabled', true)
            $('#cancel-button').prop('disabled', true)
            $('#close-button').prop('disabled', true)
            $('#modal-alloc').data('bs.modal')._config.backdrop = 'static'
            $('#modal-alloc').data('bs.modal')._config.keyboard = false
        }

        var enableControl = function() {
            $('#submit-button').prop('disabled', false)
            $('#cancel-button').prop('disabled', false)
            $('#close-button').prop('disabled', false)
            $('#modal-alloc').data('bs.modal')._config.backdrop = true
            $('#modal-alloc').data('bs.modal')._config.keyboard = true
        }

        var updateTotalAlokasi = function() {
            $.ajax({
                type: 'GET',
                url: '<?= base_url('api/struktural/ajax/get-alokasi-kegiatan/' . $kegiatan['id']) ?>',
                success: function(data) {
                    var count = data.count ?? 0;
                    $('.total-alloc').text(count);
                }
            })
        }

        updateTotalAlokasi();

        $('#form-alloc').submit(function(e) {
            e.preventDefault();
            disableControl();

            $.ajax({
                type: 'POST',
                url: '<?= base_url('api/struktural/ajax/set-target-pegawai') ?>',
                data: $(this).serialize(),
                success: function(data) {
                    nowEdited
                        .data('jmlTarget', parseInt(data.data.jumlah_target))
                        .attr('data-jml-target', parseInt(data.data.jumlah_target))
                        .data('idKreditKegiatan', parseInt(data.data.id_kredit_kegiatan))
                        .attr('data-id-kredit-kegiatan', parseInt(data.data.id_kredit_kegiatan))
                    nowEdited.find('.jml').text(parseInt(data.data.jumlah_target));
                    enableControl();
                    $('#modal-alloc').modal('hide');
                    nowEdited = null;
                    updateTotalAlokasi();
                },
                error: function(xhr, status, error) {
                    enableControl();
                    var data = xhr.responseJSON;
                    if (data) {
                        if (data.error.jumlah_target) {
                            $('#modal-error-desc').text(data.error.jumlah_target);
                        }
                    }
                    $('#error-modal').modal('show');
                }
            })

        });

        $('.lihat-detail').click(function() {
            var data = $(this).data();
            $('#modal-prt2-kredit-kegiatan').modal('show');
            prt2.getKreditKegiatan(data.id);
            prt2.showModalDetail();
        });
    })
</script>
<?= $this->endSection() ?>

<?= $this->section('head') ?>
<style>
    /** hover biar ada cursornya */
    #alloc tr {
        cursor: pointer;
    }
</style>
<?= $this->endSection() ?>