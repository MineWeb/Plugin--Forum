<?= $this->Html->css('Forum.forum-style.css?'.rand(1, 1000000)) ?>
<?= $this->Html->css('Forum.custom.css') ?>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li <?php if($router == 'home') echo 'class="active"'; ?>><a href="./home"><?= $Lang->get('FORUM__GENERAL__O'); ?></a></li>
                    <li <?php if($router == 'forum') echo 'class="active"'; ?>><a href="./forum"><?= $Lang->get('FORUM__TITLE'); ?></a></li>
                </ul>
                <div class="tab-content">
                    <?php if($router == 'home'): ?>
                        <div class="tab-pane active" id="tab_general">
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="tab_general accordion" class="card card-info">
                                        <div class="card-header with-border">
                                            <h3 class="card-title"><?= $Lang->get('FORUM__BACKGROUND'); ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12" id="dropdown">
                                                    <?= $this->Form->create(false, ['type' => 'post', 'data-ajax' => 'true']); ?>
                                                        <div class="radio">
                                                            <input type="radio" name="background" value="[background][none]" <?php if($configTheme['background']['type'] == 'default') echo "checked"; ?> />
                                                            <label><?= $Lang->get('FORUM__BASIC'); ?></label>
                                                        </div>

                                                        <div class="radio">
                                                            <input type="radio" name="background" id="background-color" <?php if($configTheme['background']['type'] == 'color') echo "checked"; ?> value="[background][color]" />
                                                            <label><?= $Lang->get('FORUM__COLOR'); ?></label>
                                                        </div>
                                                        <div id="type-dropdown" class="<?php if($configTheme['background']['type'] != 'color') echo 'hidden'; ?>">
                                                            <div class="form-group">
                                                                <label><?= $Lang->get('FORUM__COLOR'); ?></label>
                                                                <input type="text" class="form-control" name="background-color" value="<?php if($configTheme['background']['type'] == 'color') echo $configTheme['background']['value']; ?>" placeholder="#b2b2b2" />
                                                            </div>
                                                        </div>

                                                        <div class="radio">
                                                            <input type="radio" name="background" id="background-image"  <?php if($configTheme['background']['type'] == 'image') echo "checked"; ?> value="[background][image]" />
                                                            <label><?= $Lang->get('FORUM__PICTURE'); ?></label>
                                                        </div>
                                                        <div id="type-dropdown2" class="<?php if($configTheme['background']['type'] != 'image') echo 'hidden'; ?>">
                                                            <div class="form-group">
                                                                <label><?= $Lang->get('FORUM__URI'); ?></label>
                                                                <input type="text" class="form-control" name="background-image" value="<?php if($configTheme['background']['type'] == 'image') echo $configTheme['background']['value']; ?>" placeholder="https://images.unsplash.com/photo-1420768255295-e871cbf6eb81" />
                                                            </div>
                                                        </div>
                                                    <?= $this->Form->end(['label' => 'Modifier', 'class' => 'btn btn-success pull-right']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab_general accordion" class="card card-warning">
                                        <div class="card-header with-border">
                                            <h3 class="card-title"><?= $Lang->get('FORUM__MANAGE__ARROW'); ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php echo $this->Form->create(false, ['type' => 'post', 'data-ajax' => 'true']); ?>
                                                    <input type="hidden" name="type" value="arrow" />
                                                    <div class="form-group">
                                                        <div class="input-group">
                                                            <div class="input-group-addon">
                                                                <i style="color:<?php if(!empty($configTheme['chevron_color'])) echo $configTheme['chevron_color']; ?>" class="fa fa-chevron-down" aria-hidden="true"></i>
                                                            </div>
                                                            <input value="<?php if(!empty($configTheme['chevron_color'])) echo $configTheme['chevron_color']; ?>" name="color" placeholder="#A57A57 (couleur hexadécimal)" class="form-control" type="text" />
                                                        </div>
                                                    </div>
                                                    <?php echo $this->Form->end(['label' => 'Modifier', 'class' => 'btn btn-success pull-right']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab_general accordion" class="card card-primary">
                                        <div class="card-header with-border">
                                            <h3 class="card-title"><?= $Lang->get('FORUM__MANAGE__FORUM'); ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php echo $this->Form->create(false, ['type' => 'post', 'data-ajax' => 'true']); ?>
                                                    <input type="hidden" name="type" value="forum" />
                                                    <div class="form-group">
                                                        <label><?= $Lang->get('FORUM__COLOR_TITLETOPIC'); ?></label>
                                                        <input value="<?php if(!empty($configTheme['forum_color'])) echo $configTheme['forum_color']; ?>" name="color" placeholder="#A57A57 (couleur hexadécimal)" class="form-control" type="text" />
                                                    </div>
                                                    <?php echo $this->Form->end(['label' => 'Modifier', 'class' => 'btn btn-success pull-right']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="tab_general accordion" class="card card-info">
                                        <div class="card-header with-border">
                                            <h3 class="card-title"><?= $Lang->get('FORUM__MANAGE__HOME'); ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php echo $this->Form->create(false, ['type' => 'post', 'data-ajax' => 'true']); ?>
                                                    <input type="hidden" name="type" value="home" />
                                                    <div class="form-group">
                                                        <label><?= $Lang->get('FORUM__COLOR_TITLETOPIC'); ?> statistiques</label>
                                                        <input value="<?php if(!empty($configTheme['index_title']['stats'])) echo $configTheme['index_title']['stats']; ?>" name="stats" placeholder="#A57A57 (couleur hexadécimal)" class="form-control" type="text" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label><?= $Lang->get('FORUM__COLOR_TITLETOPIC'); ?> utilisateur </label>
                                                        <input value="<?php if(!empty($configTheme['index_title']['users'])) echo $configTheme['index_title']['users']; ?>" name="users" placeholder="#A57A57 (couleur hexadécimal)" class="form-control" type="text" />
                                                    </div>
                                                    <?php echo $this->Form->end(['label' => 'Modifier', 'class' => 'btn btn-success pull-right']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="tab_general accordion" class="card card-success">
                                        <div class="card-header with-border">
                                            <h3 class="card-title"><?= $Lang->get('FORUM__MANAGE__DESCRIPTIONS'); ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php echo $this->Form->create(false, ['type' => 'post', 'data-ajax' => 'true']); ?>
                                                        <div class="radio">
                                                            <input type="radio" name="description" value="description-base" <?php if($configTheme['description'] == 'description') echo "checked"; ?> />
                                                            <label><?= $Lang->get('FORUM__BASIC__LEFTTITLE'); ?></label>
                                                        </div>
                                                        <div class="radio">
                                                            <input type="radio" name="description" value="description-tooltip" <?php if($configTheme['description'] == 'tooltip') echo "checked"; ?> />
                                                            <label><?= $Lang->get('FORUM__BASIC__MOUSEHOVER'); ?></label>
                                                        </div>
                                                    <?php echo $this->Form->end(['label' => 'Modifier', 'class' => 'btn btn-success pull-right']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="tab_general accordion" class="card card-danger">
                                        <div class="card-header with-border">
                                            <h3 class="card-title"><?= $Lang->get('FORUM__MANAGE__ICONS'); ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <ol class="forum-breadcrumb forum-breadcrumb-menu">
                                                        <li class="forum-left">
                                                            <a href="" class="forum-breadcrumb-fahome"><i class="fa fa-home" aria-hidden="true"></i></a>
                                                        </li>
                                                        <li class="forum-left"></li>
                                                        <li class="forum-left"></li>
                                                        <li class="forum-left"></li>
                                                        <li class="forum-left"></li>
                                                        <li class="forum-left">
                                                            <a href="" class="forum-breadcrumb-faflag"><i class="fa fa-flag" aria-hidden="true"></i></a>
                                                        </li>
                                                        <li class="forum-left">
                                                            <a href="" class="forum-breadcrumb-faenvelope"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
                                                        </li>
                                                        <li class="forum-left">
                                                            <a href="" class="forum-breadcrumb-fauser"><i class="fa fa-user" aria-hidden="true"></i></a>
                                                        </li>
                                                        <li class="forum-left">
                                                            <a href="" class="forum-breadcrumb-fasignout"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
                                                        </li>
                                                        <li class="forum-left"></li>
                                                        <li class="forum-left"></li>
                                                        <li class="forum-left">
                                                            <a href="" class="forum-breadcrumb-fasignin"><i class="fa fa-sign-in" aria-hidden="true"></i></a>
                                                        </li>
                                                    </ol>
                                                </div>
                                                <div class="col-md-12">
                                                    <?php echo $this->Form->create(false, ['type' => 'post', 'data-ajax' => 'true']); ?>
                                                        <input type="hidden" name="type" value="icon" />
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-home" aria-hidden="true"></i>
                                                                </div>
                                                                <input value="<?php if(!empty($configTheme['icons']['home'])) echo $configTheme['icons']['home']; ?>" name="icon-home" placeholder="#A57A57 (couleur hexadécimal)" class="form-control" type="text" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-flag" aria-hidden="true"></i>
                                                                </div>
                                                                <input value="<?php if(!empty($configTheme['icons']['flag'])) echo $configTheme['icons']['flag']; ?>" name="icon-flag" placeholder="#A57A57 (couleur hexadécimal)" class="form-control" type="text" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                                                </div>
                                                                <input value="<?php if(!empty($configTheme['icons']['envelope'])) echo $configTheme['icons']['envelope']; ?>" name="icon-envelope" placeholder="#A57A57 (couleur hexadécimal)" class="form-control" type="text" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-user" aria-hidden="true"></i>
                                                                </div>
                                                                <input value="<?php if(!empty($configTheme['icons']['user'])) echo $configTheme['icons']['user']; ?>" name="icon-user" placeholder="#A57A57 (couleur hexadécimal)" class="form-control" type="text" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-sign-out" aria-hidden="true"></i>
                                                                </div>
                                                                <input value="<?php if(!empty($configTheme['icons']['out'])) echo $configTheme['icons']['out']; ?>" name="icon-out" placeholder="#A57A57 (couleur hexadécimal)" class="form-control" type="text" />
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-sign-in" aria-hidden="true"></i>
                                                                </div>
                                                                <input value="<?php if(!empty($configTheme['icons']['in'])) echo $configTheme['icons']['in']; ?>" name="icon-in" placeholder="#A57A57 (couleur hexadécimal)" class="form-control" type="text" />
                                                            </div>
                                                        </div>
                                                    <?php echo $this->Form->end(['label' => 'Modifier', 'class' => 'btn btn-success pull-right']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php elseif ($router == 'forum'): ?>
                        <div class="tab-pane active" id="tab_general">
                            <div class="row">
                                <div class="col-md-6">
                                    <div id="tab_general accordion" class="card card-primary">
                                        <div class="card-header with-border">
                                            <h3 class="card-title"><?= $Lang->get('FORUM__MANAGE__LASTTOPIC'); ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php echo $this->Form->create(false, ['type' => 'post', 'data-ajax' => 'true']); ?>
                                                    <input type="hidden" name="type" value="last" />
                                                    <div class="form-group">
                                                        <label><?= $Lang->get('FORUM__COLOR_TITLETOPIC'); ?></label>
                                                        <input value="<?php if(!empty($configTheme['last_colortitle'])) echo $configTheme['last_colortitle']; ?>" name="title" placeholder="#A57A57 (couleur hexadécimal)" class="form-control" type="text" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label><?= $Lang->get('FORUM__COLOR_DATETOPIC'); ?></label>
                                                        <input value="<?php if(!empty($configTheme['last_colordate'])) echo $configTheme['last_colordate']; ?>" name="date" placeholder="#A57A57 (couleur hexadécimal)" class="form-control" type="text" />
                                                    </div>
                                                    <?php echo $this->Form->end(['label' => 'Modifier', 'class' => 'btn btn-success pull-right']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div id="tab_general accordion" class="card card-warning">
                                        <div class="card-header with-border">
                                            <h3 class="card-title"><?= $Lang->get('FORUM__MANAGE__TOPIC'); ?></h3>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <?php echo $this->Form->create(false, ['type' => 'post', 'data-ajax' => 'true']); ?>
                                                    <input type="hidden" name="type" value="topic" />
                                                    <div class="form-group">
                                                        <label><?= $Lang->get('FORUM__COLOR_TITLETOPIC'); ?></label>
                                                        <input value="<?php if(!empty($configTheme['topic_color'])) echo $configTheme['topic_color']; ?>" name="color" placeholder="#A57A57 (couleur hexadécimal)" class="form-control" type="text" />
                                                    </div>
                                                    <?php echo $this->Form->end(['label' => 'Modifier', 'class' => 'btn btn-success pull-right']); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    $("#dropdown").change(function () {
        if ($("#background-color").is(':checked')) {
            $("#type-dropdown").removeClass('hidden');
        } else {
            $("#type-dropdown").addClass('hidden');
        }
        if ($("#background-image").is(':checked')) {
            $("#type-dropdown2").removeClass('hidden');
        } else {
            $("#type-dropdown2").addClass('hidden');
        }
    });
</script>