<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__HISTORY') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="post" data-ajax="true">
                                <input type="hidden" name="drop" value="42" />
                                <div class="ajax-msg"></div>
                                <div class="pull-left">
                                    <button class="btn btn-danger" type="submit"><?= $Lang->get('FORUM__DROP') ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-responsive dataTable">
                                <thead>
                                    <tr>
                                        <th><?= $Lang->get('USER__USERNAME'); ?></th>
                                        <th><?= $Lang->get('FORUM__DATE__REPORT'); ?></th>
                                        <th><?= $Lang->get('FORUM__IP'); ?></th>
                                        <th><?= $Lang->get('FORUM__CATEGORY__ALT'); ?></th>
                                        <th><?= $Lang->get('FORUM__ACTION'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($history as $h): ?>
                                    <tr>
                                        <td><?= $h['Historie']['pseudo']; ?></td>
                                        <td><?= $h['Historie']['date']; ?></td>
                                        <td><?= $h['Historie']['ip']; ?></td>
                                        <td><?= $h['Historie']['category']; ?></td>
                                        <td><?= $h['Historie']['action']; ?></td>
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