<?php $user = sessiondata('login'); ?>
<div class="container emp-profile">
    <div id="alert_danger" style="display: none;" class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong></strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <form id="form-edit-user" enctype="multipart/form-data" action="<?php echo base_url('users/update_profile') ?>" method="post">
        <input type="hidden" name="id" value="<?php echo $user['id_user'] ?>">
        <input type="hidden" name="_mode" value="edit-pr">
        <div class="row">
            <div class="col-md-4">
                <div id="pp" class="profile-img">
                    <a data-lightbox="pp" data-title="Tekan tombol esc untuk keluar" href="<?php echo base_url('public/img/profile/' . $user['user_avatar']) ?>"><img style="cursor: pointer;" id="pp-preview" src="<?php echo base_url('public/img/profile/' . $user['user_avatar']) ?>" alt="Photo profile" /></a>
                    <label style="cursor: pointer;" id="file" class="file btn btn-lg btn-primary">
                        Change Photo (Max. 5MB)
                        <input accept="image/*" id="n-pp" type="file" name="pp" />
                    </label>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <label>Nama Lengkap</label>
                    </div>
                    <div class="col-md-6">
                        <input class="no-border" type="text" name="username" value="<?php echo $user['user_name'] ?>" id="username">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <label>Email</label>
                    </div>
                    <div class="col-md-6">
                        <input class="no-border" type="text" name="email" value="<?php echo $user['user_email'] ?>" id="email">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <label>Role</label>
                    </div>
                    <div class="col-md-6">
                        <input class="no-border" type="text" name="role" readonly value="<?php echo $user['user_role'] ?>" id="role">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <label>Password</label>
                    </div>
                    <div class="col-md-6">
                        <input autocomplete="off" class="form-control" type="password" name="password" id="pass" placeholder="Isi jika ingin merubah password">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <label>Alamat</label>
                    </div>
                    <div class="col-md-6">
                        <textarea class="no-border" name="alamat" id="alamat" cols="20" rows="3"><?php echo $user['user_address'] ?></textarea>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <label>No Hp</label>
                    </div>
                    <div class="col-md-6">
                        <input class="no-border" type="text" name="hp" value="<?php echo $user['user_phone'] ?>" id="hp">
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <label>Registrar</label>
                    </div>
                    <div class="col-md-6">
                        <input class="no-border" type="text" name="registrar" value="<?php echo $user['registrar'] ?>" id="registrar" readonly>
                    </div>
                </div>
                <hr>
            </div>
            <div class="form-group col-12">
                <button id="btn-submit" type="submit" style="float: right;" class="btn btn-outline-primary btn-sm">Update Profile</button>
            </div>
    </form>
</div>