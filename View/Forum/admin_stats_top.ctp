<?= $this->Html->css('Forum.billboard.min.css') ?>
<?= $this->Html->script('Forum.d3.v4.min.js'); ?>
<?= $this->Html->script('Forum.billboard.min.js'); ?>
<?= $this->Html->css('Forum.forum-style.css') ?>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__NB__TOPIC'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="chart-bb-spline-topTopic"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-warning">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__NB__MESSAGE'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="chart-bb-spline-topMessage"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-danger">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__NB__VIEW'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="chart-bb-spline-topView"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-success">
                <div class="card-header with-border">
                    <h3 class="card-title"><?= $Lang->get('FORUM__NB__MP'); ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div id="chart-bb-spline-topMp"></div>
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
            "columns": [
                ["Nombre de topics",
                    <?php foreach ($ranking['topic']['nb'] as $r): ?>
                    "<?= $r[0]['count']; ?>",
                    <?php endforeach; ?>
                ]
            ],
            "type": "bar"
        },
        "bar": {
            "width": {
                "ratio": 0.6
            }
        },
        "axis": {
            "rotated": true,
            "x": {
                "type": "category",
                "categories": [
                    <?php foreach ($ranking['topic']['nb'] as $r): ?>
                        "<?= $r['username']; ?>",
                    <?php endforeach; ?>

                ]
            }
        },
        "bindto": "#chart-bb-spline-topTopic"
    });
    var chart = bb.generate({
        "data": {
            "columns": [
                ["Nombre de messages",
                    <?php foreach ($ranking['message']['nb'] as $r): ?>
                        "<?= $r[0]['count']; ?>",
                    <?php endforeach; ?>
                ]
            ],
            "type": "bar",
            "colors": {
                "Nombre de messages": "#ff7f0e"
            },
        },
        "bar": {
            "width": {
                "ratio": 0.6
            }
        },
        "axis": {
            "rotated": true,
            "x": {
                "type": "category",
                "categories": [
                    <?php foreach ($ranking['message']['nb'] as $r): ?>
                        "<?= $r['username']; ?>",
                    <?php endforeach; ?>

                ]
            }
        },
        "bindto": "#chart-bb-spline-topMessage"
    });
    var chart = bb.generate({
        "data": {
            "columns": [
                ["Nombre de vues",
                    <?php foreach ($ranking['view']['nb'] as $r): ?>
                        "<?= $r[0]['count']; ?>",
                    <?php endforeach; ?>
                ]
            ],
            "type": "bar",
            "colors": {
                "Nombre de vues": "#d62728"
            },
        },
        "bar": {
            "width": {
                "ratio": 0.6
            }
        },
        "axis": {
            "rotated": true,
            "x": {
                "type": "category",
                "categories": [
                    <?php foreach ($ranking['view']['nb'] as $r): ?>
                        "<?= $r['title']; ?>",
                    <?php endforeach; ?>

                ]
            }
        },
        "bindto": "#chart-bb-spline-topView"
    });
    var chart = bb.generate({
        "data": {
            "columns": [
                ["Nombre de mps",
                    <?php foreach ($ranking['mp']['nb'] as $r): ?>
                        "<?= $r[0]['count']; ?>",
                    <?php endforeach; ?>
                ]
            ],
            "type": "bar",
            "colors": {
                "Nombre de mps": "#2ca02c"
            },
        },
        "bar": {
            "width": {
                "ratio": 0.6
            }
        },
        "axis": {
            "rotated": true,
            "x": {
                "type": "category",
                "categories": [
                    <?php foreach ($ranking['mp']['nb'] as $r): ?>
                        "<?= $r['username']; ?>",
                    <?php endforeach; ?>

                ]
            }
        },
        "bindto": "#chart-bb-spline-topMp"
    });
</script>