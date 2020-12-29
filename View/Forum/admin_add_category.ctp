<?= $this->Html->css('Forum.forum-style.css') ?>
<?= $this->Html->css('Forum.select2.min.css'); ?>
<?= $this->Html->css('AdminLTE.min.css'); ?>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__ADD__CATEGORY') ?></h3>
                </div>
                <div class="card-body">
                    <form action="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'add_category', 'admin' => true)) ?>" method="post" data-ajax="true" data-redirect="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'forum', 'admin' => true)) ?>">
                        <div class="ajax-msg"></div>
                        <div class="form-group">
                            <label><?= $Lang->get('GLOBAL__NAME') ?></label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-font" aria-hidden="true"></i>
                                </div>
                                <input placeholder="Mineweb" name="name" class="form-control" type="text" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?= $Lang->get('FORUM__POSITION') ?></label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-sort-numeric-asc" aria-hidden="true"></i>
                                </div>
                                <input name="position" class="form-control" type="text" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label><?= $Lang->get('FORUM__PARENT') ?></label>
                            <select class="form-control e1" name="parent">
                                <?php foreach ($forums as $key => $forum) { ?>
                                    <option value="<?= $forum['Forum']['id']; ?>"><?= $Lang->get('FORUM__IN') ?> : <?= $forum['Forum']['forum_name'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group text-center">
                            <label class="radio-inline">
                                <input type="radio" name="ii_type" id="ii_type_icone"> Icone
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="ii_type" id="ii_type_image"> Image
                            </label>
                        </div>
                        <script type="text/javascript">
                            $("#ii_type_icone").click(function () {
                                var html = '<div class="form-group">';
                                html += '<div class="input-group">';
                                html += '<span class="input-group-addon">fa-</span>';
                                html += '<input name="image" class="form-control" type="text" placeholder="times" />';
                                html += '</div>';
                                html += '</div>';
                                $('#zone').html(html);
                            });
                            $("#ii_type_image").click(function () {
                                var html = '<div class="form-group">';
                                html += '<div class="input-group">';
                                html += '<div class="input-group-addon">';
                                html += '<i class="fa fa-link" aria-hidden="true"></i>';
                                html += '</div>';
                                html += '<input name="image" class="form-control" type="text" placeholder="https://bing.com/image.png" />';
                                html += '</div>';
                                html += '</div>';
                                $('#zone').html(html);
                            });
                        </script>
                        <div id="zone">
                        </div>

                        <div class="alert alert-warning alert-dismissible" style="margin:40px 0">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-warning"></i> <?= $Lang->get('FORUM__WARNING!'); ?></h4>
                            <?= $Lang->get('FORUM__PERMS__WARNING'); ?>
                        </div>

                        <div class="form-group">
                            <label> <?= $Lang->get('FORUM__SEEBY') ?></label>
                            <?php if(!empty($ranks)): ?>
                                <?php foreach($ranks as $key => $rank): ?>
                                    <div style="background-color: #<?= $rank['Group']['color']; ?>" class="forum-badgerank"><input type="checkbox" class="middle" name="<?= $rank['Group']['id']; ?>" /> <?= $rank['Group']['group_name']; ?></div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <div>
                                <?= $Lang->get('FORUM__ADMIN__TIPS_1'); ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <input name="lock" type="checkbox" />
                            <label> <?= $Lang->get('FORUM__LOCK__CATEGORY') ?></label>
                        </div>
                        <div class="form-group">
                            <input name="automatic_lock" type="checkbox"  />
                            <label> <?= $Lang->get('FORUM__AUTOLOCK__CATEGORY') ?></label>
                        </div>
                        <div class="pull-right">
                            <a href="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'index', 'admin' => true)) ?>" class="btn btn-default"><?= $Lang->get('GLOBAL__CANCEL') ?></a>
                            <button class="btn btn-primary" type="submit"><?= $Lang->get('GLOBAL__SUBMIT') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<?= $this->Html->script('Forum.select2.min.js'); ?>
<script type="text/javascript">
    $(document).ready(function(){$(".e1").select2();});
</script>