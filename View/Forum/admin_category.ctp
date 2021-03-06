<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__CATEGORY') ?> &nbsp;&nbsp;<a href="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'add_category', 'admin' => true)) ?>" class="btn btn-success"><?= $Lang->get('GLOBAL__ADD') ?></a></h3>
                </div>
                <div class="card-body">

                    <table class="table table-bordered dataTable">
                        <thead>
                            <tr>
                                <th><?= $Lang->get('GLOBAL__NAME') ?></th>
                                <th><?= $Lang->get('FORUM__FORUM__PARENT'); ?></th>
                                <th class="right"><?= $Lang->get('GLOBAL__ACTIONS') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($forums as $forum) { ?>
                            <tr>
                                <td><?= $forum['Forum']['forum_name'] ?></td>
                                <td><?= $forum['Forum']['parent']; ?></td>
                                <td class="right">
                                    <a href="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'edit/category/'.$forum['Forum']['id'], 'admin' => true)) ?>" class="btn btn-info"><?= $Lang->get('GLOBAL__EDIT') ?></a>
                                    <a onClick="confirmDel('<?= $this->Html->url(array('controller' => 'forum', 'action' => 'delete/category/'.$forum['Forum']['id'], 'admin' => true)) ?>')" class="btn btn-danger"><?= $Lang->get('GLOBAL__DELETE') ?></a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</section>