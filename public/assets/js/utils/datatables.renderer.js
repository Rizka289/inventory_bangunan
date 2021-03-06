$(document).ready(async function () {
    var data = await persiapan_data();
    add_eventlistener(data);
    inisialisasi(data);
});

async function tambahHandler(data, isEdit = false, defData = null, formBody = null, modalConf = null) {
    var url_tambah_data = "<?php echo $url_tambah_data ?>";
    var url_update_data = "<?= $url_update_data ?>";

    var form = "<?php echo $form ?>";
    var pasdata = <?php echo isset($formdata) && !empty($formdata) ? json_encode($formdata) : '{}' ?>;
    var formdata = new FormData();
    var customBody = formBody != null;
    
    formdata.append('id', form);
    formdata.append('data', JSON.stringify(pasdata))
    var form = await fetch(path + 'ws/form', {
        method: 'POST', 
        body: formdata
    }).then(res => {
        if (res.status != 200)
            return;
        else
            return res.text()
    }).then(res => {
        if (!res)
            return;
        else {
            var opt = {
                type: 'form',
                clickToClose: false,
                kembali: false,
                destroy: true,
                open: true,
                ajax: true,
                rules: modalConf && modalConf.rules ? modalConf.rules : null,
                size: modalConf && modalConf.size ? modalConf.size :  'modal-lg',
                modalTitle: isEdit ? "Edit data" : 'Tambah data',
                modalPos: modalConf && modalConf.pos ? modalConf.pos : 'right',
                saatBuka: function () {
                    if (!$('body').hasClass('modal-open'))
                        $('body').addClass('modal-open');
                    data.modal_buka(isEdit, defData);
                },
                submitSuccess: function (res) {
                    $('#submit').prop('disabled', false);
                    $('#modal-' + data.tableid).modal('hide');
                    endLoading();
                    var toastOpt = data.toasCofig;
                    toastOpt.bg = 'bg-success';
                    toastOpt.title = 'Berhasil';
                    toastOpt.message = res.message;
                    makeToast(toastOpt);
    
                    data.loadData();
    
                    setTimeout(function () {
                        $('#batal').trigger('click');
                    }, 2000);
                },
                submitError: function (res) {
                    $('#submit').prop('disabled', false);
                    $('#modal-' + data.tableid).modal('hide');
                    endLoading();
    
                    res = res.responseJSON
                    var toastOpt = data.toasCofig;
    
                    toastOpt.bg = 'bg-danger';
                    toastOpt.message = res.message;
                    toastOpt.title = "Galat";
                    makeToast(toastOpt);

                    data.loadData();
    
                    setTimeout(function () {
                        $('#batal').trigger('click');
                    }, 2000);
                },
                sebelumSubmit: function () {
                    $('#submit').prop('disabled', true);
                    showLoading();
                },
                formOpt: {
                    formId: "form-" + data.tableid,
                    formAct:  modalConf && modalConf.submit ? path + modalConf.submit : isEdit ? path + url_update_data : path + url_tambah_data,
                    formMethod: 'POST',
                    formAttr: ''
                },
                modalBody: {
                    input: !customBody ? [
                        {
                            type: 'custom', text: res,
                        },
                    ] : formBody,
                    buttons: [
                        { type: 'reset', data: 'data-dismiss="modal"', text: 'Batal', id: "batal", class: "btn btn btn-warning" },
                        { type: 'button', text: 'kembali', id: "kembali", class: "btn btn btn-secondary" },
                        { type: 'submit', text: 'Simpan', id: "submit", class: "btn btn btn-primary" }
                    ]
                },
            }
            generateModal('modal-' + data.tableid, 'body', opt)
        }
    });
}


async function persiapan_data() {
    var data = {
        thumb: {},
    };

    var tableid = "<?php echo $tableid ?>"
    var adaCheckbox = <?php echo isset($adaCheckbox) ? $adaCheckbox : 'false' ?>;
    var ada_filter_tanggal = <?php isset($ada_filter_tanggal) ? var_export($ada_filter_tanggal) : var_export(false) ?>;

    data.ada_filter_tanggal = ada_filter_tanggal;

    data.tableid = tableid;
    var toasCofig = {
        wrapper: '#' + tableid,
        id: 'toast-barang',
        delay: 3000,
        autohide: true,
        show: true,
        bg: 'bg-danger',
        textColor: 'text-white',
        time: waktu(null, 'HH:mm'),
        toastId: 'logout-error',
        title: 'Gagal, Terjadi kesalahan',
        type: 'danger',
        hancurkan: true
    }
    data.toasCofig = toasCofig;

    modal_buka = function (isEdit = false, defData = null) {
        $('#kembali').hide();
        $('#form-modal-barang').removeAttr('tabindex');
        $('#penjual').change(function () {
            if (!$(this).val())
                $('#tambah-penjual').prop('disabled', false)
            else
                $('#tambah-penjual').prop('disabled', true)

        });

        $('#tambah-penjual').click(function () {
            $('#halaman-2').show();
            $('#halaman-1').animate({ height: 'toggle' });

            setTimeout(function () {
                $('#halaman-1').hide();
            }, 500)

            if ($('#halaman-2').is(':visible'))
                $('#kembali').show();

            setTimeout(function () {
                $('body').addClass('modal-open');
            }, 500);
        });

        $('#kembali').click(function () {
            $('#halaman-1').show();
            $('#halaman-2').animate({ height: 'toggle' });


            setTimeout(function () {
                $('#halaman-2').hide();
            }, 500)

            if ($('#halaman-1').is(':visible'))
                $('#kembali').hide();

            setTimeout(function () {
                $('body').addClass('modal-open');
            }, 500);
        });
        
        $('.select2').select2({
            minimumInputLength: 3,
            ajax: {
                url: path + 'penjual/select2',
                dataType: 'json',
                data: function (params) {
                    var query = {
                        search: params.term
                    }
                    return query;
                },
                processResults: function (data) {
                    var hasil = [];
                    data.data.forEach(d => {
                        hasil.push({ id: d.id, text: d.nama_lengkap + ' - ' + d.alamat.substr(0, 20) + '...' });
                    });
                    return {
                        results: hasil
                    }
                }
            },
        });

        if(isEdit){
            var editCallback = <?php echo isset($editCallback) ? $editCallback : "null" ?>;
            if(editCallback != null)
                editCallback(defData)
        }
    }
    data.modal_buka = modal_buka
    loadData = async function (url = null) {
        showLoading();
        var sumberData = "<?php echo $url_sumber_data ?>";
        var rowScript = <?php echo $row_scirpt ?>;
        var extra_buttons = <?php echo isset($extra_button) && !empty($extra_button) ? json_encode($extra_button) : '[]' ?>;
        var ada_hapus = <?php isset($ada_hapus) ? var_export($ada_hapus) : var_export(true) ?>;
        var ada_edit = <?php isset($ada_edit) ? var_export($ada_edit) : var_export(true) ?>;
        var ada_tambah = <?php isset($ada_tambah) ? var_export($ada_tambah) : var_export(true) ?>;
        var index_id = <?php echo isset($index_id) ? $index_id : '0' ?>;

        var url = !url ? path + sumberData : url;
        var data = await fetch(url, { method: 'GET' }).then(res => res.json()).then(res => {
            if (!res.data)
                return;
            if ($.fn.DataTable.isDataTable('#' + tableid)) {
                $('#' + tableid).DataTable().clear();
                $('#' + tableid).DataTable().destroy();
            }

            var rows = '';
            var data = res.data;
            data.forEach((d, i) => {
                rows += rowScript(d, i);
            });
            $('#' + tableid + ' tbody').html(rows);
            endLoading();
            return res.data;
        });
        var options = {
            search: true,
            info: true,
            order: true,
            changeMenu: false,
            change: false,
            responsive: true,
            dom: 'Bfrtip',
            buttons: [
                
            ],
        };  

        if(ada_tambah){
            options.buttons.push(
                {
                    data: {modal_buka: modal_buka, tableid: tableid, toasCofig: toasCofig, loadData: loadData },
                    attr: { 'class': 'btn btn-primary' },
                    text: 'Tambah',
                    action: async function (e, dt, node, config) {
                        $(node).prop('disabled', true);
                        await tambahHandler(config.data);
                        $(node).prop('disabled', false);
                    }
                },
            )
        }
        
        if(adaCheckbox){
            options.select = {
                style:    'multi',
                selector: 'td:first-child'
            };
            options.columnDefs = [
                {
                    targets: 0,
                    data: null,
                    defaultContent: '',
                    orderable: false,
                    className: 'select-checkbox'
                }
            ]
            var url_delete_data = "<?= $url_delete_data ?>";

           
            if(ada_edit){
                options.buttons.push(
                    {
                        data: {modal_buka: modal_buka, tableid: tableid, toasCofig: toasCofig, loadData: loadData },
                        attr: { 'class': 'btn btn-primary', 'type': 'button', 'id': 'btn-edit' },
                        text: 'Edit',
                        action: async function (e, dt, node, config) {
                            $(node).prop('disabled', true);
                            var data = instance.dataTables[config.data.tableid].rows({ selected: true }).data()
                            if(data.length != 1){
                                alert("Pilih Satu baris data");
                                $(node).prop('disabled', false);
                                return;
                            }
                            await tambahHandler(config.data, true, data[0]);
    
                            $(node).prop('disabled', false);
                        }
                    },
                );
            }
            
            if(ada_hapus){
                options.buttons.push(
                    {
                        data: {modal_buka: modal_buka, tableid: tableid, toasCofig: toasCofig, loadData: loadData },
                        attr: { 'class': 'btn btn-primary', 'type': 'button', 'id': 'btn-edit' },
                        text: 'Hapus',
                        action: async function (e, dt, node, config) {
                            $(node).prop('disabled', true);
                            var data = instance.dataTables[config.data.tableid].rows({ selected: true }).data().toArray()
                            if(data.length == 0){
                                alert("Pilih baris yang ingin dihapus");
                                $(node).prop('disabled', false);
                                return;
                            }
                            var res = confirm("Yakin Ingin Menghapus Data .?");
                            if(!res){
                                $(node).prop('disabled', false);
                                return;
                            }
                            $("#pros-loading").show();
                        
                            var ids = data.map(d => d[index_id]);
                            
                            $.post(path + url_delete_data, {
                                ids: ids
                            }, function(res, code, d){
                                var toastOpt = config.data.toasCofig;
                                toastOpt.bg = 'bg-success';
                                toastOpt.title = 'Berhasil';
                                toastOpt.message = res.message;
                                config.data.loadData();
                                makeToast(toastOpt);
                                
                            }, 'json').fail(function(res){
                                var toastOpt = config.data.toasCofig;
                                res = res.responseJSON
                                toastOpt.message = res.message;

                                makeToast(toastOpt);
                                config.data.loadData();

                                
                            });
                            $("#pros-loading").hide();
                            $(node).prop('disabled', false);
                        }
                    },
                );
            }

        }

        extra_buttons.forEach(btn => {
            if(btn.nonCustom){
                options.buttons.push(btn.button)
            }else{
                options.buttons.push({
                data: {modal_buka: modal_buka, tableid: tableid, toasCofig: toasCofig, loadData: loadData },
                attr: { 'class': 'btn btn-primary' },
                text: btn.text,
                action: eval(btn.funct)
            })
            }
            
        });
        window.opt = options
        initDatatable('#' + tableid, options);

    }
    data.loadData = loadData
    return data;
}


function add_eventlistener(data) {

}


function inisialisasi(data) {
    var sumberData = "<?php echo $url_sumber_data ?>";
    if(data.ada_filter_tanggal){
        var startDate = "<?php echo isset($minTanggal) ? $minTanggal : waktu(null, MYSQL_DATE_FORMAT) ?>";
        var endDate = "<?php echo isset($maxTanggal) ? $maxTanggal : waktu(null, MYSQL_DATE_FORMAT) ?>";
    
        $("#filter-tanggal").daterangepicker({
            timePicker: false,
            showDropdowns: true,
            startDate: moment(startDate).format("MM/D/Y"),
            endDate: moment(endDate).format('MM/D/Y')
        })

        $("#filter-tanggal").on('apply.daterangepicker', function(e, picker){
            var val = $(this).val().split(' - ');
            var start = moment(val[0]).format("Y-MM-DD");
            var end = moment(val[1]).format("Y-MM-DD");
            
            var arrUrl = sumberData.split('?');

            if(arrUrl.length == 0)
                sumberData += "?start=" + start + "&end=" + end
            else
                sumberData = arrUrl[0] + "?start=" + start + "&end=" + end

            data.loadData(path + sumberData);
        })
    }
        
    data.loadData();
}