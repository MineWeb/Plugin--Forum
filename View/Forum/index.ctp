<?= $this->Html->css('Forum.forum-style.css?'.rand(1, 1000000)) ?>
<?= $this->Html->css('Forum.custom.css') ?>

<div class="background-forum">
    <div class="<?= $theme; ?> marge">
        <?php if(!empty($alertforum['update'])) echo $alertforum['update']; ?>
        <div class="row">
            <div class="col-md-10">
                <ol class="forum-breadcrumb">
                    <li class="forum-breadcrumb-home">
                        <a href="<?= $this->Html->url('/forum') ?>" class="forum-breadcrumb-fahome"><i class="fa fa-home" aria-hidden="true"></i></a>
                    </li>
                </ol>
            </div>
            <div class="col-md-2">
                <ol class="forum-breadcrumb forum-breadcrumb-menu">
                    <?php if($perms['FORUM_VIEW_REPORT']): ?>
                        <li class="forum-left">
                            <a href="<?= $this->Html->url('/forum/report') ?>" class="forum-breadcrumb-faflag"><i class="fa fa-flag" aria-hidden="true"></i></a>
                        </li>
                    <?php endif; ?>
                    <?php if($active['privatemsg'] && isset($_SESSION['user'])): ?>
                        <li class="forum-left">
                            <a href="<?= $this->Html->url('/message') ?>" class="forum-breadcrumb-faenvelope"><i class="fa fa-envelope-o" aria-hidden="true"></i></a>
                        </li>
                    <?php endif; ?>
                    <?php if(isset($_SESSION['user'])): ?>
                        <li class="forum-left">
                            <a href="<?= $this->Html->url('/user/'.$my['user'].'.'.$my['id'].'/') ?>" class="forum-breadcrumb-fauser"><i class="fa fa-user" aria-hidden="true"></i></a>
                        </li>
                        <li class="forum-left last">
                            <a href="<?= $this->Html->url('/user/logout') ?>" class="forum-breadcrumb-fasignout"><i class="fa fa-sign-out" aria-hidden="true"></i></a>
                        </li>
                    <?php else: ?>
                        <li class="forum-left last">
                            <a href="#" data-toggle="modal" data-target="#login" class="forum-breadcrumb-fasignin"><i class="fa fa-sign-in" aria-hidden="true"></i></a>
                        </li>
                    <?php endif; ?>
                </ol>
            </div>
        </div>

        <?= @$this->Session->flash(); ?>
        <?php foreach ($forums as $f => $forum): ?>
            <?php if($forum['Forum']['id_parent'] == 0): $p = $forum['Forum']['id']; ?>
                <div class="forum-forum" data-id="<?= $forum['Forum']['id']; ?>" data-hidden="false">
                    <div class="forum-forum-header">
                        <p class="forum-forum-title inline">
                            <?php if(filter_var($forum['Forum']['forum_image'], FILTER_VALIDATE_URL)): ?>
                                <img src="<?= $forum['Forum']['forum_image']; ?>" class="forum-category-icon-min" alt="" />
                            <?php else: ?>
                                <i class="fa fa-<?= $forum['Forum']['forum_image']; ?> forum-category-fa-min" aria-hidden="true"></i>
                            <?php endif; ?>

                            <?php if($internal['description'] == "tooltip"): ?>
                                <span data-toggle="tooltip" data-placement="right" title="<?= $forum['Forum']['forum_description']; ?>"><?= $forum['Forum']['forum_name']; ?></span>
                            <?php else: ?>
                                <span><?= $forum['Forum']['forum_name']; ?></span>
                                <span class="forum-description"><?= $forum['Forum']['forum_description']; ?></span>
                            <?php endif; ?>
                        </p>
                        <p class="inline pull-right chevron-hidden" data-idto="<?= $forum['Forum']['id']; ?>"><i style="color:<?php if(!empty($internal['chevron_color'])) echo $internal['chevron_color']; ?>" class="fa fa-chevron-down" aria-hidden="true"></i></p>
                    </div>
                    <?php foreach ($forums as $f => $forum): ?>
                        <?php if($forum['Forum']['id_parent'] != 0 && $forum['Forum']['id_parent'] == $p): ?>
                            <div class="forum-category">
                                <div class="row">
                                    <div class="forum-category-icone col-xs-2 col-md-1 text-center">
                                        <?php if(filter_var($forum['Forum']['forum_image'], FILTER_VALIDATE_URL)): ?>
                                            <img src="<?= $forum['Forum']['forum_image']; ?>" class="forum-category-icon" alt="" />
                                        <?php else: ?>
                                            <i class="fa fa-<?= $forum['Forum']['forum_image']; ?> forum-category-fa" aria-hidden="true"></i>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-xs-7 col-md-7 col-sm-6">
                                        <h3 class="forum-category-title">
                                            <a style="color:<?php if(!empty($internal['forum_color'])) echo $internal['forum_color']; ?>" href="<?= $forum['Forum']['href']; ?>">
                                                <?= $forum['Forum']['forum_name']; ?>
                                            </a>
                                        </h3>
                                        <div class="forum-category-description">
                                            <span><?= $Lang->get('FORUM__FORUMS__ALT'); ?> :</span> <?= $forum['Forum']['nb_discussion']; ?>
                                            <span><?= $Lang->get('FORUM__MSGS'); ?> :</span> <?= $forum['Forum']['nb_message']; ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-4 col-xs-3 hidden-mob forum-category-last">
                                        <?php if($forum['Forum']['nb_discussion'] != 0 && $forum['Forum']['nb_message'] != 0): ?>
                                            <a style="color:<?php if(!empty($internal['last_colortitle'])) echo $internal['last_colortitle']; ?>" href="<?= h($forum['Forum']['topic_last_href']); ?>"><?= $this->Text->truncate(h($forum['Forum']['topic_last_title']), 70); ?></a><br/>
                                            <a style="color:#<?= $forum['Forum']['topic_last_author_color']; ?>" href="<?= $this->Html->url('/user/'.$forum['Forum']['topic_last_author'].'.'.$forum['Forum']['topic_last_authorid'].'/'); ?>"><?= $forum['Forum']['topic_last_author']; ?></a>, <span style="color:<?php if(!empty($internal['last_colordate'])) echo $internal['last_colordate']; ?>"><?= $forum['Forum']['topic_last_date']; ?></span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

        <?php endforeach; ?>
        <?php if($active['statistics']): ?>
            <div class="forum-forum">
                <div class="forum-other-header">
                    <p style="color:<?php if(!empty($internal['index_title']['stats'])) echo $internal['index_title']['stats']; ?>" class="forum-forum-title"><i class="fa fa-bar-chart" aria-hidden="true"></i> <?= $Lang->get('FORUM__STATISTIC'); ?></p>
                </div>
                <div class="forum-category">
                    <div class="row">
                        <div class="col-md-6 col-xs-6 text-center">
                            <span class="forum-other-stats"><?= $stats['total_topic']; ?></span>
                            <span class="forum-other-stats-txt"><?= $Lang->get('FORUM__TOTAL__TOPIC'); ?></span>
                        </div>
                        <div class="col-md-6 col-xs-6 text-center">
                            <span class="forum-other-stats"><?= $stats['total_msg']; ?></span>
                            <span class="forum-other-stats-txt"><?= $Lang->get('FORUM__TOTAL__MSG'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if($active['useronline']): ?>
            <div class="forum-forum">
                <div class="forum-other-header">
                    <p style="color:<?php if(!empty($internal['index_title']['users'])) echo $internal['index_title']['users']; ?>" class="forum-forum-title"><i class="fa fa-users" aria-hidden="true"></i> <?= $Lang->get('FORUM__USER'); ?><?php if($stats['countuser'] > 1) echo 's'; ?> <?= $Lang->get('FORUM__CONNECTED'); ?><?php if($stats['countuser'] > 1) echo 's'; ?></p>
                </div>
                <div class="forum-category">
                    <div class="row">
                        <div class="col-md-12">
                            <?php $count = count($userOnlines); foreach($userOnlines as $key => $userOnline): ?>
                                <a href="<?= $this->Html->url('/user/'.$userOnline['User']['pseudo'].'.'.$userOnline['User']['id'].'/'); ?>" style="color: #<?= $userOnline['User']['color']; ?>"><?= $userOnline['User']['pseudo']; ?></a><?php if($key+1 != $count) echo ','; ?>
                            <?php endforeach; ?>
                            <?php if($stats['countuser'] == 0) echo $Lang->get('FORUM__ONLINE__ZERO'); ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.chevron-hidden').click(function(){
            var id = $(this).data('idto');
            var state = $('[data-id="'+id+'"]').data("hidden");
            if(state == false){
                $('[data-id="'+id+'"]').data('hidden', true);
                $('[data-id="'+id+'"] > .forum-category').slideUp(1000).fadeOut(1000);
                $('[data-id="'+id+'"] > .forum-forum-header > .chevron-hidden > i').removeClass("fa-chevron-down").addClass("fa-chevron-up");
            } else {
                $('[data-id="'+id+'"]').data('hidden', false);
                $('[data-id="'+id+'"] > .forum-category').slideDown(1000).fadeIn(1000);
                $('[data-id="'+id+'"] > .forum-forum-header > .chevron-hidden > i').removeClass("fa-chevron-up").addClass("fa-chevron-down");
            }
        });
        $(function () {$('[data-toggle="tooltip"]').tooltip()});
    });
</script>