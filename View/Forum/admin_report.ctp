<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__MSGREPORT') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <blockquote>
                                <?= $Lang->get('FORUM__PHRASE__PAGE__REPORT_1'); ?><a href="<?= $this->Html->url('/forum/report'); ?>"><?= $Lang->get('FORUM__HERE'); ?></a>
                            </blockquote>
                            <table class="table table-responsive dataTable">
                                <thead>
                                    <tr>
                                        <th><?= $Lang->get('FORUM__BY'); ?></th>
                                        <th><?= $Lang->get('FORUM__DATE__REPORT'); ?></th>
                                        <th><?= $Lang->get('FORUM__CONTENT'); ?></th>
                                        <th><?= $Lang->get('FORUM__ACTION'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($msgreports as $msgreport): ?>
                                    <tr>
                                        <td><?= $msgreport['MsgReport']['user']; ?></td>
                                        <td><?= $msgreport['MsgReport']['date']; ?></td>
                                        <td><?= $msgreport['MsgReport']['reason']; ?></td>
                                        <td><?= $msgreport['MsgReport']['content']; ?></td>
                                        <td>
                                            <a href="<?= $this->Html->url($msgreport['MsgReport']['href'].'/#'.$msgreport['MsgReport']['id_msg']); ?>" class="btn btn-info"><i class="fa fa-external-link" aria-hidden="true"></i></a>
                                            <a onClick="confirmDel('<?= $this->Html->url(array('controller' => 'forum', 'action' => 'delete/report/'.$msgreport['MsgReport']['id'], 'admin' => true)) ?>')" class="btn btn-danger"><?= $Lang->get('GLOBAL__DELETE') ?></a>
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