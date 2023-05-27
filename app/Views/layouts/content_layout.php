<?php

/**
 * Layout untuk tampilan tabel indeks
 * 
 * Render parameter :
 * head
 * breadcrumb
 * title (controller)
 * title widget
 * table
 * content
 * script
 * 
 */

?>
<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
	<!-- Fonts and icons -->

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<div class="container">
    <?= $this->renderSection('breadcrumb') ?>
    <div class="card">
        <div class="card-header">
            <div class="float-left">
                <?= $this->renderSection('left_title_widget') ?>
                <h1 class="h4"><?= strlen($title) > 70 ? substr($title, 0, 70) . "..." : $title ?></h1>
            </div>
            <div class="float-right">
                <?= $this->renderSection('title_widget') ?>
            </div>
        </div>
        <div class="card-body">
            <?= view_cell('App\\Libraries\\Cells\\Form::statusBadge') ?>
            <?= $this->renderSection('table') ?>
            <div class="mb-2"></div>
            <?= $this->renderSection('content') ?>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
