<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Unggah Peta']]) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="container">
        <div class="card">
            <div class="card-body">
                <?php if (!empty(session()->getFlashdata('error'))) : ?>
                    <div class="alert alert-danger" role="alert">
                        <h4>Periksa Entrian Form</h4>
                        </hr />
                        <?php echo session()->getFlashdata('error'); ?>
                    </div>
                <?php endif; ?>
                <form method="post" action="<?= base_url(); ?>/berkas/save" enctype="multipart/form-data">
                    <?= csrf_field(); ?>
                    <div class="mb-3">
                        <label for="berkas" class="form-label">Berkas</label>
                        <input type="file" class="form-control" id="berkas" name="berkas[]" multiple>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= old('keterangan'); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="kdKec" class="form-label">Kecamatan</label>
                        <div class="mb-3">
                            <select class="form-control" name="kdKec" id="kdKec" onchange="cari_desa(this.value)">
                            <option value="" disabled selected>Pilih Kecamatan</option>
                            <?php
                            foreach($kec as $d){
                                ?>
                                <option value="<?php echo $d->kdKec; ?>"><?php echo $d->nmKec; ?></option>
                                <?php 
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="kdDesa" class="form-label">Desa</label>
                        <div class="mb-3">
                            <select class="form-control" name="kdDesa" id="kdDesa">                                
                            <option value="" disabled selected>Pilih Desa</option>
                            <?php
                            foreach($desa as $d){
                                ?>
                                <option value="<?php echo $d->kdDesa; ?>"><?php echo $d->nmDesa; ?></option>
                                <?php 
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="kdJenis" class="form-label">Jenis</label>
                        <div class="mb-3">
                            <select class="form-control" name="kdJenis" id="kdJenis" onchange=>
                                <option value="" disabled selected>Pilih Jenis Peta</option>
                                <option value="WA">Peta WA</option>
                                <option value="WB1">Peta WB 2010</option>
                                <option value="WB2">Peta WB 2020</option>
                                <option value="WS">Peta WS</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="kdBS" class="form-label">Kode BS</label>
                        <textarea class="form-control" id="kdBS" name="kdBS" rows="3"><?= old('kdBS'); ?></textarea>
                    </div>
                    <div class="mb-3">
                        <input type="submit" class="btn btn-info" value="Upload" />
                    </div>
                </form>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>

<?= $this->section('script') ?>
<?= $this->endSection() ?>
