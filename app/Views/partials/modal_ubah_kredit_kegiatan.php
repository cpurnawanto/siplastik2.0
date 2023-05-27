<!-- Modal -->
<div class="modal fade" id="modal-prt1-kredit-kegiatan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-prt1-title">Cari kegiatan</h5>
                <div class="ml-auto">
                    <span id="prt1-header-control" style="display: none;">
                        <button type="button" class="btn btn-secondary" id="prt1-header-control-kembali">
                            <i class="fas fa-search"></i>
                            Kembali ke Pencarian
                        </button>
                        <button type="button" class="btn btn-primary" id="prt1-header-control-pilih">
                            <i class="fas fa-check"></i>
                            Pilih Kegiatan Ini
                        </button>
                    </span>
                    <button type="button" class="btn btn-secondary ml-3" data-dismiss="modal" aria-label="Close">
                        Kembali
                        <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div id="modal-prt1-pencarian" class="overflow-auto">
                    <small><i>*) Klik untuk melihat detail kegiatan</i> <br><br></small>
                    <table id="prt1-kk" class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kode</th>
                                <th>Tingkat</th>
                                <th>Kode Perka</th>
                                <th>Kegiatan</th>
                                <th>Uraian Singkat</th>
                                <th>Satuan Hasil</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div id="modal-prt1-detail" style="display: none;">
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
                                    <tbody id="prt1-detail-kegiatan-body">

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
                                <tbody id="prt1-detail-fungsional-body">


                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var prt1 = [];
    cellScripts.push(function() {
        prt1.selectedKegiatanDetail = null;
        prt1.isKegiatanAhli = false;

        prt1.table = $('#prt1-kk').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": "<?= base_url('api/data-tables/kredit-kegiatan') ?>",
            "columns": [{
                    "data": "id"
                }, {
                    "data": "kode"
                },
                {
                    "data": "nama_tingkat"
                },
                {
                    "data": "kode_perka"
                },
                {
                    "data": "kegiatan"
                },
                {
                    "data": "uraian_singkat"
                },
                {
                    "data": "satuan_hasil"
                }
            ]
        });

        prt1.titleCase = function(sentence) {
            var sentence = sentence.toLowerCase().split("_");
            for (var i = 0; i < sentence.length; i++) {
                sentence[i] = sentence[i][0].toUpperCase() + sentence[i].slice(1);
            }

            return sentence.join(" ");
        }

        prt1.entityMap = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;',
            '`': '&#x60;',
            '=': '&#x3D;'
        };


        prt1.escapeHtml = function(string) {
            return String(string).replace(/[&<>"'`=\/]/g, function(s) {
                return prt1.entityMap[s];
            });
        }
        prt1.showModalDetail = function() {
            $('#modal-prt1-pencarian').hide();
            $('#modal-prt1-detail').show();
            $('#modal-prt1-title').text('Sedang memuat mohon tunggu...');
            $('#prt1-detail-kegiatan-body').empty();
            $('#prt1-detail-fungsional-body').empty();
            selectedKegiatanId = null;
        }

        prt1.showModalPencarian = function() {
            $('#modal-prt1-detail').hide();
            $('#prt1-header-control').hide();
            $('#modal-prt1-pencarian').show();
            if (prt1.isKegiatanAhli) {
                $('#modal-prt1-title').text('Cari kredit kegiatan AHLI');
                prt1.table.ajax.url("<?= base_url('api/data-tables/kredit-kegiatan/2') ?>").load();
            } else {
                $('#modal-prt1-title').text('Cari kredit kegiatan TERAMPIL');
                prt1.table.ajax.url("<?= base_url('api/data-tables/kredit-kegiatan/1') ?>").load();
            }

            selectedKegiatanId = null;
        }

        prt1.updateModalDetail = function(data) {

            $('#prt1-header-control').show();
            prt1.selectedKegiatanDetail = data.kredit_kegiatan;

            $('#prt1-detail-kegiatan-body').empty();
            $('#prt1-detail-fungsional-body').empty();
            $('#modal-prt1-title').text('Detail Kredit Kegiatan ' + data.kredit_kegiatan.kode + ' - ' +
                data.kredit_kegiatan.nama_tingkat);

            for (var key in data.kredit_kegiatan) {
                var row = `<tr>
                                <td class="text-nowrap">
                                    ${prt1.titleCase(key)}
                                </td>
                                <td>
                                    ${prt1.escapeHtml(data.kredit_kegiatan[key] ? data.kredit_kegiatan[key] : '-')}
                                </td>
                            </tr>`
                $('#prt1-detail-kegiatan-body').append($(row));
            }

            for (var key in data.kredit_fungsional) {
                var row = `<tr>
                                <td>
                                    ${data.kredit_fungsional[key]['fungsional']}
                                </td>
                                <td class="text-nowrap">
                                    ${prt1.escapeHtml(data.kredit_fungsional[key]['angka_kredit'] ? data.kredit_fungsional[key]['angka_kredit'] : '-')}
                                </td>
                            </tr>`
                $('#prt1-detail-fungsional-body').append($(row));
            }

        }

        prt1.getKreditKegiatan = function(id) {
            $.ajax('<?= base_url('api/ajax/get-kredit-kegiatan/') ?>/' + id, {
                success: function(data) {
                    prt1.updateModalDetail(data.data);
                }
            })
        }

        $('#prt1-header-control-kembali').click(function(e) {
            prt1.showModalPencarian();
        })

        /** onclick baris untuk melihat detail */
        $('#prt1-kk tbody').on('click', 'tr', function() {
            var data = prt1.table.row(this).data();
            prt1.getKreditKegiatan(data.id);
            prt1.showModalDetail();
        });
    })
</script>