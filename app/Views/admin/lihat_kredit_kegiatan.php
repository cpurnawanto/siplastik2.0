<?= $this->extend('layouts/content_layout') ?>
<?php if (!isset($not_admin)) : ?>
    <?= $this->section('breadcrumb') ?>
    <?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Administrator', 'uri' => 'admin'], ['text' => 'Indeks Kredit Kegiatan', 'uri' => 'admin/kredit_kegiatan'], ['text' => 'Lihat Kredit Kegiatan']]) ?>
    <?= $this->endSection() ?>

    <?= $this->section('title_widget') ?>
    <a href="<?= base_url('admin/kredit-kegiatan') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
    <?= $this->endSection() ?>
<?php else : ?>
    <?= $this->section('breadcrumb') ?>
    <?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', [['text' => 'Personal', 'uri' => 'personal'], ['text' => 'Indeks Kredit Kegiatan', 'uri' => 'personal/fungsional/kredit-kegiatan'], ['text' => 'Lihat Kredit Kegiatan']]) ?>
    <?= $this->endSection() ?>

    <?= $this->section('title_widget') ?>
    <a href="<?= base_url('personal/fungsional/kredit-kegiatan') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
    <?= $this->endSection() ?>
<?php endif; ?>
<?= $this->section('content') ?>
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
                <tbody>

                    <?php
                    /**
                     *  key_dari_table nanti menjadi Key Dari TAble
                     */
                    foreach ($data['kredit_kegiatan'] as $key => $value) : ?>
                        <?php if ($key === 'id') continue; ?>
                        <tr>
                            <td class="text-nowrap"><?= ucwords(str_replace('_', ' ', $key)) ?></td>
                            <td><?= $value ? $value : '-' ?></td>
                        </tr>
                    <?php endforeach; ?>
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
            <tbody>

                <?php foreach ($data['kredit_fungsional'] as $element) : ?>
                    <tr>
                        <td><?= $element['fungsional'] ?></td>
                        <td class="text-nowrap"><?= $element['angka_kredit']  ? $element['angka_kredit'] : '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>