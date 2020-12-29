<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__TOPIC__ALT') ?></h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered dataTable">
                        <thead>
                        <tr>
                            <th><?= $Lang->get('GLOBAL__NAME') ?></th>
                            <th><?= $Lang->get('FORUM__AUTHOR'); ?></th>
                            <th><?= $Lang->get('FORUM__CREATE'); ?></th>
                            <th class="right"><?= $Lang->get('GLOBAL__ACTIONS') ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($topics as $topic) { ?>
                            <tr>
                                <td><?= h($topic['Topic']['name']); ?></td>
                                <td><?= $topic['Topic']['author']; ?></td>
                                <td><?= $topic['Topic']['date']; ?></td>
                                <td class="right">
                                    <a target="_blank" href="<?= $this->Html->url($topic['Topic']['href'].'/'); ?>" class="btn btn-primary"><i class="fas fa-external-link-alt"></i></a>
                                    <a data-toggle="modal" data-target="#modal-<?= $topic['Topic']['id_topic']; ?>" class="btn btn-info"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                    <a onClick="confirmDel('<?= $this->Html->url(array('controller' => 'forum', 'action' => 'delete/topic/'.$topic['Topic']['id_topic'], 'admin' => true)) ?>')" class="btn btn-danger"><?= $Lang->get('GLOBAL__DELETE') ?></a>
                                </td>
                            </tr>
                            <div class="modal fade" id="modal-<?= $topic['Topic']['id_topic']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"><div class="modal-dialog" role="document"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button><h4 class="modal-title"><?= h($topic['Topic']['name']); ?></h4></div><div class="modal-body"><?= $topic['Topic']['content']; ?></div><div class="modal-footer"><button type="button" class="btn btn-default" data-dismiss="modal">Close</button></div></div></div></div>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>