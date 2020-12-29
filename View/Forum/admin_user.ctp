<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__USERS') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-responsive dataTable">
                                <thead>
                                    <tr>
                                        <th><?= $Lang->get('USER__USERNAME'); ?></th>
                                        <th><?= $Lang->get('FORUM__RANK__ALT'); ?></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= $user['User']['pseudo']; ?></td>
                                        <td>
                                            <?php if (!empty($user['User']['rank'])): ?>
                                                <?php foreach ($user['User']['rank'] as $key => $rank): ?>
                                                    <div style="background-color:#<?= $user['User']['color'][$key]['color']; ?>;color: #fff;padding: 2px 5px;margin-top: 5px;display: inline-block;"><?= $rank['group_name']; ?></div>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'edit/user/'.$user['User']['id'], 'admin' => true)) ?>" class="btn btn-primary"><?= $Lang->get('GLOBAL__EDIT'); ?></a>
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