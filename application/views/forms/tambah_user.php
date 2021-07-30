<div class="form-group">
    <label for="username">Role</label>
    <select class="form-control" name="role" id="role">
        <option value="admin">Admin</option>
        <option value="staff">Staff</option>
    </select>
</div>
<div class="form-group">
    <label for="username">Nama Lengkap</label>
    <input class="form-control" type="text" required id="username" name="username">
</div>
<div class="form-group">
    <label for="email">Email</label>
    <input class="form-control" type="email" required id="email" name="email">
</div>
<div class="form-group">
    <label for="hp">Nomor Hp</label>
    <input class="form-control" type="text" data-rule-digits="true" required id="hp" name="hp">
</div>
<div class="form-group">
    <label for="alamat">Alamat</label>
    <textarea class="form-control" name="alamat" id="alamat" cols="30" rows="5"></textarea>
</div>
<input type="hidden" name="id" id="id">