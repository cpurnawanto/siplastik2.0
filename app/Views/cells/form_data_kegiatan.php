<?php

/**
 * Fungsi untuk mempermudah ekstraksi $input
 */
function inp(string $input_key, $input, $default = '')
{
    return !empty($input[$input_key]) ? esc($input[$input_key]) : $default;
}

function inpSelect(string $input_key, string $input_val, $input)
{
    if (!empty($input[$input_key])) {
        return $input[$input_key] == $input_val ? 'selected' : '';
    }
    return '';
}


function isHubungkanAK($input)
{
    // repopulate form tambah
    if (!empty($input['hubungkan_ak'])) {
        return $input['hubungkan_ak'] == 'true';
    }

    // repopulate ubah
    if (!empty($input['id_kredit_ahli'])) {
        return $input['id_kredit_ahli'];
    }
    if (!empty($input['id_kredit_terampil'])) {
        return $input['id_kredit_terampil'];
    }

    if (!empty($input['id']) && empty($input['id_kredit_ahli']) && empty($input['id_kredit_terampil'])) {
        return false;
    }

    //default
    return true;
}

?>

<form action="<?= base_url($uri) ?>" method="POST">
    <?= csrf_field() ?>

    <div class="form-group">
        <div class="row">
            <div class="col-lg-3">
                <label for="nama_kegiatan">Nama Kegiatan *</label>
            </div>
            <div class="col-lg-9">
                <input type="text" class="form-control" name="kegiatan[nama_kegiatan]" id="nama-kegiatan" placeholder="Mohon masukkan nama kegiatan" required autocomplete="off" value="<?= inp('nama_kegiatan', $input) ?>">
            </div>
        </div>

    </div>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-3">
                <label for="jumlah_target">Satuan Target *</label>
            </div>
            <div class="col-lg-9">
                <input type="text" class="form-control" name="kegiatan[satuan_target]" placeholder="Masukkan satuan target" id="satuan-target" required autocomplete="off" value="<?= inp('satuan_target', $input, 'Unit') ?>">
            </div>
        </div>
    </div>
    <?php if (!$template) : ?>
        <div class="form-group">
            <div class="row align-items-center">
                <div class="col-lg-3">
                    <label for="jumlah_target">Jumlah Target *</label>
                </div>
                <div class="col-lg-9">
                    <div class="input-group">
                        <input type="number" class="form-control" name="kegiatan[jumlah_target]" id="jumlah_target" placeholder="Masukkan jumlah target" required autocomplete="off" value="<?= inp('jumlah_target', $input) ?>">
                        <div class="input-group-prepend">
                            <div class="input-group-text" id="satuan-target-text"><?= inp('satuan_target', $input, 'Unit') ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-3">
                <label for="bobot">Bobot *</label>
            </div>
            <div class="col-lg-9">
                <input type="number" class="form-control" name="kegiatan[bobot]" id="bobot" placeholder="Bobot per target" required autocomplete="off" step="0.0001" value="<?= inp('bobot', $input) ?>">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-3">
                <label for="id_unit_kerja">Unit Kerja</label>
            </div>
            <div class="col-lg-9">
                <select class="form-control" name="kegiatan[id_unit_kerja]" id="id_unit_kerja">
                    <?php foreach ($list['unit_kerja'] as $unit) : ?>
                        <option value="<?= $unit['id'] ?>" <?= inpSelect('id_unit_kerja', $unit['id'], $input) ?>><?= $unit['unit_kerja'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
    <?php if (!$template) : ?>
        <div class="form-group">
            <div class="row align-items-center">
                <div class="col-lg-3">
                    <label for="tgl_mulai">Waktu Mulai *</label>
                </div>
                <div class="col-lg-9">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-calendar"></i> </div>
                        </div>
                        <input type="text" class="form-control datepicker" name="kegiatan[tgl_mulai]" id="tgl_mulai" placeholder="Klik untuk tanggal mulai" required autocomplete="off" value="<?= inp('tgl_mulai', $input) ?>">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Contoh: 2021-02-22 </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="row align-items-center">
                <div class="col-lg-3">
                    <label for="tgl_selesai">Waktu Selesai *</label>
                </div>
                <div class="col-lg-9">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text"><i class="fas fa-calendar"></i> </div>
                        </div>
                        <input type="text" class="form-control datepicker" name="kegiatan[tgl_selesai]" id="tgl_selesai" placeholder="Klik untuk tanggal selesai" required autocomplete="off" value="<?= inp('tgl_selesai', $input) ?>">
                        <div class="input-group-prepend">
                            <div class="input-group-text">Contoh: 2021-02-27 </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <div class="row">
            <div class="col-lg-3 pr-0">
                <label for="nama_kegiatan">Hubungkan Angka Kredit *</label>
            </div>
            <div class="col-lg-9">
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="kegiatan[hubungkan_ak]" id="hubungkan-ak-true" value="true" <?= isHubungkanAK($input) ? 'checked' : '' ?>> Ya
                    </label>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label">
                        <input class="form-check-input" type="radio" name="kegiatan[hubungkan_ak]" id="hubungkan-ak-false" value="false" <?= !isHubungkanAK($input) ? 'checked' : '' ?>> Tidak
                    </label>
                </div>
                <div id="hubungkan-ak">
                    <br>
                    <p class="ml-1 mb-1">Kredit Kegiatan Terampil : <br><span id="kredit-kegiatan-terampil-hint"><?= inp('id_kredit_terampil', $input) ? inp('id_kredit_terampil_desc', $input) : '-' ?></span></p>
                    <input type="hidden" name="kegiatan[id_kredit_terampil]" id="id-kredit-terampil" value="<?= inp('id_kredit_terampil', $input) ?>">
                    <button type="button" class="btn btn-primary btn-sm mt-2" id="show-kegiatan-terampil-modal" data-toggle="modal" data-target="#modal-prt1-kredit-kegiatan">
                        Pilih Kredit Terampil
                    </button>
                    <br>
                    <br>
                    <p class="ml-1 mb-1">Kredit Kegiatan Ahli : <br><span id="kredit-kegiatan-ahli-hint"><?= inp('id_kredit_ahli', $input) ? inp('id_kredit_ahli_desc', $input) : '-' ?></span></p>
                    <input type="hidden" name="kegiatan[id_kredit_ahli]" id="id-kredit-ahli" value="<?= inp('id_kredit_ahli', $input) ?>">
                    <button type="button" class="btn btn-primary btn-sm mt-2" id="show-kegiatan-ahli-modal" data-toggle="modal" data-target="#modal-prt1-kredit-kegiatan">
                        Pilih Kredit Ahli
                    </button>
                    <br>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-lg-3">
                <label for="keterangan">Keterangan</label>
            </div>
            <div class="col-lg-9">
                <textarea name="kegiatan[keterangan]" id="keterangan" cols="30" rows="10" class="form-control"><?= inp('keterangan', $input) ?></textarea>
            </div>
        </div>
    </div>
    <br>
    <button type="submit" class="btn btn-primary px-4"> <i class="fas fa-save mr-1"></i>Simpan</button>
    <br>
    <br>
    <small>*) Wajib diisi</small>
</form>

<?= $this->include('partials/modal_ubah_kredit_kegiatan') ?>

<script>
    cellScripts.push(function() {
        var enableHubungkanAK = function() {
            $('#hubungkan-ak').show();
        }

        var disableHubungkanAK = function() {
            $('#hubungkan-ak').hide();
        }

        <?php if (isHubungkanAK($input)) : ?>
            enableHubungkanAK();
        <?php else : ?>
            disableHubungkanAK();
        <?php endif; ?>


        $('#satuan-target').change(function(e) {
            if ($(this).val().trim() === '') {
                $(this).val('Unit');
            }
            $('#satuan-target-text').text($(this).val());

        })

        $('input[type=radio][name="kegiatan[hubungkan_ak]"]').change(function() {
            if (this.value === 'true') {
                enableHubungkanAK();
            } else if (this.value === 'false') {
                disableHubungkanAK();
            }
        });

        //Control attach untuk tombol di modal_ubah_kredit_kegiatan pilih
        $('#prt1-header-control-pilih').click(function(e) {
            var descSelectedKegiatanId = `${prt1.selectedKegiatanDetail.uraian_singkat} ${prt1.selectedKegiatanDetail.kegiatan ? ('- '+prt1.selectedKegiatanDetail.kegiatan) : ''} (${prt1.selectedKegiatanDetail.kode}-${prt1.selectedKegiatanDetail.nama_tingkat} ${prt1.selectedKegiatanDetail.kode_perka})`;
            if (prt1.isKegiatanAhli) {
                $('#kredit-kegiatan-ahli-hint').text(descSelectedKegiatanId);
                $('#id-kredit-ahli').val(prt1.selectedKegiatanDetail.id);
            } else {
                $('#kredit-kegiatan-terampil-hint').text(descSelectedKegiatanId);
                $('#id-kredit-terampil').val(prt1.selectedKegiatanDetail.id);
            }

            $('#modal-prt1-kredit-kegiatan').modal('hide');
        })

        //Event handler untuk summon modal modal_ubah_kredit_kegiatan
        $('#show-kegiatan-terampil-modal').click(function(e) {
            var id = $('#id-kredit-terampil').val();
            prt1.isKegiatanAhli = false;
            if (id) {
                prt1.getKreditKegiatan(id);
                prt1.showModalDetail();
            } else {
                prt1.showModalPencarian();
            }
        })

        $('#show-kegiatan-ahli-modal').click(function(e) {
            var id = $('#id-kredit-ahli').val();
            prt1.isKegiatanAhli = true;
            if (id) {
                prt1.getKreditKegiatan(id);
                prt1.showModalDetail();
            } else {
                prt1.showModalPencarian();
            }
        })
    });
</script>