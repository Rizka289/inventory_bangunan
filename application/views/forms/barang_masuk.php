<div class="form-group">
    <label for="material">Nama Material</label>
    <select name="material" id="material" class='form-control'>
       <option value="" selected>-- Pilih --</option>
        <?php foreach($material as $m): ?>
        <option value="<?= $m->id_nama_material ?>"><?= $m->nama_material ?></option>
        <?php endforeach ?>
    </select>
</div>
<div class="form-group">
    <label for="merk">Merk Material</label>
    <select name="merk" id="merk" class='form-control'>
       <option value="" selected>-- Pilih --</option>
        <?php foreach($merk as $m): ?>
        <option value="<?= $m->id_merk_material ?>"><?= $m->merk_material ?></option>
        <?php endforeach ?>
    </select>
</div>
<div class="form-group">
    <label for="satuan">Satuan</label>
    <select name="satuan" id="satuan" class='form-control'>
       <option value="" selected>-- Pilih --</option>
        <?php foreach($satuan as $m): ?>
        <option value="<?= $m->id_uom ?>"><?= $m->uom ?></option>
        <?php endforeach ?>
    </select>
</div>
<div class="form-group">
    <label for="supplier">Supplier</label>
    <select name="supplier" id="supplier" class='form-control'>
       <option value="" selected>-- Pilih --</option>
        <?php foreach($supplier as $m): ?>
        <option value="<?= $m->id_supplier ?>"><?= $m->nama ?></option>
        <?php endforeach ?>
    </select>
</div>
<div class="form-group">
    <label for="jumlah">Jumlah</label>
    <input class="form-control" data-rule-digits="true" type="jumlah" required id="jumlah" name="jumlah">
</div>
<div class="form-group">
    <label for="harga">Harga</label>
    <input class="form-control" type="text" data-rule-digits="true" required id="harga" name="harga">
</div>
<div class="form-group">
    <label for="keterangan">Keterangan</label>
    <textarea class="form-control" name="keterangan" id="keterangan" cols="30" rows="5"></textarea>
</div>
<input type="hidden" name="id" id="id">