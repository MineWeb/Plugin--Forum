<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__PERMISSION') ?></h3>
                </div>
                <div class="card-body">
                    <form action="" method="post" data-ajax="true">
                        <input name="data[_Token][key]" value="<?= $csrfToken ?>" type="hidden">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?= $Lang->get('PERMISSIONS__LABEL') ?></th>
                                    <?php foreach ($groups as $group): ?>
                                        <th class="text-center"><?= $group['Group']['group_name']; ?></th>
                                    <?php endforeach; ?>
                                    <th class="text-center"><?= $Lang->get('FORUM__USER'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $db = ConnectionManager::getDataSource('default'); foreach ($permissions as $permission): ?>
                                <tr>
                                    <th><?= $Lang->get($permission['ForumPermission']['name'].'__PERM'); ?></th>
                                    <?php foreach ($groups as $group): ?>
                                        <th class="text-center">
                                            <?php $idGroup = $group['Group']['id']; ?>
                                            <?php $name = $permission['ForumPermission']['name']; ?>
                                            <?php $p = $db->query("SELECT * FROM forum__forum_permissions WHERE group_id ='$idGroup' AND name ='$name'")[0]['forum__forum_permissions'];
                                                if(is_null($p)){
                                                    $db->query("INSERT INTO forum__forum_permissions (group_id, name, value) VALUES ('$idGroup', '$name', 0)");
                                                } ?>
                                            <input type="checkbox" name="<?= $idGroup; ?>-<?= $p['id']; ?>" <?php if($p['value'] == 1) echo 'checked'; ?> />
                                        </th>
                                    <?php endforeach; ?>
                                    <?php $p = $db->query("SELECT * FROM forum__forum_permissions WHERE group_id = 99 AND name ='$name'")[0]['forum__forum_permissions']; ?>
                                    <th class="text-center"><input type="checkbox" name="99-<?= $p['id']; ?>" <?php if($p['value'] == 1) echo 'checked'; ?> /></th>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                        <button class="btn btn-primary pull-right" type="submit"><?= $Lang->get('GLOBAL__SUBMIT') ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>