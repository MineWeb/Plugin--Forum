<?= $this->Html->css('Forum.forum-style.css?' . rand(1, 1000000)) ?>
<?= $this->Html->css('Forum.custom.css') ?>

<div class="background-forum">
    <div class="<?= $theme; ?> marge">
        <?php if (!empty($alertforum['update'])) echo $alertforum['update']; ?>
        <div class="row">
            <div class="col-md-10">
                <ol class="forum-breadcrumb">
                    <li class="forum-breadcrumb-home">
                        <a href="<?= $this->Html->url('/forum') ?>" class="forum-breadcrumb-fahome"><i
                                    class="fa fa-home" aria-hidden="true"></i></a>
                    </li>
                    <?php foreach ($breadcrumb as $key => $b): ?>
                        <li class="forum-breadcrumb-href">
                            <a href="<?= $this->Html->url('/forum/' . $b['url'] . '/') ?>">
                                <?= $b['name']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ol>
            </div>
            <div class="col-md-2">
                <?php if ($isConnected): ?>
                    <?php if (!$isLock or $perms['FORUM_TOPIC_LOCK']): ?>
                        <a href="<?= $this->Html->url('/topic/add/' . $id) ?>" class="btn btn-theme mt30"><i
                                    class="fa fa-plus"
                                    aria-hidden="true"></i> <?= $Lang->get('FORUM__TOPIC__CREATE'); ?></a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <?= @$this->Session->flash(); ?>
            </div>
        </div>

        <div class="forum-forum">
            <div class="forum-forum-header">
                <p class="forum-forum-title"><i class="fa fa-folder-open"
                                                aria-hidden="true"></i> <?= $parent['forum_parent']['name']; ?></p>
            </div>
            <?php foreach ($forums as $f => $forum): ?>
                <?php if ($forum['Forum']['visible']): ?>
                    <div class="forum-category">
                        <div class="row">
                            <div class="forum-category-icone col-xs-2 col-md-1 text-center">
                                <?php if (filter_var($forum['Forum']['forum_image'], FILTER_VALIDATE_URL)): ?>
                                    <img src="<?= $forum['Forum']['forum_image']; ?>" class="forum-category-icon"
                                         alt=""/>
                                <?php else: ?>
                                    <i class="fa fa-<?= $forum['Forum']['forum_image']; ?> forum-category-fa"
                                       aria-hidden="true"></i>
                                <?php endif; ?>
                            </div>
                            <div class="col-xs-7 col-md-7 col-sm-6">
                                <h3 class="forum-category-title">
                                    <a style="color:<?php if (!empty($internal['forum_color'])) echo $internal['forum_color']; ?>"
                                       href="<?= h($forum['Forum']['href']); ?>"><?= $this->Text->truncate(h($forum['Forum']['forum_name']), 50); ?></a>
                                </h3>
                                <div class="forum-category-description">
                                    <span><?= $Lang->get('FORUM__FORUMS__ALT'); ?> :</span> <?= $forum['Forum']['nb_discussion']; ?>
                                    <span><?= $Lang->get('FORUM__MSG'); ?> :</span> <?= $forum['Forum']['nb_message']; ?>
                                </div>
                            </div>
                            <?php if (isset($forum['Forum']['forum_last_href'])): ?>
                                <div class="col-md-4 col-sm-4 col-xs-3 hidden-mob forum-category-last">
                                    <?php if ($forum['Forum']['nb_discussion'] != 0 && $forum['Forum']['nb_message'] != 0): ?>
                                        <a style="color:<?php if (!empty($internal['last_colortitle'])) echo $internal['last_colortitle']; ?>"
                                           href="<?= $forum['Forum']['forum_last_href']; ?>">
                                            <?= $this->Text->truncate(h($forum['Forum']['forum_last_title']), 60); ?>
                                        </a>
                                        <br/>
                                        <a style="color:#<?= $forum['Forum']['forum_last_author_color']; ?>"
                                           href="<?= $this->Html->url('/user/' . $forum['Forum']['forum_last_author']) . '.' . $forum['Forum']['forum_last_authorid'] . '/' ?>">
                                            <?= $forum['Forum']['forum_last_author']; ?>
                                        </a>, <span
                                                style="color:<?php if (!empty($internal['last_colordate'])) echo $internal['last_colordate']; ?>"><?= $forum['Forum']['forum_last_date']; ?></span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
            <?php if (!empty($topics_stick)): ?>
                <div class="forum-separator"><i class="fa fa-bell-o"
                                                aria-hidden="true"></i> <?= $Lang->get('FORUM__TOPIC__STICKED'); ?>
                </div>
            <?php endif; ?>
            <?php foreach ($topics_stick as $topic_stick): ?>
                <?php if ($topic_stick['Topic']['visible']): ?>
                    <div class="forum-category">
                        <div class="row">
                            <div class="forum-category-icone col-xs-2 col-md-1 text-center">
                                <i class="fa fa-comment forum-category-fa" aria-hidden="true"></i>
                            </div>
                            <div class="col-xs-7 col-md-5 col-sm-6">
                                <div class="pull-right">
                                    <i class="fa fa-thumb-tack" style="color:#348fef" aria-hidden="true"></i>
                                    <?php if ($topic_stick['Topic']['lock']): ?>
                                        <i class="fa fa-lock" style="color:#9f191f" aria-hidden="true"></i>
                                    <?php endif; ?>
                                    <?php if ($perms['FORUM_TOPIC_LOCK'] || $perms['FORUM_TOPIC_STICK'] || $perms['FORUM_TOPIC_DELETE'] || $perms['FORUM_MOOVE_TOPIC'] || $perms['FORUM_MSG_EDIT']): ?>
                                        <a class="modal-edit" id="" data-toggle="modal"
                                           data-target="#ModalEdit-<?= $topic_stick['Topic']['id_topic']; ?>">
                                            <i class="fa fa-cog" style="color:#2ecc71" aria-hidden="true"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>

                                <?php foreach ($tags as $key => $tag): ?>
                                    <?php if (isset($topic_stick['Topic']['tags'])):
                                        $explode = explode(',', $topic_stick['Topic']['tags']);
                                        foreach ($explode as $key => $e):
                                            if ($e == $tag['Tag']['id']): ?>
                                                <span style="background-color: #<?= $tag['Tag']['color']; ?>"
                                                      class="labeltag">
                                                   <?php if (!empty($tag['Tag']['icon'])): ?>
                                                       <i class="fa fa-<?= $tag['Tag']['icon']; ?>"
                                                          aria-hidden="true"></i>
                                                   <?php endif; ?>
                                                    <?= $tag['Tag']['name']; ?>
                                               </span>
                                            <?php endif;
                                        endforeach;
                                    endif;
                                endforeach; ?>

                                <h3 class="forum-category-title inline">
                                    <a style="color:<?php if (!empty($internal['topic_color'])) echo $internal['topic_color']; ?>"
                                       href="<?= $topic_stick['Topic']['href']; ?>"><?= $this->Text->truncate(h($topic_stick['Topic']['name']), 50); ?></a>
                                </h3>
                                <div class="forum-category-description">
                                    <a href="<?= $this->Html->url('/user/' . $topic_stick['Topic']['author'] . '.' . $topic_stick['Topic']['id_user'] . '/'); ?>"><?= $topic_stick['Topic']['author']; ?></a>, <?= $topic_stick['Topic']['date']; ?>
                                </div>
                            </div>
                            <div class="hidden-mob col-md-2 forum-category-last">
                                <div class="forum-category-description">
                                    <span><?= $Lang->get('FORUM__MSG'); ?> :</span> <?= $topic_stick['Topic']['nb_message']; ?>
                                </div>
                                <div class="forum-category-description">
                                    <span><?= $Lang->get('FORUM__VIEW'); ?><?php if ($topic_stick['Topic']['total_view'] > 1) echo 's'; ?> :</span> <?= $topic_stick['Topic']['total_view']; ?>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-4 col-xs-3 hidden-mob forum-category-last">
                                <a style="color:#<?= $topic_stick['Topic']['topic_last_author_color']; ?>"
                                   href="<?= $this->Html->url('/user/' . $topic_stick['Topic']['forum_last_author'] . '.' . $topic_stick['Topic']['forum_last_authorid'] . '/'); ?>"><?= $topic_stick['Topic']['forum_last_author']; ?></a>, <?= $topic_stick['Topic']['forum_last_date']; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <?php echo $this->element('modal', ['type' => 'propertiesTopic', 'perms' => $perms, 'topic' => $topic_stick, 'ranks' => $ranks, 'csrfToken' => $csrfToken]); ?>
            <?php endforeach; ?>
            <?php if (!empty($topics) && !empty($topics)): ?>
                <div class="forum-separator"><?= $Lang->get('FORUM__TOPICS'); ?></div>
            <?php endif; ?>
            <?php if (!empty($topics)): ?>
                <?php foreach ($topics as $topic): ?>
                    <?php if ($topic['Topic']['visible']): ?>
                        <div class="forum-category">
                            <div class="row">
                                <div class="forum-category-icone col-xs-2 col-md-1 text-center">
                                    <i class="fa fa-comment forum-category-fa" aria-hidden="true"></i>
                                </div>
                                <div class="col-xs-7 col-md-5 col-sm-6">
                                    <div class="pull-right">
                                        <?php if ($topic['Topic']['lock']): ?>
                                            <i class="fa fa-lock" style="color:#9f191f" aria-hidden="true"></i>
                                        <?php endif; ?>
                                        <?php if ($perms['FORUM_TOPIC_LOCK'] || $perms['FORUM_TOPIC_STICK'] || $perms['FORUM_TOPIC_DELETE'] || $perms['FORUM_MOOVE_TOPIC'] || $perms['FORUM_MSG_EDIT']): ?>
                                            <a class="modal-edit" id="" data-toggle="modal"
                                               data-target="#ModalEdit-<?= $topic['Topic']['id_topic']; ?>">
                                                <i class="fa fa-cog" style="color:#2ecc71" aria-hidden="true"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                    <?php foreach ($tags as $key => $tag): ?>
                                        <?php if (isset($topic['Topic']['tags'])):
                                            $explode = explode(',', $topic['Topic']['tags']);
                                            foreach ($explode as $key => $e):
                                                if ($e == $tag['Tag']['id']): ?>
                                                    <span style="background-color: #<?= $tag['Tag']['color']; ?>"
                                                          class="labeltag">
                                                           <?php if (!empty($tag['Tag']['icon'])): ?>
                                                               <i class="fa fa-<?= $tag['Tag']['icon']; ?>"
                                                                  aria-hidden="true"></i>
                                                           <?php endif; ?>
                                                        <?= $tag['Tag']['name']; ?>
                                                       </span>
                                                <?php endif;
                                            endforeach;
                                        endif;
                                    endforeach; ?>

                                    <h3 class="forum-category-title inline">
                                        <a style="color:<?php if (!empty($internal['topic_color'])) echo $internal['topic_color']; ?>"
                                           href="<?= $topic['Topic']['href']; ?>"><?= $this->Text->truncate(h($topic['Topic']['name']), 50); ?></a>
                                    </h3>
                                    <div class="forum-category-description">
                                        <a href="<?= $this->Html->url('/user/' . $topic['Topic']['author'] . '.' . $topic['Topic']['id_user'] . '/'); ?>"><?= $topic['Topic']['author']; ?></a>, <?= $topic['Topic']['date']; ?>
                                    </div>
                                </div>
                                <div class="hidden-mob col-md-2 forum-category-last">
                                    <div class="forum-category-description">
                                        <span><?= $Lang->get('FORUM__MSG'); ?> :</span> <?= $topic['Topic']['nb_message']; ?>
                                    </div>
                                    <div class="forum-category-description">
                                        <span><?= $Lang->get('FORUM__VIEW'); ?><?php if ($topic['Topic']['total_view'] > 1) echo 's'; ?> :</span> <?= $topic['Topic']['total_view']; ?>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-3 hidden-mob forum-category-last">
                                    <a style="color:#<?= $topic['Topic']['topic_last_author_color']; ?>"
                                       href="<?= $this->Html->url('/user/' . $topic['Topic']['forum_last_author'] . '.' . $topic['Topic']['forum_last_authorid'] . '/'); ?>"><?= $topic['Topic']['forum_last_author']; ?></a>, <?= $topic['Topic']['forum_last_date']; ?>
                                </div>
                            </div>
                        </div>

                        <?php echo $this->element('modal', ['type' => 'propertiesTopic', 'perms' => $perms, 'topic' => $topic, 'csrfToken' => $csrfToken]); ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <?= $pagination['html']; ?>

        <?php if ($isConnected): ?>
            <?php if (!$isLock or $perms['FORUM_TOPIC_LOCK']): ?>
                <div class="row">
                    <div class="col-md-10"></div>
                    <div class="col-md-2">
                        <div class="col-md-2">
                            <a href="<?= $this->Html->url('/topic/add/' . $id) ?>" class="btn btn-theme mt30"><i
                                        class="fa fa-plus"
                                        aria-hidden="true"></i> <?= $Lang->get('FORUM__TOPIC__CREATE'); ?></a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>