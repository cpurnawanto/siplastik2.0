<div class="modal fade" id="modal-prt2-kredit-kegiatan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-prt2-title">Cari kegiatan</h5>
                <div id="prt2-header-control" class="ml-auto" style="display: none;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
            <div class="modal-body">
                <div id="modal-prt2-detail">
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
                                    <tbody id="prt2-detail-kegiatan-body">

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
                                <tbody id="prt2-detail-fungsional-body">


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
    var prt2 = [];
    cellScripts.push(function() {

        prt2.entityMap = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;',
            '/': '&#x2F;',
            '`': '&#x60;',
            '=': '&#x3D;'
        };

        prt2.titleCase = function(sentence) {
            var sentence = sentence.toLowerCase().split("_");
            for (var i = 0; i < sentence.length; i++) {
                sentence[i] = sentence[i][0].toUpperCase() + sentence[i].slice(1);
            }

            return sentence.join(" ");
        }

        prt2.escapeHtml = function(string) {
            return String(string).replace(/[&<>"'`=\/]/g, function(s) {
                return prt2.entityMap[s];
            });
        }
        prt2.showModalDetail = function() {
            $('#modal-prt2-title').text('Sedang memuat mohon tunggu...');
            $('#prt2-detail-kegiatan-body').empty();
            $('#prt2-detail-fungsional-body').empty();
        }

        prt2.updateModalDetail = function(data) {

            $('#prt2-header-control').show();

            $('#prt2-detail-kegiatan-body').empty();
            $('#prt2-detail-fungsional-body').empty();
            $('#modal-prt2-title').text('Detail Kredit Kegiatan ' + data.kredit_kegiatan.kode + ' - ' +
                data.kredit_kegiatan.nama_tingkat);

            for (var key in data.kredit_kegiatan) {
                var row = `<tr>
                                <td class="text-nowrap">
                                    ${prt2.titleCase(key)}
                                </td>
                                <td>
                                    ${prt2.escapeHtml(data.kredit_kegiatan[key] ? data.kredit_kegiatan[key] : '-')}
                                </td>
                            </tr>`
                $('#prt2-detail-kegiatan-body').append($(row));
            }

            for (var key in data.kredit_fungsional) {
                var row = `<tr>
                                <td>
                                    ${data.kredit_fungsional[key]['fungsional']}
                                </td>
                                <td class="text-nowrap">
                                    ${prt2.escapeHtml(data.kredit_fungsional[key]['angka_kredit'] ? data.kredit_fungsional[key]['angka_kredit'] : '-')}
                                </td>
                            </tr>`
                $('#prt2-detail-fungsional-body').append($(row));
            }

        }

        prt2.getKreditKegiatan = function(id) {
            $.ajax('<?= base_url('api/ajax/get-kredit-kegiatan/') ?>/' + id, {
                success: function(data) {
                    prt2.updateModalDetail(data.data);
                }
            })
        }

    });
</script>