<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body ">
                <h1 class="card-title"><?php echo $dtTitle ?></h1>
                <?php if (isset($dtAlert)) : ?>
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <strong><?php echo $alert ?>: </strong> <span id="saldo-sebelum"><?php echo $dtAlert ?></span>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                <?php endif ?>
                <div class="toolbar">
                    <?php
                    $dataToolbar = isset($dataToolbar) ? $dataToolbar : array();
                    if (isset($toolbar))
                        include_view($toolbar, $dataToolbar);
                    ?>

                    
                </div>
                <div class="table-responsive">
                    <table id="<?php echo $dtid ?>" data-export-title="<?php echo isset($exportTitle) ? $exportTitle : null ?>">
                        <thead>
                            <tr>
                                <?php foreach ($head as $h) : ?>
                                    <th class="<?php echo isset($hide) && !empty($hide) && in_array($h, $hide) ? 'd-none' : null ?>"><?php echo $h ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</div>