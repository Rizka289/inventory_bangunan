async (e, dt, node, config) => {
    var custom = <?php isset($custom) ? var_export($custom) : var_export(false) ?>; 
    $(node).prop('disabled', true);
    var data = instance.dataTables[config.data.tableid].rows({ selected: true }).data().toArray()
    if (data.length == 0) {
        alert('Pilih Data yang ingin dipindahkan');
        $(node).prop('disabled', false);
        return;
    }
    var res = confirm('Yakin Ingin Memindahkan Data .?');
    if (!res) {
        $(node).prop('disabled', false);
        return;
    }
    $('#pros-loading').show();
  
    var inputs = [];
    var barang = data.map(d => [d[index_id], d[2] + ' ' + d[3] + '(' + d[4] + ')', d[7]]);
    if(custom){
        var merk = <?= isset($merk) ? $merk : '3' ?>;
        var uom = <?= isset($uom) ? $uom : '4' ?>;
        var material = <?= isset($material) ? $material : '2' ?>;
        var id = <?= isset($id) ? $id : 'null' ?>;
        var total = <?= isset($total) ? $total : '7' ?>;

        if(id == null)
            id = index_id;
            
        barang = data.map(d => [d[id], d[material] + ' ' + d[merk] + '(' + d[uom] + ')', d[total]]);
    }
    barang.forEach(b => {
        inputs.push({
            label: 'Jumlah untuk ' + b[1], type: 'text', id: 'jumlah-' + b[0], attr: 'min=1 max=' + b[2] + ' required data-rule-number=true autocomplete=off', name: 'jumlah[' + b[0] + ']'
        });
        inputs.push({
            label: 'Keterangan untuk ' + b[1], type: 'textarea', id: 'keterangan-' + b[0], attr: 'autocomplete=off', name: 'keterangan[' + b[0] + ']'
        });
        inputs.push({
            type: 'hidden', value: b[0], name: 'ids[]'
        });

    })

    var modalConf = {
        pos: 'def',
        size: 'modal-md',
        submit: 'inventory/kestok',
    };

    await tambahHandler(config.data, false, null, inputs, modalConf);
    $('#pros-loading').hide();
    $(node).prop('disabled', false);
}