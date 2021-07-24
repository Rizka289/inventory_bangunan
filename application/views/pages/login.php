<div class="container-fluid" style="background-image: url('<?php echo base_url('public/img/sideImage/login.svg') ?>'); background-color: rgb(244, 246, 248); background-position: 80% center; background-repeat: no-repeat;">
    <div class="row">
        <div class="" style="background-color: white; width: 40%; max-height: 100%">
            <div style="padding:8% 15%;"><img src="<?php echo base_url('public/img/logo/logo.svg') ?>">
                <div>
                    <h3 style="color: rgba(232, 103, 37, 0.8); font-size: 32px;">Login</h3>
                    <p style="color: rgba(232, 103, 37, 0.8); font-size: 20px;">Masuk untuk melanjutkan</p>
                </div>
                <div style="padding: 0;" class="alert">
                    <p style="display: none;" id="alert_danger" class="p-2 alert-danger">ok</p>
                    <p style="display: none;" id="alert_succes" class="p-2 alert-succes"></p>
                </div>
                <form id="<?= $formid ?>" method="POST" action="<?php echo base_url('ws/login') ?>" style="margin-top: 13%;">
                    <div class="form-group">
                        <label class="control-label">Username atau Email</label>
                        <input autocomplete="off" placeholder="Masukkan Email atau Username" name="user" required type="text" id="user" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Password</label>
                        <input autocomplete="off" placeholder="Masukkan Password" required name="pass" type="password" id="password" class="form-control">
                    </div>
                    <div class="form-group">
                        <button id="btn-login" type="submit" class="btn text-white" style="background-color: rgb(6, 146, 65); width:100%">Masuk</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>