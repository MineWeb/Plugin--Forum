<?=  $this->Html->css('Forum.bootstrap-colorpicker.min.css'); ?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__RANK') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="post" data-ajax="true">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="ajax-msg"></div>
                                        <div class="col-md-12">
                                            <table class="table table-responsive">
                                                <tbody>
                                                <tr>
                                                    <td>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-font" aria-hidden="true"></i>
                                                            </div>
                                                            <input placeholder="Nom du grade (Développeur)" name="rank" class="form-control" type="text" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                                            </div>
                                                            <input type="text" placeholder="#ffffff" class="form-control colorpicker-element" name="color" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-sort-numeric-asc" aria-hidden="true"></i>
                                                            </div>
                                                            <input placeholder="Position (1..99)" name="position" class="form-control" type="text" />
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <div class="input-group" style="width:90%">
                                                            <div class="input-group-addon">
                                                                <i class="fa fa-commenting" aria-hidden="true"></i>
                                                            </div>
                                                            <input placeholder="Description du gade" name="description" class="form-control" type="text" />
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-primary" type="submit"><?= $Lang->get('GLOBAL__ADD') ?></button>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-responsive dataTable">
                                <thead>
                                    <tr>
                                        <th><?= $Lang->get('FORUM__RANK__ALT'); ?></th>
                                        <th><?= $Lang->get('FORUM__DESCRIPTION'); ?></th>
                                        <th><?= $Lang->get('FORUM__COLOR'); ?></th>
                                        <th><?= $Lang->get('FORUM__POSITION'); ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($groups as $group): ?>
                                    <tr>
                                        <td><?= $group['Group']['group_name']; ?></td>
                                        <td><?= $group['Group']['group_description']; ?></td>
                                        <td><div style="background-color:#<?= $group['Group']['color']; ?>;height:16px;width:16px" ?></td>
                                        <td><?= $group['Group']['position']; ?></td>
                                        <td>
                                            <a href="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'edit/rank/'.$group['Group']['id'], 'admin' => true)) ?>" class="btn btn-primary"><?= $Lang->get('GLOBAL__EDIT'); ?></a>
                                            <a onclick="confirmDel('<?= $this->Html->url(array('controller' => 'forum', 'action' => 'delete/rank/'.$group['Group']['id'], 'admin' => true)) ?>')" class="btn btn-danger"><?= $Lang->get('GLOBAL__DELETE'); ?></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->Html->script('Forum.bootstrap-colorpicker.min.js'); ?>
<?= $this->Html->css('dataTables.bootstrap.css'); ?>
<?= $this->Html->script('jquery.dataTables.min.js') ?>
<?= $this->Html->script('dataTables.bootstrap.min.js') ?>
<script type="text/javascript">
    $(function(){
        $('.colorpicker-element').colorpicker();
        $('.dataTable').dataTable( {
            "paging": false
        } );
    });
</script>