<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if(!$stateExec): ?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-ban"></i> <?= $Lang->get('FORUM__WARNING!'); ?></h4>
                    <?= $Lang->get('FORUM__BACKUP__INCOMPATIBLE'); ?><br />
                    <span style="font-size: 11px"><?= $Lang->get('FORUM__BACKUP__INCOMPATIBLE__MORE'); ?></span>
                </div>
            <?php endif; ?>
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__BACKUPS') ?></h3>
                </div>
                <div class="card-body">
                    <a class="btn btn-large btn-block btn-success" href="<?= $this->Html->url('/admin/forum/forum/backup/new'); ?>"><?= $Lang->get('FORUM__CREATE__BACKUP') ?></a>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered dataTable">
                        <thead>
                        <tr>
                            <th><?= $Lang->get('GLOBAL__NAME') ?></th>
                            <th><?= $Lang->get('FORUM__CREATE'); ?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(!empty($lists)): ?>
                            <?php foreach ($lists as $list): ?>
                                <tr>
                                    <td><?= $list['name']; ?></td>
                                    <td><?= $list['date']; ?></td>
                                    <td class="right">
                                        <a onClick="confirmDel('<?= $this->Html->url(['controller' => 'forum', 'action' => 'backup', 'admin' => true, 'set', $list['name']]) ?>')" class="btn btn-primary"><?= $Lang->get('FORUM__APPLY') ?></a>
                                        <a onClick="confirmDel('<?= $this->Html->url(['controller' => 'forum', 'action' => 'backup', 'admin' => true, 'delete', $list['name']]) ?>')" class="btn btn-danger"><?= $Lang->get('GLOBAL__DELETE') ?></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-large btn-block btn-danger" href="<?= $this->Html->url('/admin/forum/forum/backup/deleteall'); ?>"><?= $Lang->get('FORUM__DELETEALL__BACKUP') ?></a>
                </div>
            </div>

        </div>
    </div>
</section>