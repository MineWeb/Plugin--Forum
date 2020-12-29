<?= $this->Html->css('Forum.forum-style.css') ?>
<?=  $this->Html->css('Forum.bootstrap-colorpicker.min.css'); ?>
<?= $this->Html->css('Forum.select2.min.css'); ?>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__RANK') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                <div class="col-md-12">
                    <form action="" method="post" data-ajax="true">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="ajax-msg"></div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-font" aria-hidden="true"></i>
                                        </div>
                                        <input placeholder="Nom du tag" name="label" class="form-control" type="text" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-font-awesome" aria-hidden="true"></i>
                                        </div>
                                        <input placeholder="icone (exclamation-circle)" name="icon" class="form-control" type="text" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                        </div>
                                        <input type="text" placeholder="#ffffff" class="form-control colorpicker-element" name="color" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-sort-numeric-asc" aria-hidden="true"></i>
                                        </div>
                                        <input placeholder="Position (1..99)" name="position" class="form-control" type="text" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label><?= $Lang->get('FORUM__TAGS__FREE') ?> (<?= $Lang->get('FORUM_TAG_PUBLIC') ?> )</label>
                                    <div class="input-group">
                                        <div class="input-group-addon">
                                            <i class="fa fa-sort-numeric-asc" aria-hidden="true"></i>
                                        </div>
                                        <select class="e1 form-control" name="free">
                                            <option value="0">Non</option>
                                            <option value="1">Oui</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group ranks" style="display:none">
                                    <label> <?= $Lang->get('FORUM__USEDBY') ?></label>
                                    <?php if(!empty($ranks)): ?>
                                        <?php foreach($ranks as $key => $rank): ?>
                                            <div style="background-color: #<?= $rank['Group']['color']; ?>" class="forum-badgerank"><input type="checkbox" class="middle" name="<?= $rank['Group']['id']; ?>" /> <?= $rank['Group']['group_name']; ?></div>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>

                                <button class="btn btn-primary pull-right" type="submit"><?= $Lang->get('GLOBAL__ADD') ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
                </div>
            </div>

            <div class="card card-primary">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__RANK') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-responsive dataTable">
                                <thead>
                                <tr>
                                    <th><?= $Lang->get('FORUM__LABEL'); ?></th>
                                    <th><?= $Lang->get('FORUM__ICON'); ?></th>
                                    <th><?= $Lang->get('FORUM__COLOR'); ?></th>
                                    <th><?= $Lang->get('FORUM__POSITION'); ?></th>
                                    <th><?= $Lang->get('FORUM__RENDERING'); ?></th>
                                    <th><?= $Lang->get('FORUM__USEDBY'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($tags as $tag): ?>
                                    <tr>
                                        <td><?= $tag['Tag']['name']; ?></td>
                                        <td><?= $tag['Tag']['icon']; ?></td>
                                        <td>#<?= $tag['Tag']['color']; ?></td>
                                        <td><?= $tag['Tag']['position']; ?></td>
                                        <td>
                                            <span style="padding:1px 5px;color:#ffffff;font-weight:600;background-color:#<?= $tag['Tag']['color']; ?>">
                                                <?php if(!empty($tag['Tag']['icon'])): ?>
                                                    <i class="fa fa-<?= $tag['Tag']['icon']; ?>" aria-hidden="true"></i>
                                                <?php endif; ?>
                                                 <?= $tag['Tag']['name']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#myModal<?= $tag['Tag']['id']; ?>">
                                                <?= $Lang->get('FORUM__SHOW__RANKS'); ?>
                                            </button>

                                            <div class="modal fade" id="myModal<?= $tag['Tag']['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body">
                                                            <?php $active = unserialize($tag['Tag']['used']);
                                                                  foreach($ranks as $key => $rank): ?>
                                                                    <div style="background-color: #<?= $rank['Group']['color']; ?>" class="forum-badgerank"><input <?php if($active[$rank['Group']['id']]) echo 'checked'; ?> type="checkbox" class="middle" name="<?= $rank['Group']['id']; ?>" /> <?= $rank['Group']['group_name']; ?></div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal"><?= $Lang->get('GLOBAL__CLOSE'); ?></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a onclick="confirmDel('<?= $this->Html->url(array('controller' => 'forum', 'action' => 'delete/tag/'.$tag['Tag']['id'], 'admin' => true)); ?>')" class="btn btn-danger"><?= $Lang->get('GLOBAL__DELETE'); ?></a>
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
<?= $this->Html->script('Forum.select2.min.js'); ?>
<?= $this->Html->script('Forum.bootstrap-colorpicker.min.js'); ?>

<?= $this->Html->css('dataTables.bootstrap.css'); ?>
<?= $this->Html->script('jquery.dataTables.min.js') ?>
<?= $this->Html->script('dataTables.bootstrap.min.js') ?>

<script type="text/javascript">$(function(){$('.colorpicker-element').colorpicker();});$(document).ready(function(){$(".e1").select2();});$('body').on('change', '.e1', function(){if(this.value == 1){$('.ranks').css('display', 'block');}else{$('.ranks').css('display', 'none');}});</script>