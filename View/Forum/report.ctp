<?= $this->Html->css('Forum.forum-style.css?'.rand(1, 1000000)) ?>
<?= $this->Html->css('Forum.custom.css') ?>

<div class="background-forum">
    <div class="<?= $theme; ?> marge">
    <?php if(!empty($alertforum['update'])) echo $alertforum['update']; ?>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__MSGREPORT') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-responsive dataTable">
                                <thead>
                                    <tr>
                                        <th><?= $Lang->get('FORUM__BY'); ?></th>
                                        <th><?= $Lang->get('FORUM__DATE__REPORT'); ?></th>
                                        <th><?= $Lang->get('FORUM__REASON'); ?></th>
                                        <th><?= $Lang->get('FORUM__CONTENT'); ?></th>
                                        <th><?= $Lang->get('FORUM__ACTION'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($msgreports as $msgreport): ?>
                                    <tr>
                                        <td><?= $msgreport['MsgReport']['user']; ?></td>
                                        <td><?= $msgreport['MsgReport']['date']; ?></td>
                                        <td><?= h($msgreport['MsgReport']['reason']); ?></td>
                                        <td><?= h($msgreport['MsgReport']['content']); ?></td>
                                        <td>
                                            <a  href="<?= $this->Html->url($msgreport['MsgReport']['href'].'/#'.$msgreport['MsgReport']['id_msg']); ?>" class="btn btn-info"><i class="fa fa-external-link" aria-hidden="true"></i></a>
                                            <form style="display: inline-block" action="" method="post">
                                                <input name="data[_Token][key]" value="<?= $csrfToken ?>" type="hidden" />
                                                <input type="hidden" name="id" value="<?= $msgreport['MsgReport']['id']; ?>" />
                                                <button type="submit" class="btn btn-danger"><?= $Lang->get('GLOBAL__DELETE') ?></button>
                                            </form>
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
</div>
</div>