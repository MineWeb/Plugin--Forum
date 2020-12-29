<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__INSULTS') ?></h3>
                </div>
                <div class="card-body">
                    <blockquote>
                        <p><?= $Lang->get('FORUM__PHRASE__PAGE__WORD_1'); ?></p>
                    </blockquote>
                    <p><?= $Lang->get('FORUM__PHRASE__PAGE__WORD_2'); ?><br/>
                        <?= $Lang->get('FORUM__PHRASE__PAGE__WORD_3'); ?>
                    </p>
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
                                                        <i class="fa fa-font" aria-hidden="true"></i>
                                                    </div>
                                                    <input placeholder="Mot interdit" name="word" class="form-control" type="text" />
                                                </div>
                                            </td>
                                            <td>
                                                <div class="input-group">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-font" aria-hidden="true"></i>
                                                    </div>
                                                    <input placeholder="Remplacé par" name="replace" class="form-control" type="text" />
                                                </div>
                                            </td>
                                            <td><button class="btn btn-primary" type="submit"><?= $Lang->get('GLOBAL__ADD') ?></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-responsive dataTable">
                                <thead>
                                    <tr>
                                        <th><?= $Lang->get('FORUM__WORD'); ?></th>
                                        <th><?= $Lang->get('FORUM__WORD__REPLACE'); ?></th>
                                        <th><?= $Lang->get('FORUM__ACTION'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($words as $word): ?>
                                        <tr>
                                            <td><?= $word['Insult']['word']; ?></td>
                                            <td><?= $word['Insult']['replace']; ?></td>
                                            <td><a onclick="confirmDel('<?= $this->Html->url(array('controller' => 'forum', 'action' => 'delete/word/'.$word['Insult']['id'], 'admin' => true)) ?>')" class="btn btn-danger"><?= $Lang->get('GLOBAL__DELETE') ?></a></td>
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