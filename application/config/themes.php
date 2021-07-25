
<?php
// Resource group "main"
$config['themes']['main']['js'] = array(
    array('pos' => 'head', 'src' => VENDOR_PATH . 'jquery/jquery-3.3.1.min.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'jquery/jquery.form.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'bootstrap/js/popper.min.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'bootstrap/js/bootstrap.min.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'bootstrap/js/bootstrap.bundle.min.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'bootstrap-notify/bootstrap-notify.min.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'jquery-validation/dist/jquery.validate.min.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'moment/moment.min.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'kamscore/js/Kamscore.js'),
    // array('pos' => 'body:end', 'src' => ASSETS_PATH . 'js/main.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'kamscore/js/uihelper.js'),
);

$config['themes']['main']['css'] = array(
    array('pos' => 'head', 'src' => VENDOR_PATH . 'bootstrap/css/bootstrap.min.css')
);

// ICON ONLY
$config['themes']['icons']['css'] = array(
    array('pos' => 'head', 'src' => base_url('public/assets/vendor/fontawesome/css/all.min.css')),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor/dore/icon/iconsmind/style.css') ),
    array('pos' => 'head', 'src' => base_url('public/assets/vendor/dore/icon/simple-line-icons/css/simple-line-icons.css'))
);

// Dore themes
$config['themes']['dore']['css'] = array(
    array('pos' => 'head', 'src' => 'https://cdn.kamscodelab.tech/dore/css/dore.light.green.min.css'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'dore/css/main.css')
);
$config['themes']['dore']['js'] = array(
    array('pos' => 'head', 'src' => VENDOR_PATH . 'dore/js/script.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'dore/js/dore.script.js'),
);

// Landing Page

$config['themes']['landing']['css'] = array(
    array('pos' => 'head', 'src' => get_path('assets', 'icon/simple-line-icons/css/simple-line-icons.css')),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'bootstrap/css/bootstrap-stars.css'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'bootstrap/css/bootstrap.min.css'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'owl/css/owl-carousel.min.css'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'bootstrap/css/bootstrap-stars.css'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'dore/css/main.css'),
);

$config['themes']['landing']['js'] = array(
    array('pos' => 'body:end', 'src' => VENDOR_PATH . 'owl/js/owl-carousel.js'),
    array('pos' => 'body:end', 'src' => VENDOR_PATH . 'landing-page/js/headroom.min.js'),
    array('pos' => 'body:end', 'src' => VENDOR_PATH . 'landing-page/js/jQuery.headroom.js'),
    array('pos' => 'body:end', 'src' => VENDOR_PATH . 'landing-page/js/jquery.scrollTo.min.js'),
    array('pos' => 'body:end', 'src' => VENDOR_PATH . 'landing-page/js/jquery.autoellipsis.js'),
    array('pos' => 'body:end', 'src' => VENDOR_PATH . 'dore/js/landing.script.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'dore/js/script.js'),

);

// FORM
$config['themes']['form']['css'] = array(
    array('pos' => 'head', 'src' => VENDOR_PATH . 'select2/dist/css/select2.css'),
);

$config['themes']['form']['js'] = array(
    array('pos' => 'head', 'src' => VENDOR_PATH . 'select2/dist/js/select2.min.js'),
);



$config['themes']['datatables']['css'] = array(
    array('src' => VENDOR_PATH . 'datatables/dataTables.bootstrap4.min.css', 'pos' => 'head'),
    array('src' => VENDOR_PATH . 'datatables/datatables.responsive.bootstrap4.min.css', 'pos' => 'head'),
    // array('src' => VENDOR_PATH . 'datatables/jquery.dataTables.min.css', 'pos' => 'head'),
    array('src' => VENDOR_PATH . 'datatables/select.dataTables.css', 'pos' => 'head'),
);

$config['themes']['datatables']['js'] = array(
    array('pos' => 'head', 'src' => VENDOR_PATH . 'datatables/datatables.min.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'datatables/buttons.datatables.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'datatables/dt.select.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'datatables/btn.zip.js'),
    // array('pos' => 'head', 'src' => VENDOR_PATH . 'datatables/btn.pdf.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'datatables/btn.pfs.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'datatables/btn.html-buttons.js'),
    array('pos' => 'head', 'src' => VENDOR_PATH . 'datatables/btn.print.js'),
    // array('pos' => 'head', 'src' => VENDOR_PATH . 'datatables/jquery.dataTables.min.js'),
    // array('pos' => 'head', 'src' => VENDOR_PATH . 'datatables/dataTables.select.min.js'),
);