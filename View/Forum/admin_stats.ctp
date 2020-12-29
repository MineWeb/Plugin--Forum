<?= $this->Html->css('Forum.billboard.min.css') ?>
<?= $this->Html->script('Forum.d3.v4.min.js'); ?>
<?= $this->Html->script('Forum.billboard.min.js'); ?>
<?= $this->Html->css('Forum.forum-style.css') ?>

<section class="content">
    <div class="row">
        <div class="col-md-3 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-aqua"><i class="fa fa-envelope-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= $Lang->get('FORUM__MSG'); ?><?php if($stats['general']['total_msg'] > 1) echo 's'; ?></span>
                    <span class="info-box-number"><?= $stats['general']['total_msg']; ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-yellow"><i class="fa fa-file-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= $Lang->get('FORUM__TOPIC'); ?><?php if($stats['general']['total_topic'] > 1) echo 's'; ?></span>
                    <span class="info-box-number"><?= $stats['general']['total_topic']; ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-thumbs-up"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= $Lang->get('FORUM__THUMB'); ?><?php if($stats['thumbgreen'] > 1) echo 's'; ?> <?= $Lang->get('FORUM__GREEN'); ?><?php if($stats['thumbgreen'] > 1) echo 's'; ?></span>
                    <span class="info-box-number"><?= $stats['thumbgreen']; ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-red"><i class="fa fa-thumbs-down"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?= $Lang->get('FORUM__THUMB'); ?><?php if($stats['thumbred'] > 1) echo 's'; ?> <?= $Lang->get('FORUM__RED'); ?><?php if($stats['thumbred'] > 1) echo 's'; ?></span>
                    <span class="info-box-number"><?= $stats['thumbred']; ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-maroon"><i class="fa fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?php if($stats['usertoday'] > 1){ echo $Lang->get('FORUM__USER__REGISTERED__TODAY'); }else{ echo $Lang->get('FORUM__USER__REGISTERED__TODAY__S'); } ?></span>
                    <span class="info-box-number"><?= $stats['usertoday']; ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-navy"><i class="fa fa-list"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?php if($stats['forum'] > 1){ echo $Lang->get('FORUM__FORUMS'); }else{ echo $Lang->get('FORUM__TITLE'); } ?></span>
                    <span class="info-box-number"><?= $stats['forum']; ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-teal"><i class="fa fa-comments"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?php if($stats['category'] > 1){ echo $Lang->get('FORUM__CATEGORY__ALT__P'); }else{ echo $Lang->get('FORUM__CATEGORY__ALT'); } ?></span>
                    <span class="info-box-number"><?= $stats['category']; ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-olive"><i class="fa fa-commenting-o"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text"><?php if($stats['mp'] > 1){ echo $Lang->get('FORUM__MPS'); }else{ echo $Lang->get('FORUM__MP'); } ?></span>
                    <span class="info-box-number"><?= $stats['mp']; ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Ranking of last seven days -->
    <div class="row mt30">
        <div class="col-md-5 col-xs-12">
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-area-chart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Statistiques des 8 derniers jours</span>
                    <span class="info-box-number"></span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 50%"></div>
                    </div>
                    <span class="progress-description">
                  </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__STATISTIC__VIEWTOPIC') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="chart-bb-spline-views"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__STATISTIC__TOPICCREATE') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="chart-bb-spline-nbtopic"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ranking of last twelve months -->
    <div class="row mt30">
        <div class="col-md-5 col-xs-12">
            <div class="info-box bg-aqua">
                <span class="info-box-icon"><i class="fa fa-area-chart"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Statistiques des 12 derniers mois</span>
                    <span class="info-box-number"></span>
                    <div class="progress">
                        <div class="progress-bar" style="width: 50%"></div>
                    </div>
                    <span class="progress-description">
                  </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-info">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__STATISTIC__VIEWTOPIC') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="chart-bb-spline-viewsmonth"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__STATISTIC__TOPICCREATE') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="chart-bb-spline-nbmonth"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__LAST__ACTIVITYS') ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered dataTable">
                                <thead>
                                <tr>
                                    <th><?= $Lang->get('FORUM__PSEUDO'); ?></th>
                                    <th><?= $Lang->get('FORUM__LAST__ACTIVITY'); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($lastactivities as $l): ?>
                                    <tr>
                                        <td><?= $l['User']['pseudo']; ?></td>
                                        <td><?= $l['User']['forum-last_activity']; ?></td>
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

<script type="text/javascript">
    var chart = bb.generate({
        "data": {
            "x": "x",
            "columns": [
                ["x",
                    <?php foreach ($stats['x'] as $s): ?>
                        "<?= $s['date']; ?>",
                    <?php endforeach; ?>
                ],
                ["Nombre de topics vue(s)",
                    <?php foreach ($stats['view'] as $s): ?>
                        <?= $s['nb']; ?>,
                    <?php endforeach; ?>
                ]
            ],
            "type": "spline"
        },
        "axis": {
            "x": {
                "type": "category"
            }
        },
        "bindto": "#chart-bb-spline-views"
    });
    var chart = bb.generate({
        "data": {
            "x": "x",
            "columns": [
                ["x",
                    <?php foreach ($stats['x'] as $s): ?>
                        "<?= $s['date']; ?>",
                    <?php endforeach; ?>
                ],
                ["Nombre de nouveau(x) messages(s)",
                    <?php foreach ($stats['message'] as $s): ?>
                        <?= $s['nb']; ?>,
                    <?php endforeach; ?>
                ],
                ["Nombre de nouveau(x) topic(s)",
                    <?php foreach ($stats['topic'] as $s): ?>
                        <?= $s['nb']; ?>,
                    <?php endforeach; ?>
                ]
            ],
            "type": "spline"
        },
        "axis": {
            "x": {
                "type": "category"
            }
        },
        "bindto": "#chart-bb-spline-nbtopic"
    });
    var chart = bb.generate({
        "data": {
            "x": "x",
            "columns": [
                ["x",
                    <?php foreach ($stats['month']['topic'] as $s): ?>
                        "<?= $s['date']; ?>",
                    <?php endforeach; ?>
                ],
                ["Nombre de topics vue(s)",
                    <?php foreach ($stats['month']['topic'] as $s): ?>
                        <?= $s['view']; ?>,
                    <?php endforeach; ?>
                ]
            ],
            "type": "spline"
        },
        "axis": {
            "x": {
                "type": "category"
            }
        },
        "bindto": "#chart-bb-spline-viewsmonth"
    });
    var chart = bb.generate({
        "data": {
            "x": "x",
            "columns": [
                ["x",
                    <?php foreach ($stats['month']['topic'] as $s): ?>
                        "<?= $s['date']; ?>",
                    <?php endforeach; ?>
                ],
                ["Nombre de nouveau(x) messages(s)",
                    <?php foreach ($stats['month']['message'] as $s): ?>
                        <?= $s['nb']; ?>,
                    <?php endforeach; ?>
                ],
                ["Nombre de nouveau(x) topic(s)",
                    <?php foreach ($stats['month']['topic'] as $s): ?>
                        <?= $s['nb']; ?>,
                    <?php endforeach; ?>
                ]
            ],
            "type": "spline"
        },
        "axis": {
            "x": {
                "type": "category"
            }
        },
        "bindto": "#chart-bb-spline-nbmonth"
    });
</script>