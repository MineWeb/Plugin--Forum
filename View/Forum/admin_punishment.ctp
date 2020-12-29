<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__PUNISHMENT') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="post" data-ajax="true">
                                <div class="ajax-msg"></div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-responsive">
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-user" aria-hidden="true"></i>
                                                        </div>
                                                        <input placeholder="Pseudo" name="pseudo" class="form-control" type="text" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-commenting" aria-hidden="true"></i>
                                                        </div>
                                                        <input placeholder="Raison" name="reason" class="form-control" type="text" />
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar" aria-hidden="true"></i>
                                                        </div>
                                                        <input name="date" class="form-control" placeholder="<?= date('Y-m-d', strtotime('+1 day')); ?>" value="<?= date('Y-m-d', strtotime('+1 day')); ?>" type="text" />
                                                    </div>
                                                </td>
                                                <td><button class="btn btn-primary" type="submit"><?= $Lang->get('GLOBAL__ADD') ?></button></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </form>
                            <table class="table table-responsive dataTable">
                                <thead>
                                    <tr>
                                        <th><?= $Lang->get('FORUM__BY'); ?></th>
                                        <th><?= $Lang->get('USER__USERNAME'); ?></th>
                                        <th><?= $Lang->get('FORUM__DATE__ENDBAN'); ?></th>
                                        <th><?= $Lang->get('FORUM__REASON'); ?></th>
                                        <th><?= $Lang->get('FORUM__ACTION'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($bannis as $banni): ?>
                                    <tr>
                                        <td><?= $banni['Punishment']['user']; ?></td>
                                        <td><?= $banni['Punishment']['userto']; ?></td>
                                        <td><?= $banni['Punishment']['date']; ?></td>
                                        <td><?= $banni['Punishment']['reason']; ?></td>
                                        <td>
                                            <a onClick="confirmDel('<?= $this->Html->url(array('controller' => 'forum', 'action' => 'delete/punishment/'.$banni['Punishment']['id'], 'admin' => true)) ?>')" class="btn btn-danger"><?= $Lang->get('GLOBAL__DELETE') ?></a>
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