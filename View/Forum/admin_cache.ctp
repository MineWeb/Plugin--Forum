<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-info"></i> <?= $Lang->get('FORUM__INFORMATION'); ?></h4>
                <?= $Lang->get('FORUM__CACHE__EXPLANATION'); ?><br />
            </div>
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__CACHE') ?></h3>
                </div>
                <div class="card-body">
                    <?php if($config): ?>
                        <a class="btn btn-large btn-block btn-danger" href="<?= $this->Html->url('/admin/forum/forum/cache/deactivate'); ?>"><?= $Lang->get('FORUM__DEACTIVATE__CACHE') ?></a>
                    <?php else: ?>
                        <a class="btn btn-large btn-block btn-success" href="<?= $this->Html->url('/admin/forum/forum/cache/active'); ?>"><?= $Lang->get('FORUM__ACTIVE__CACHE') ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered dataTable">
                        <thead>
                        <tr>
                            <th><?= $Lang->get('FORUM__TECHNICAL__NAME') ?></th>
                            <th><?= $Lang->get('GLOBAL__NAME') ?></th>
                            <th><?= $Lang->get('FORUM__VALUE'); ?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cache['count']['topicCatgory'] as $c): if($c['value']): ?>
                                <tr>
                                    <td><?= $c['key']; ?></td>
                                    <td><?= $c['title']; ?></td>
                                    <td><?= $c['value']; ?></td>
                                    <td>
                                        <a class="btn btn-large btn-block btn-danger" href="<?= $this->Html->url('/admin/forum/forum/cache/delete/'.$c['id']); ?>"><?= $Lang->get('GLOBAL__DELETE') ?></a>
                                    </td>
                                </tr>
                            <?php endif; endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-large btn-block btn-danger" href="<?= $this->Html->url('/admin/forum/forum/cache/empty'); ?>"><?= $Lang->get('FORUM__CACHE__EMPTY') ?></a>
                </div>
            </div>

        </div>
    </div>
</section>