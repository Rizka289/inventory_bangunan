<?php
// $data_head = isset($data_head) ? $data_head : null;
$data_head = array(
    'resource' => $resource,
    'extra_js' => isset($extra_js) ? $extra_js : null,
    'extra_css' => isset($extra_css) ? $extra_css : null,
    'includeTiny' => isset($includeTiny) ? $includeTiny : null,
    'loadingAnim' => isset($loadingAnim) ? $loadingAnim : null,
);
include_view('components/header/main', $data_head);
if (isset($navbar) && !is_array($navbar))
    include_view($navbar, $navbarConf);
if (isset($sidebar) && !is_array($sidebar))
    include_view($sidebar, $sidebarConf);
if (!isset($data_content))
    $data_content = null;

?>
<main>
    <div class="container-fluid" style="<?php echo isset($ada_bg) && $ada_bg ? "background: url('" . $bg_url . "') no-repeat" : null ?>">
        <div class="col-12">
            <h1><?php echo isset($pageName) ? $pageName : null ?></h1>
            <nav class="breadcrumb-container d-none d-sm-block d-lg-inline-block" aria-label="breadcrumb">
                <?php if (isset($subPageName) && !empty($subPageName)) : ?>
                    <ol class="breadcrumb pt-0">
                        <li class="breadcrumb-item">
                            <p><?php echo $subPageName ?></p>
                        </li>
                    </ol>
                <?php endif ?>
            </nav>
            <?php if (isset($pageName)) : ?>
                <div class="separator mb-5"></div>
            <?php endif ?>
        </div>
        <?php
        if (isset($content) && !empty($content)) {
            if (is_array($content)) {
                foreach ($content as $c) {
                    if (!empty($c))
                        include_view($c, $data_content);
        ?>
                    <br>
        <?php }
            } else
                include_view($content, $data_content);
        }
        ?>
    </div>
</main>
<?php
$footerParams = array(
    'resource' => $resource,
    'extra_js' => isset($extra_js) ? $extra_js : null,
    'extra_css' => isset($extra_css) ? $extra_css : null,
    'adaThemeSelector' => isset($adaThemeSelector) ? $adaThemeSelector : false,
);
include_view('components/footer/main', $footerParams);
