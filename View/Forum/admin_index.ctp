<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-envelope-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><?= $Lang->get('FORUM__MSG'); ?><?php if($stats['total_msg'] > 1) echo 's'; ?></span>
                            <span class="info-box-number"><?= $stats['total_msg']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-file-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><?= $Lang->get('FORUM__TOPIC'); ?><?php if($stats['total_topic'] > 1) echo 's'; ?></span>
                            <span class="info-box-number"><?= $stats['total_topic']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= $Lang->get('FORUM__INFORMATION') ?></h3>
                        </div>
                        <div class="box-body">
                            <p>
                                <?= $Lang->get('FORUM__PHRASE__PAGE__ADMININDEX_8'); ?>
                                <ul>
                                    <li><a href="https://www.phpierre.fr/" target="_blank">Site web</a></li>
                                    <li><a href="https://twitter.com/PHPierrre" target="_blank">Profil Twitter</a></li>
                                    <li><a href="https://github.com/PHPierrre" target="_blank">Profil Github</a></li>
                                </ul>
                            </p>
                            <br />
                            <b><?= $Lang->get('FORUM__PHRASE__PAGE__ADMININDEX_7'); ?></b>
                            <ul>
                                <li><a href="https://github.com/MineWeb/Plugin-Forum" target="_blank">Le code du forum sur Github</a></li>
                                <li><a href="https://discordapp.com/invite/3QYdt8r" target="_blank">Le discord de MineWeb</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= $Lang->get('USER__RANK_MEMBER'); ?><?php if($stats['countuser'] > 1) echo 's'; ?><?= $Lang->get('FORUM__PHRASE__ONLINE'); ?></h3>
                            <div class="box-tools pull-right">
                                <span class="label label-success"><?= $stats['countuser']; ?> <?= $Lang->get('USER__RANK_MEMBER'); ?><?php if($stats['countuser'] > 1) echo 's'; ?><?= $Lang->get('FORUM__PHRASE__ONLINE'); ?></span>
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body no-padding">
                            <ul class="users-list clearfix">
                                <?php foreach($userOnlines as $userOnline): ?>
                                    <li style="width: 10%">
                                        <img src="<?= $this->Html->url(array('controller' => 'API', 'action' => 'get_head_skin', 'plugin' => false, $userOnline['User']['pseudo'], 32, 'admin' => false)); ?>" alt="<?= $userOnline['User']['pseudo']; ?>">
                                        <a class="users-list-name" href="<?= $this->Html->url('/user/'.$userOnline['User']['pseudo'].'.'.$userOnline['User']['id'].'/'); ?>"><?= $userOnline['User']['pseudo']; ?></a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= $Lang->get('FORUM__GENERAL') ?></h3>
                </div>
                <div class="box-body">
                    <?= $remoteMsg['info']; ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-thumbs-up"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><?= $Lang->get('FORUM__THUMB'); ?><?php if($stats['thumbgreen'] > 1) echo 's'; ?> <?= $Lang->get('FORUM__GREEN'); ?><?php if($stats['thumbgreen'] > 1) echo 's'; ?></span>
                            <span class="info-box-number"><?= $stats['thumbgreen']; ?></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-thumbs-down"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text"><?= $Lang->get('FORUM__THUMB'); ?><?php if($stats['thumbred'] > 1) echo 's'; ?> <?= $Lang->get('FORUM__RED'); ?><?php if($stats['thumbred'] > 1) echo 's'; ?></span>
                            <span class="info-box-number"><?= $stats['thumbred']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="box-body">
                    <h3 class="box-title"><?= $Lang->get('FORUM__CONFIG') ?></h3>
                    <form action="" method="post" data-ajax="true">
                        <input type="hidden" name="config" value="42" />
                        <div class="ajax-msg"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-responsive">
                                    <tbody>
                                        <?php foreach ($configs as $config): ?>
                                            <tr>
                                                <td><?= $Lang->get($config['Config']['lang']); ?></td>
                                                <td><input type="radio" name="<?= $config['Config']['config_name']; ?>" <?php if($config['Config']['config_value'] == 1) echo "checked"; ?> value="1"> <?= $Lang->get('GLOBAL__ENABLED'); ?></td>
                                                <td><input type="radio" name="<?= $config['Config']['config_name']; ?>" <?php if($config['Config']['config_value'] == 0) echo "checked"; ?> value="0"> <?= $Lang->get('GLOBAL__DISABLED'); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="pull-right">
                            <button class="btn btn-primary" type="submit"><?= $Lang->get('GLOBAL__EDIT') ?></button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="box">
                <div class="box-body">
                    <h3 class="box-title"><?= $Lang->get('FORUM__CONFIG__INSTALL') ?></h3>
                    <p style="color: #dc322f"><?= $Lang->get('FORUM__PHRASE__PAGE__ADMININDEX_5'); ?></p>
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="post" data-ajax="true">
                                <input type="hidden" name="install" value="42" />
                                <div class="ajax-msg"></div>
                                <div class="text-center">
                                    <button class="btn btn-primary" type="submit"><?= $Lang->get('FORUM__INSTALL__DEFAULT') ?></button>
                                </div>
                            </form>
                            <form action="" method="post" style="margin-top:30px" data-ajax="true">
                                <input type="hidden" name="install" value="43" />
                                <div class="ajax-msg"></div>
                                <div class="text-center">
                                    <button class="btn btn-primary" type="submit"><?= $Lang->get('FORUM__INSTALL__RESET') ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box">
                <div class="box-body">
                    <h3 class="box-title"><?= $Lang->get('FORUM__CONFIG__HISTORY') ?></h3>
                    <p><?= $Lang->get('FORUM__PHRASE__PAGE__ADMININDEX_6'); ?></p>
                    <form action="" method="post" data-ajax="true">
                        <input type="hidden" name="drop" value="42" />
                        <div class="ajax-msg"></div>
                        <div class="text-center">
                            <button class="btn btn-danger" type="submit"><?= $Lang->get('FORUM__DROP') ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>