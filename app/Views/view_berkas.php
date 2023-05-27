<?= $this->extend('layouts/content_layout') ?>

<?= $this->section('breadcrumb') ?>
<?= view_cell('\\App\\Libraries\\Cells\\Breadcrumb::build', []) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
        <ul class="nav nav-pills mb-3" id="petaTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="pills-wa-tab" data-toggle="pill" href="#pills-wa" role="tab" aria-controls="pills-wa" aria-selected="true">Peta WA</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-wb10-tab" data-toggle="pill" href="#pills-wb10" role="tab" aria-controls="pills-wb10" aria-selected="false">Peta WB 2010</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-wb20-tab" data-toggle="pill" href="#pills-wb20" role="tab" aria-controls="pills-wb20" aria-selected="false">Peta WB 2020</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="pills-ws-tab" data-toggle="pill" href="#pills-ws" role="tab" aria-controls="pills-ws" aria-selected="false">Peta WS</a>
            </li>
        </ul>
        <div class="dropdown-divider"></div>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-wa" role="tabpanel" aria-labelledby="pills-wa-tab">
                <div class="table-responsive">    
                    <table id="basic-datatables" class="display table table-head-bg-primary table-striped table-hover mb-3" >
                       <thead>
                              <tr>
                                  <th>No</th>
                                  <th>ID Desa</th>
                                  <th>Gambar</th>
                                  <th>Kecamatan</th>
                                  <th>Desa</th>
                                  <th>Tanggal Upload</th>
                                  <th>Keterangan</th>
                                  <th>Action</th>
                             </tr>
                       </thead>
                       <tbody>
                             <?php
                             $no  = 1;
                             foreach ($WA as $row) {
                             ?>
                                 <tr>
                                     <td><?= $no++; ?></td>
                                     <td><?= $row->IDBS; ?></td>
                                     <td><img width="150px" class="img-thumbnail" src="<?= base_url() . "/uploads/berkas/" . $row->berkas; ?>"></td>
                                     <td><?= $row->nmKec; ?></td>
                                     <td><?= $row->nmDesa; ?></td>
                                     <td><?php setlocale(LC_ALL, 'id-ID', 'id_ID'); echo strftime('%A %d %B %Y, %H:%M', strtotime($row->tanggalUpload));?>
                                    </td>
                                     <td><?= $row->keterangan; ?></td>
                                     <td><a class="btn btn-primary" href="<?= base_url(); ?>/berkas/download/<?= $row->berkas; ?>">Download</a></td>
                                 </tr>
                             <?php
                             }
                             ?>
                       </tbody>
                       </table>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-wb10" role="tabpanel" aria-labelledby="pills-wb10-tab">
            <div class="table-responsive">    
                    <table id="basic-datatables" class="display table table-head-bg-primary table-striped table-hover mb-3" >
                       <thead>
                              <tr>
                                  <th>No</th>
                                  <th>ID BS</th>
                                  <th>Gambar</th>
                                  <th>Kecamatan</th>
                                  <th>Desa</th>
                                  <th>Tanggal Upload</th>
                                  <th>Keterangan</th>
                                  <th>Action</th>
                             </tr>
                       </thead>
                       <tbody>
                             <?php
                             $no  = 1;
                             foreach ($WB1 as $row) {
                             ?>
                                 <tr>
                                     <td><?= $no++; ?></td>
                                     <td><?= $row->IDBS; ?></td>
                                     <td><img width="150px" class="img-thumbnail" src="<?= base_url() . "/uploads/berkas/" . $row->berkas; ?>"></td>
                                     <td><?= $row->nmKec; ?></td>
                                     <td><?= $row->nmDesa; ?></td>
                                     <td><?php setlocale(LC_ALL, 'id-ID', 'id_ID'); echo strftime('%A %d %B %Y, %H:%M', strtotime($row->tanggalUpload));?>
                                    </td>
                                     <td><?= $row->keterangan; ?></td>
                                     <td><a class="btn btn-primary" href="<?= base_url(); ?>/berkas/download/<?= $row->berkas; ?>">Download</a></td>
                                 </tr>
                             <?php
                             }
                             ?>
                       </tbody>
                       </table>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-wb20" role="tabpanel" aria-labelledby="pills-wb20-tab">
            <div class="table-responsive">    
                    <table id="basic-datatables" class="display table table-head-bg-primary table-striped table-hover mb-3" >
                       <thead>
                              <tr>
                                  <th>No</th>
                                  <th>ID BS</th>
                                  <th>Gambar</th>
                                  <th>Kecamatan</th>
                                  <th>Desa</th>
                                  <th>Tanggal Upload</th>
                                  <th>Keterangan</th>
                                  <th>Action</th>
                             </tr>
                       </thead>
                       <tbody>
                             <?php
                             $no  = 1;
                             foreach ($WB2 as $row) {
                             ?>
                                 <tr>
                                     <td><?= $no++; ?></td>
                                     <td><?= $row->IDBS; ?></td>
                                     <td><img width="150px" class="img-thumbnail" src="<?= base_url() . "/uploads/berkas/" . $row->berkas; ?>"></td>
                                     <td><?= $row->nmKec; ?></td>
                                     <td><?= $row->nmDesa; ?></td>
                                     <td><?php setlocale(LC_ALL, 'id-ID', 'id_ID'); echo strftime('%A %d %B %Y, %H:%M', strtotime($row->tanggalUpload));?>
                                    </td>
                                     <td><?= $row->keterangan; ?></td>
                                     <td><a class="btn btn-primary" href="<?= base_url(); ?>/berkas/download/<?= $row->berkas; ?>">Download</a></td>
                                 </tr>
                             <?php
                             }
                             ?>
                       </tbody>
                       </table>
                </div>
            </div>
            <div class="tab-pane fade" id="pills-ws" role="tabpanel" aria-labelledby="pills-ws-tab">
            <div class="table-responsive">    
                    <table id="basic-datatables" class="display table table-head-bg-primary table-striped table-hover mb-3" >
                       <thead>
                              <tr>
                                  <th>No</th>
                                  <th>ID SLS</th>
                                  <th>Gambar</th>
                                  <th>Kecamatan</th>
                                  <th>Desa</th>
                                  <th>Tanggal Upload</th>
                                  <th>Keterangan</th>
                                  <th>Action</th>
                             </tr>
                       </thead>
                       <tbody>
                             <?php
                             $no  = 1;
                             foreach ($WS as $row) {
                             ?>
                                 <tr>
                                     <td><?= $no++; ?></td>
                                     <td><?= $row->IDBS; ?></td>
                                     <td><img width="150px" class="img-thumbnail" src="<?= base_url() . "/uploads/berkas/" . $row->berkas; ?>"></td>
                                     <td><?= $row->nmKec; ?></td>
                                     <td><?= $row->nmDesa; ?></td>
                                     <td><?php setlocale(LC_ALL, 'id-ID', 'id_ID'); echo strftime('%A %d %B %Y, %H:%M', strtotime($row->tanggalUpload));?>
                                    </td>
                                     <td><?= $row->keterangan; ?></td>
                                     <td><a class="btn btn-primary" href="<?= base_url(); ?>/berkas/download/<?= $row->berkas; ?>">Download</a></td>
                                 </tr>
                             <?php
                             }
                             ?>
                       </tbody>
                       </table>
                </div>
            </div>
        </div>
<?= $this->endSection() ?>
<?= $this->section('script') ?>
<script>
    $(function() {
        $('table').DataTable();
    })
</script>
<?= $this->endSection() ?>