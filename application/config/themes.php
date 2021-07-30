
<?php
// Resource group "main"
$config['themes']['main']['js'] = array(
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/jquery/jquery-3.3.1.min.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/jquery/jquery.form.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/bootstrap/js/popper.min.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/bootstrap/js/bootstrap.min.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/bootstrap/js/bootstrap.bundle.min.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/bootstrap-notify/bootstrap-notify.min.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/jquery-validation/dist/jquery.validate.min.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/moment/moment.min.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/kamscore/js/Kamscore.js'),
    array('pos' => 'body:end', 'src' => base_url('public/assets/js/main.js')),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/kamscore/js/uihelper.js'),
);

$config['themes']['main']['css'] = array(
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/bootstrap/css/bootstrap.min.css')
);

// ICON ONLY
$config['themes']['icons']['css'] = array(
    array('pos' => 'head', 'src' => base_url('public/assets/vendor/fontawesome/css/all.min.css')),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor/dore/icon/iconsmind/style.css') ),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor/dore/icon/simple-line-icons/css/simple-line-icons.css'))
);

// Dore themes
$config['themes']['dore']['css'] = array(
    // array('pos' => 'head', 'src' => base_url('public/assets/vendor/dore/css/dore.light.green.css')),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/dore/css/main.css')
);
$config['themes']['dore']['js'] = array(
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/dore/js/script.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/dore/js/dore.script.js'),
);

// Landing Page

$config['themes']['landing']['css'] = array(
    array('pos' => 'head', 'src' => get_path('assets', 'icon/simple-line-icons/css/simple-line-icons.css')),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/bootstrap/css/bootstrap-stars.css'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/bootstrap/css/bootstrap.min.css'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/owl/css/owl-carousel.min.css'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/bootstrap/css/bootstrap-stars.css'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/dore/css/main.css'),
);

$config['themes']['landing']['js'] = array(
    array('pos' => 'body:end', 'src' => base_url('public/assets/vendor') . '/owl/js/owl-carousel.js'),
    array('pos' => 'body:end', 'src' => base_url('public/assets/vendor') . '/landing-page/js/headroom.min.js'),
    array('pos' => 'body:end', 'src' => base_url('public/assets/vendor') . '/landing-page/js/jQuery.headroom.js'),
    array('pos' => 'body:end', 'src' => base_url('public/assets/vendor') . '/landing-page/js/jquery.scrollTo.min.js'),
    array('pos' => 'body:end', 'src' => base_url('public/assets/vendor') . '/landing-page/js/jquery.autoellipsis.js'),
    array('pos' => 'body:end', 'src' => base_url('public/assets/vendor') . '/dore/js/landing.script.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/dore/js/script.js'),

);

// FORM
$config['themes']['form']['css'] = array(
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/select2/dist/css/select2.css'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/daterangepicker/daterangepicker.css'),
);

$config['themes']['form']['js'] = array(
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/select2/dist/js/select2.min.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/daterangepicker/daterangepicker.js'),
);



$config['themes']['datatables']['css'] = array(
    array('src' => base_url('public/assets/vendor') . '/datatables/dataTables.bootstrap4.min.css', 'pos' => 'head'),
    array('src' => base_url('public/assets/vendor') . '/datatables/datatables.responsive.bootstrap4.min.css', 'pos' => 'head'),
    // array('src' => base_url('public/assets/vendor') . '/datatables/jquery.dataTables.min.css', 'pos' => 'head'),
    array('src' => base_url('public/assets/vendor') . '/datatables/select.dataTables.css', 'pos' => 'head'),
);

$config['themes']['datatables']['js'] = array(
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/datatables/datatables.min.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/datatables/buttons.datatables.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/datatables/dt.select.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/datatables/btn.zip.js'),
    array('pos' => 'head', 'src' => 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/datatables/btn.pfs.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/datatables/btn.html-buttons.js'),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/datatables/btn.print.js'),
    // array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/datatables/jquery.dataTables.min.js'),
    // array('pos' => 'head', 'src' => base_url('public/assets/vendor') . '/datatables/dataTables.select.min.js'),
);