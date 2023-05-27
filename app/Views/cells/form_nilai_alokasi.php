<form action="<?= base_url($uri) ?>" method="POST">
    <?= csrf_field() ?>

    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-3">
                <label for="jumlah_target">Jumlah Target *</label>
            </div>
            <div class="col-lg-9">
                <input type="number" class="form-control" name="alokasi[jumlah_target]" id="jumlah_target" placeholder="Masukkan Jumlah Target" required autocomplete="off" min="1" value="<?= $input['jumlah_target'] ?>">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row align-items-center">
            <div class="col-lg-3">
                <label for="persen_kualitas">Persen Kualitas *</label>
            </div>
            <div class="col-lg-9">
                <input type="number" class="form-control" name="alokasi[persen_kualitas]" id="persen_kualitas" placeholder="Masukkan Persen Kualitas" required autocomplete="off" min="0" max="100" value="<?= $input['persen_kualitas'] ?>">
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="row">
            <div class="col-lg-3">
                <label for="keterangan">Keterangan</label>
            </div>
            <div class="col-lg-9">
                <textarea name="alokasi[keterangan]" id="keterangan" cols="30" rows="5" class="form-control" autocomplete="off"><?= $input['keterangan'] ?></textarea>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary px-4"> <i class="fas fa-save mr-1"></i>Simpan</button>
    <br>
    <br>
    <small>*) Wajib diisi</small>
</form>