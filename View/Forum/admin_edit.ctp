<?= $this->Html->css('Forum.forum-style.css') ?>
<?= $this->Html->css('Forum.billboard.min.css') ?>
<?= $this->Html->css('Forum.font-awesome.min.css') ?>
<?= $this->Html->css('Forum.select2.min.css'); ?>
<?= $this->Html->css('AdminLTE.min.css'); ?>

<?= $this->Html->script('Forum.d3.v4.min.js'); ?>
<?= $this->Html->script('Forum.billboard.min.js'); ?>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <?php if($type == 'forum'): ?>
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title"><?= $Lang->get('FORUM__EDIT__FORUM') ?></h3>
                    </div>
                    <div class="card-body">
                        <script type="text/javascript">
                            function icone() {
                                var html = '<div class="form-group">';
                                html += '<div class="input-group">';
                                html += '<span class="input-group-addon">fa-</span>';
                                html += '<input value="<?= $datas["forum_image"]; ?>" name="image" class="form-control" type="text" placeholder="times" />';
                                html += '</div>';
                                html += '</div>';
                                $('#zone').html(html);
                            }
                            function image() {
                                var html = '<div class="form-group">';
                                html += '<div class="input-group">';
                                html += '<div class="input-group-addon">';
                                html += '<i class="fa fa-link" aria-hidden="true"></i>';
                                html += '</div>';
                                html += '<input value="<?= $datas["forum_image"]; ?>" name="image" class="form-control" type="text" placeholder="https://bing.com/image.png" />';
                                html += '</div>';
                                html += '</div>';
                                $('#zone').html(html);
                            }
                        </script>
                        <form action="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'admin_edit', 'admin' => true)) ?>" method="post" data-ajax="true" data-redirect="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'admin_edit', 'admin' => true)) ?>">
                            <div class="ajax-msg"></div>
                            <div class="form-group">
                                <label><?= $Lang->get('GLOBAL__NAME') ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-font" aria-hidden="true"></i>
                                    </div>
                                    <input value="<?= $datas['forum_name']; ?>" name="name" class="form-control" type="text" />
                                </div>
                                <input value="<?= $datas['id']; ?>" name="id" type="hidden" />
                            </div>

                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__POSITION') ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-sort-numeric-asc" aria-hidden="true"></i>
                                    </div>
                                    <select class="form-control e1" name="position">
                                        <option value="1"><?= $Lang->get('FORUM__FIRST__POSITION') ?></option>
                                        <?php foreach ($forums as $key => $forum) { ?>
                                            <option value="<?= $key+2 ?>" <?php if($key+2 == $datas['position']) echo 'selected'; ?> <?php if($key+1 == $datas['position']) echo 'disabled'; ?>><?= $Lang->get('FORUM__AFTER') ?> : <?= $forum['Forum']['forum_name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__DESCRIPTION') ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-font" aria-hidden="true"></i>
                                    </div>
                                    <input placeholder="Participez aux développement du serveur Minecraft" name="description" class="form-control" value="<?= $datas['forum_description']; ?>" type="text" />
                                </div>
                            </div>

                            <div class="form-group text-center">
                                <label class="radio-inline">
                                    <?php if(!filter_var($datas['forum_image'], FILTER_VALIDATE_URL)): ?>
                                    <input checked type="radio" name="ii_type" id="ii_type_icone"> Icone
                                        <script type="text/javascript">
                                            $(document).ready(function() {
                                                icone();
                                            });
                                        </script>
                                    <?php else: ?>
                                    <input type="radio" name="ii_type" id="ii_type_icone"> Icone
                                    <?php endif; ?>
                                </label>
                                <label class="radio-inline">
                                    <?php if(filter_var($datas['forum_image'], FILTER_VALIDATE_URL)): ?>
                                    <input checked type="radio" name="ii_type" id="ii_type_image"> Image
                                        <script type="text/javascript">
                                            $(document).ready(function() {
                                                image();
                                            });
                                        </script>
                                    <?php else: ?>
                                    <input type="radio" name="ii_type" id="ii_type_image"> Image
                                    <?php endif; ?>
                                </label>
                            </div>
                            <script type="text/javascript">
                                $("#ii_type_icone").click(function () {
                                    icone();
                                });
                                $("#ii_type_image").click(function () {
                                    image();
                                });
                            </script>

                            <div id="zone">
                            </div>

                            <div class="form-group">
                                <label> <?= $Lang->get('FORUM__SEEBY') ?></label>
                                <?php if(!empty($ranks)): ?>
                                    <?php foreach($ranks as $key => $rank): ?>
                                        <div style="background-color: #<?= $rank['Group']['color']; ?>" class="forum-badgerank"><input <?php if(isset($individual[$rank['Group']['id']]) && $individual[$rank['Group']['id']] == 1) echo 'checked'; ?> type="checkbox" class="middle" name="<?= $rank['Group']['id']; ?>" /> <?= $rank['Group']['group_name']; ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <div>
                                    <?= $Lang->get('FORUM__ADMIN__TIPS_1'); ?>
                                </div>
                            </div>

                            <div class="pull-right">
                                <a href="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'index', 'admin' => true)) ?>" class="btn btn-default"><?= $Lang->get('GLOBAL__CANCEL') ?></a>
                                <button class="btn btn-primary" type="submit"><?= $Lang->get('GLOBAL__SUBMIT') ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php elseif ($type == 'category'): ?>
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title"><?= $Lang->get('FORUM__EDIT__CATEGORY') ?></h3>
                    </div>
                    <div class="card-body">
                        <script type="text/javascript">
                            function icone() {
                                var html = '<div class="form-group">';
                                html += '<div class="input-group">';
                                html += '<span class="input-group-addon">fa-</span>';
                                html += '<input value="<?= $datas["Forum"]["forum_image"]; ?>" name="image" class="form-control" type="text" placeholder="times" />';
                                html += '</div>';
                                html += '</div>';
                                $('#zone').html(html);
                            }
                            function image() {
                                var html = '<div class="form-group">';
                                html += '<div class="input-group">';
                                html += '<div class="input-group-addon">';
                                html += '<i class="fa fa-link" aria-hidden="true"></i>';
                                html += '</div>';
                                html += '<input value="<?= $datas["Forum"]["forum_image"]; ?>" name="image" class="form-control" type="text" placeholder="https://bing.com/image.png" />';
                                html += '</div>';
                                html += '</div>';
                                $('#zone').html(html);
                            }
                        </script>
                        <form action="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'admin_edit', 'admin' => true)) ?>" method="post" data-ajax="true" data-redirect="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'admin_edit', 'admin' => true)) ?>">
                            <div class="ajax-msg"></div>
                            <div class="form-group">
                                <label><?= $Lang->get('GLOBAL__NAME') ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-font" aria-hidden="true"></i>
                                    </div>
                                    <input value="<?= $datas['Forum']['forum_name']; ?>" name="name_category" class="form-control" type="text" />
                                </div>
                                <input value="<?= $datas['Forum']['id']; ?>" name="id" type="hidden" />
                            </div>
                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__POSITION') ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-sort-numeric-asc" aria-hidden="true"></i>
                                    </div>
                                    <input value="<?= $datas['Forum']['position']; ?>" name="position" class="form-control" type="text" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__PARENT') ?></label>
                                <select class="form-control e1" name="parent">
                                    <?php foreach ($forums as $key => $forum) { ?>
                                        <option value="<?= $forum['Forum']['id']; ?>" <?php if($forum['Forum']['id'] == $datas['Forum']['id_parent']) echo 'selected'; ?>><?= $Lang->get('FORUM__IN') ?> : <?= $forum['Forum']['forum_name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label><?= $Lang->get('GLOBAL__IMAGE') ?> <a style="font-size: 9px" target="_blank" href="https://fontawesome.com/v4.7.0/icons/"><i class="fa fa-question-circle" aria-hidden="true"></i></a></label>
                            </div>
                            <div class="form-group text-center">
                                <label class="radio-inline">
                                    <?php if(!filter_var($datas["Forum"]["forum_image"], FILTER_VALIDATE_URL)): ?>
                                        <input checked type="radio" name="ii_type" id="ii_type_icone"> Icone
                                        <script type="text/javascript">
                                            $(document).ready(function() {
                                                icone();
                                            });
                                        </script>
                                    <?php else: ?>
                                        <input type="radio" name="ii_type" id="ii_type_icone"> Icone
                                    <?php endif; ?>
                                </label>
                                <label class="radio-inline">
                                    <?php if(filter_var($datas["Forum"]["forum_image"], FILTER_VALIDATE_URL)): ?>
                                        <input checked type="radio" name="ii_type" id="ii_type_image"> Image
                                        <script type="text/javascript">
                                            $(document).ready(function() {
                                                image();
                                            });
                                        </script>
                                    <?php else: ?>
                                        <input type="radio" name="ii_type" id="ii_type_image"> Image
                                    <?php endif; ?>
                                </label>
                            </div>
                            <script type="text/javascript">
                                $("#ii_type_icone").click(function () {
                                    icone();
                                });
                                $("#ii_type_image").click(function () {
                                    image();
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
                                        <div style="background-color: #<?= $rank['Group']['color']; ?>" class="forum-badgerank"><input <?php if(isset($individual[$rank['Group']['id']]) && $individual[$rank['Group']['id']] == 1) echo 'checked'; ?> type="checkbox" class="middle" name="<?= $rank['Group']['id']; ?>" /> <?= $rank['Group']['group_name']; ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                                <div>
                                    <?= $Lang->get('FORUM__ADMIN__TIPS_1'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <input name="lock" id="lock" type="checkbox" <?= ($datas['Forum']['lock']) ? 'checked' : ''; ?>  />
                                <label for="lock"> <?= $Lang->get('FORUM__LOCK__CATEGORY') ?></label>
                            </div>
                            <div class="form-group">
                                <input name="automatic_lock" id="automatic_lock" type="checkbox" <?= ($datas['Forum']['automatic_lock']) ? 'checked' : ''; ?>  />
                                <label for="automatic_lock"> <?= $Lang->get('FORUM__AUTOLOCK__CATEGORY') ?></label>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary"><?= $Lang->get('GLOBAL__SUBMIT'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php elseif ($type == 'user'): ?>
                <div class="card card-primary">
                    <div class="card-header with-border">
                        <h3 class="card-title"><?= $Lang->get('FORUM__EDIT__USER') ?></h3>
                    </div>
                    <div class="card-body">
                        <form action="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'admin_edit', 'admin' => true)) ?>" method="post" data-ajax="true" data-redirect="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'admin_edit', 'admin' => true)) ?>">
                            <div class="ajax-msg"></div>
                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__PSEUDO'); ?></label>
                                <input type="hidden" name="idgroup" value="<?= $datas['rank']['r']; ?>" />
                                <input type="hidden" name="useredit" value="<?= $datas['user']['id']; ?>" />
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-user" aria-hidden="true"></i>
                                            </div>
                                            <input class="form-control" type="text" value="<?= $datas['user']['username']; ?>" disabled />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <?php if($datas['user']['isconnected']): ?>
                                            <div class="admin-state admin-online">En ligne</div>
                                        <?php else: ?>
                                            <div class="admin-state admin-offline">Hors ligne</div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-responsive dataTable">
                                <thead>
                                    <tr>
                                        <th><?= $Lang->get('FORUM__GROUP'); ?></th>
                                        <th><?= $Lang->get('FORUM__DOMINANCE'); ?></th>
                                        <th><?= $Lang->get('FORUM__RANK__ALT'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($datas['rank']['rank'] as $key => $rank): ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="rank_<?= $rank['Group']['id']; ?>"<?php if(!empty($datas['rank']['rankbis'])){foreach ($datas['rank']['rankbis'] as $d){if($d['id'] == $rank['Group']['id']) echo 'checked';}} ?> />
                                        </td>
                                        <td><input type="radio" <?php if (!empty($datas['rank']['domin'][0]['Groups_user']['domin']) && $datas['rank']['domin'][0]['Groups_user']['id_group'] == $rank['Group']['id']) echo 'checked'; ?> name="domin" value="<?= $rank['Group']['id']; ?>" /> </td>
                                        <td><div style="background-color:#<?= $rank['Group']['color']; ?>;color: #fff;padding: 2px 5px;margin-top: 5px;display: inline-block"><?= $rank['Group']['group_name']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__DESCRIPTION'); ?></label>
                                <textarea class="form-control" name="description" rows="3"><?php if(isset($datas['profile']['description'])) echo $datas['profile']['description']; ?></textarea>
                            </div>
                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__LAST__PASSAGE'); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                    </div>
                                    <input class="form-control" type="text" value="<?= $datas['user']['lastseen']; ?>" disabled />
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__ISBANNED'); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-cog" aria-hidden="true"></i>
                                    </div>
                                    <input class="form-control" type="text" value="<?= $datas['user']['isbanned']; ?>" disabled />
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary"><?= $Lang->get('GLOBAL__SUBMIT'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card card-info">
                    <div class="card-header with-border">
                        <h3 class="card-title"><?= $Lang->get('FORUM__SOCIAL__STATS') ?></h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div id="chart-bb-pie-thumbget"></div>
                            </div>
                            <div class="col-md-6">
                                <div id="chart-bb-pie-thumbset"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-md-push-3">
                                <div id="chart-bb-pie-nb"></div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                        var chart = bb.generate({
                            "data": {
                                "columns": [
                                    <?php if($datas['thumb']['get']['green'] > 1): ?>
                                        ["Pouces verts reçu", <?= $datas['thumb']['get']['green']; ?>],
                                    <?php else: ?>
                                        ["Pouce vert reçu", <?= $datas['thumb']['get']['green']; ?>],
                                    <?php endif; ?>

                                    <?php if($datas['thumb']['get']['red'] > 1): ?>
                                        ["Pouces rouges reçu", <?= $datas['thumb']['get']['red']; ?>],
                                    <?php else: ?>
                                        ["Pouce rouge reçu", <?= $datas['thumb']['get']['red']; ?>],
                                    <?php endif; ?>
                                ],
                                "colors": {
                                    <?php if($datas['thumb']['get']['green'] > 1): ?>
                                        "Pouces verts reçu": "#60B044",
                                    <?php else: ?>
                                        "Pouce vert reçu": "#60B044",
                                    <?php endif; ?>

                                    <?php if($datas['thumb']['get']['red'] > 1): ?>
                                        "Pouces rouges reçu": "#FF0000"
                                    <?php else: ?>
                                        "Pouce rouge reçu": "#FF0000"
                                    <?php endif; ?>
                                },
                                "type": "pie"
                            },
                            oninit: function() {
                                charterror('chart-bb-pie-thumbget', [<?= $datas['thumb']['get']['green']; ?>, <?= $datas['thumb']['get']['red']; ?>]);
                            },
                            "pie": {
                                "label": {
                                    "format": function (value, ratio, id) {
                                        return (value);
                                    }
                                }
                            },
                            "bindto": "#chart-bb-pie-thumbget"
                        });
                        var chart = bb.generate({
                            "data": {
                                "columns": [
                                    <?php if($datas['thumb']['set']['green'] > 1): ?>
                                    ["Pouces verts mis", <?= $datas['thumb']['set']['green']; ?>],
                                    <?php else: ?>
                                    ["Pouce vert mis", <?= $datas['thumb']['set']['green']; ?>],
                                    <?php endif; ?>

                                    <?php if($datas['thumb']['set']['red'] > 1): ?>
                                    ["Pouces rouges mis", <?= $datas['thumb']['set']['red']; ?>],
                                    <?php else: ?>
                                    ["Pouce rouge mis", <?= $datas['thumb']['set']['red']; ?>],
                                    <?php endif; ?>
                                ],
                                "colors": {
                                    <?php if($datas['thumb']['set']['green'] > 1): ?>
                                    "Pouces verts mis": "#60B044",
                                    <?php else: ?>
                                    "Pouce vert mis": "#60B044",
                                    <?php endif; ?>

                                    <?php if($datas['thumb']['set']['red'] > 1): ?>
                                    "Pouces rouges mis": "#FF0000"
                                    <?php else: ?>
                                    "Pouce rouge mis": "#FF0000"
                                    <?php endif; ?>
                                },
                                "type": "pie"
                            },
                            oninit: function() {
                                charterror('chart-bb-pie-thumbget', [<?= $datas['thumb']['set']['green']; ?>, <?= $datas['thumb']['set']['red']; ?>]);
                            },
                            "pie": {
                                "label": {
                                    "format": function (value, ratio, id) {
                                        return (value);
                                    }
                                }
                            },
                            "bindto": "#chart-bb-pie-thumbset"
                        });
                        var chart = bb.generate({
                            "data": {
                                "columns": [
                                    ["Message<?php if($datas['nb']['message'] > 1){echo's';} ?>", <?= $datas['nb']['message']; ?>],
                                    ["Topic<?php if($datas['nb']['topic'] > 1){echo's';} ?>", <?= $datas['nb']['topic']; ?>]
                                ],
                                "type": "pie"
                            },
                            oninit: function() {
                                charterror('chart-bb-pie-nb', [<?= $datas['nb']['message']; ?>, <?= $datas['nb']['topic']; ?>]);
                            },
                            "pie": {
                                "label": {
                                    "format": function (value, ratio, id) {
                                        return (value);
                                    }
                                }
                            },
                            "bindto": "#chart-bb-pie-nb"
                        });
                        function charterror(selector, data) {
                            var length = data.length;
                            for (var i = 0; i < length; i++){
                                if(data[i]) return true;
                            }
                            $('#' + selector).empty().addClass('error-chart').html('<i class="pie chart icon"></i><br />Graphique <br /> non disponible');
                        }
                    </script>
                </div>
                <div class="card card-success">
                    <div class="card-header with-border">
                        <h3 class="card-title"><?= $Lang->get('FORUM__SOCIAL__NETWORK') ?></h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="post" data-ajax="true">
                            <div class="ajax-msg"></div>
                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__SOCIAL__FACEBOOK'); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-facebook" aria-hidden="true"></i>
                                    </div>
                                    <input class="form-control" type="text" name="facebook" placeholder="https://www.facebook.com/FacebookFrance" value="<?= $socialNetworks['facebook']; ?>" />
                                </div>
                                <input class="form-control" type="hidden" name="social" value="social" />
                            </div>
                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__SOCIAL__TWITTER'); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-twitter" aria-hidden="true"></i>
                                    </div>
                                    <input class="form-control" type="text" name="twitter" placeholder="https://twitter.com/TwitterFrance" value="<?= $socialNetworks['twitter']; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__SOCIAL__YOUTUBE'); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-youtube-play" aria-hidden="true"></i>
                                    </div>
                                    <input class="form-control" type="text" name="youtube" placeholder="https://www.youtube.com/user/GoogleFrance" value="<?= $socialNetworks['youtube']; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__SOCIAL__GOOGLEPLUS'); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-google-plus" aria-hidden="true"></i>
                                    </div>
                                    <input class="form-control" type="text" name="googleplus" placeholder="https://plus.google.com/+GoogleFrance" value="<?= $socialNetworks['googleplus']; ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__SOCIAL__SNAPCHAT'); ?></label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-snapchat-ghost" aria-hidden="true"></i>
                                    </div>
                                    <input class="form-control" type="text" placeholder="snapchat" name="snapchat" value="<?= $socialNetworks['snapchat']; ?>" />
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary"><?= $Lang->get('GLOBAL__SUBMIT'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card card-danger">
                    <div class="card-header with-border">
                        <h3 class="card-title"><?= $Lang->get('FORUM__LAST__ACTIVITY') ?></h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive dataTable">
                            <thead>
                                <tr>
                                    <th><?= $Lang->get('FORUM__DATE__ACTIVITY'); ?></th>
                                    <th><?= $Lang->get('FORUM__IP'); ?></th>
                                    <th><?= $Lang->get('FORUM__CATEGORY__ALT'); ?></th>
                                    <th><?= $Lang->get('FORUM__ACTION'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($history as $key => $h): ?>
                                <tr>
                                    <td><?= $h['Historie']['date']; ?></td>
                                    <td><?= $h['Historie']['ip']; ?></td>
                                    <td><?= $h['Historie']['category']; ?></td>
                                    <td><?= $h['Historie']['action']; ?></td>
                                    <td><?= $h['Historie']['content']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card card-warning">
                    <div class="card-header with-border">
                        <h3 class="card-title"><?= $Lang->get('FORUM__LAST__REPORT') ?></h3>
                        <p><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> <?= $Lang->get('FORUM__PHRASE__PAGE__ADMINEDIT_1'); ?></p>
                    </div>
                    <div class="card-body">
                        <table class="table table-responsive dataTable">
                            <thead>
                                <tr>
                                    <th><?= $Lang->get('FORUM__DATE__REPORT'); ?></th>
                                    <th><?= $Lang->get('FORUM__REASON'); ?></th>
                                    <th><?= $Lang->get('FORUM__ACTION'); ?></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($msgReport as $key => $m): ?>
                                <tr>
                                    <td><?= $m['MsgReport']['date']; ?></td>
                                    <td><?= $m['MsgReport']['reason']; ?></td>
                                    <td><?= $m['MsgReport']['content']; ?></td>
                                    <td><a  href="<?= $this->Html->url($m['MsgReport']['href'].'/#'.$m['MsgReport']['id_msg']); ?>" class="btn btn-info"><i class="fa fa-external-link" aria-hidden="true"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?= $this->Html->css('dataTables.bootstrap.css'); ?>
            <?= $this->Html->script('jquery.dataTables.min.js') ?>
            <?= $this->Html->script('dataTables.bootstrap.min.js') ?>
            <script type="text/javascript">
                $('.dataTable').dataTable( {
                    "paging": false
                } );
            </script>
            <?php elseif($type == 'rank'): ?>
                <?= $this->Html->css('Forum.bootstrap-colorpicker.min.css'); ?>
                <div class="card">
                    <div class="card-header with-border">
                        <h3 class="card-title"><?= $Lang->get('FORUM__EDIT__RANK') ?></h3>
                        <p class="pull-right"><a href="<?= $this->Html->url('/admin/forum/forum/rank'); ?>"><i class="fa fa-undo" aria-hidden="true"></i> <?= $Lang->get('FORUM__BACKTO__RANK'); ?></a></p>
                    </div>
                    <div class="card-body">
                        <form action="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'admin_edit', 'admin' => true)) ?>" method="post" data-ajax="true" data-redirect="<?= $this->Html->url(array('controller' => 'forum', 'action' => 'admin_edit', 'admin' => true)) ?>">
                            <div class="ajax-msg"></div>
                            <div class="form-group">
                                <label><?= $Lang->get('GLOBAL__NAME') ?></label>
                                <input value="<?= $datas['group_name']; ?>" placeholder="Développeur" name="name" class="form-control" type="text" />
                                <input value="<?= $datas['id']; ?>" name="id" type="hidden" />
                            </div>
                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__DESCRIPTION') ?></label>
                                <input value="<?= $datas['group_description']; ?>" placeholder="Description du groupe" name="description" class="form-control" type="text" />
                            </div>
                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__COLOR') ?></label>
                                <div class="row">
                                    <div class="col-md-11">
                                        <input placeholder="#ffffff" value="<?= $datas['color']; ?>" name="color" class="form-control colorpicker-element" type="text" />
                                    </div>
                                    <div class="col-md-1">
                                        <a target="_blank" href="http://htmlcolorcodes.com/fr/"><i class="fa fa-question-circle" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?= $Lang->get('FORUM__POSITION') ?></label>
                                <input value="<?= $datas['position']; ?>" placeholder="Position ( 1 à 99 )" name="position" class="form-control" type="text" />
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary"><?= $Lang->get('GLOBAL__EDIT'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
                <?= $this->Html->script('Forum.bootstrap-colorpicker.min.js'); ?>
                <script type="text/javascript">$(function(){$('.colorpicker-element').colorpicker();});</script>
            <?php endif; ?>
        </div>
    </div>
</section>

<?= $this->Html->script('Forum.select2.min.js'); ?>
<script type="text/javascript">
    $(document).ready(function(){$(".e1").select2();});
</script>