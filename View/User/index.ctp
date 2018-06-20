<?= $this->Html->css('Forum.forum-style.css?'.rand(1, 1000000)) ?>
<?= $this->Html->css('Forum.custom.css') ?>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">

<div class="background-forum">
    <div class="<?= $theme; ?> marge">
        <div class="forum-forum">
            <div class="row">
                <div class="col-md-4">
                    <div class="p15">
                        <div class="forum-bloc p15 text-center">
                            <h2 class="text-center forum-h2" style="color:#<?= $userForum['color']; ?>"> <?= $slug; ?></h2>
                            <img width="200" class="center-block topic-avatar" src="<?= $this->Html->url(array('controller' => 'API', 'action' => 'get_head_skin', 'plugin' => false, $slug, 200, 42)); ?>" alt="Avatar <?= $id; ?>" />
                            <div class="forum-rank">
                                <?php if(!empty($ranks['rank'])): ?>
                                    <?php foreach($ranks['rank'] as $key => $rank): ?>
                                        <div <?= $ranks['color'][$key]; ?> class="forum-badgerank"><?= $rank['group_name']; ?></div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="forum-bloc mt15 p15">
                            <a class="btn-theme btn-full ml0" href="<?= $this->Html->url('/message/new/'.$slug) ?>"><i class="fa fa-paper-plane-o" aria-hidden="true"></i> <?= $Lang->get('FORUM__SEND__MP'); ?></a>
                        </div>
                        <div class="forum-bloc mt15">
                            <div class="forum-bloc-header p15">
                                <h3 class="forum-h3"><i class="fa fa-info-circle" aria-hidden="true"></i> <?= $Lang->get('GLOBAL__INFORMATIONS'); ?></h3>
                                <div class="forum-extrainfo">
                                    <dl>
                                        <dt><?= $Lang->get('FORUM__MSG'); ?><?php if($infos['nb_message'] > 1) echo 's'; ?> :</dt>
                                        <dd><?= $infos['nb_message']; ?></dd>
                                    </dl>
                                    <dl>
                                        <dt><?= $Lang->get('USER__REGISTER_DATE'); ?> :</dt>
                                        <dd><?= $infos['inscription']; ?></dd>
                                    </dl>
                                    <dl>
                                        <dt><?= $Lang->get('FORUM__GREENTHUMB'); ?> :</dt>
                                        <dd><?= $infos['thumb']['green']; ?></dd>
                                    </dl>
                                    <dl>
                                        <dt><?= $Lang->get('FORUM__REDTHUMB'); ?> :</dt>
                                        <dd><?= $infos['thumb']['red']; ?></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                        <?php if($active['socialnetwork']): ?>
                            <div class="forum-bloc mt15">
                                <div class="forum-bloc-header p15">
                                    <h3 class="forum-h3">
                                        <i class="fa fa-share-square-o" aria-hidden="true"></i> <?= $Lang->get('FORUM__SOCIAL__NETWORK'); ?>
                                        <?php if(isset($_SESSION['user']) && $_SESSION['user'] == $id): ?>
                                            <a class="btn-theme pull-right inline" href="<?= $this->Html->url('/user/'.$slug.'.'.$id.'/edit') ?>"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        <?php endif; ?>
                                    </h3>
                                    <div class="text-center">
                                        <?php if(!empty($socialNetworks['facebook'])): ?>
                                            <a class="media media-facebook" target="_blank" onclick="el('<?= $socialNetworks['facebook']; ?>')">
                                        <span class="fa-stack fa-lg">
                                              <i class="fa fa-square-o fa-stack-2x"></i>
                                              <i class="fa fa-facebook fa-stack-1x"></i>
                                        </span>
                                            </a>
                                        <?php endif; ?>
                                        <?php if(!empty($socialNetworks['twitter'])): ?>
                                            <a class="media media-twitter" target="_blank" onclick="el('<?= $socialNetworks['twitter']; ?>')">
                                        <span class="fa-stack fa-lg">
                                              <i class="fa fa-square-o fa-stack-2x"></i>
                                              <i class="fa fa-twitter fa-stack-1x"></i>
                                        </span>
                                            </a>
                                        <?php endif; ?>
                                        <?php if(!empty($socialNetworks['youtube'])): ?>
                                            <a class="media media-youtube" target="_blank" onclick="el('<?= $socialNetworks['youtube']; ?>')">
                                        <span class="fa-stack fa-lg">
                                              <i class="fa fa-square-o fa-stack-2x"></i>
                                              <i class="fa fa-youtube-play fa-stack-1x"></i>
                                        </span>
                                            </a>
                                        <?php endif; ?>
                                        <?php if(!empty($socialNetworks['googleplus'])): ?>
                                            <a class="media media-google-plus" target="_blank" onclick="el('<?= $socialNetworks['googleplus']; ?>')">
                                        <span class="fa-stack fa-lg">
                                              <i class="fa fa-square-o fa-stack-2x"></i>
                                              <i class="fa fa-google-plus fa-stack-1x"></i>
                                        </span>
                                            </a>
                                        <?php endif; ?>
                                        <?php if(!empty($socialNetworks['snapchat'])): ?>
                                            <a class="media media-snapchat" data-toggle="tooltip" data-placement="top" title="<?= $socialNetworks['snapchat']; ?>">
                                        <span class="fa-stack fa-lg">
                                              <i class="fa fa-square-o fa-stack-2x"></i>
                                              <i class="fa fa-snapchat-ghost fa-stack-1x"></i>
                                        </span>
                                            </a>
                                        <?php endif; ?>
                                        <?php if(empty($socialNetworks['facebook']) && empty($socialNetworks['twitter']) && empty($socialNetworks['youtube']) && empty($socialNetworks['googleplus']) && empty($socialNetworks['snapchat'])): ?>
                                            <a class="media">
                                        <span class="fa-stack fa-lg">
                                              <i class="fa fa-square-o fa-stack-2x"></i>
                                              <i class="fa fa-times fa-stack-1x"></i>
                                        </span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="p15">
                        <div class="forum-bloc p15">
                            <h5 class="inline"><?= $Lang->get('FORUM__PRESENTATION'); ?></h5>
                            <?php if(isset($_SESSION['user']) && $_SESSION['user'] == $id): ?>
                                <a class="btn-theme pull-right inline" href="<?= $this->Html->url('/user/'.$slug.'.'.$id.'/edit') ?>"><i class="fa fa-pencil" aria-hidden="true"></i> <?= $Lang->get('GLOBAL__EDIT'); ?></a>
                            <?php endif; ?>
                            <div>
                                <?= h($userForum['description']); ?>
                            </div>
                        </div>
                        <ul class="nav nav-tabs mt15" role="tablist">
                            <li role="presentation" class="active"><a href="#comment" aria-controls="comment" role="tab" data-toggle="tab"><?= $Lang->get('FORUM__COMMENT'); ?></a></li>
                            <li role="presentation"><a href="#thumb" aria-controls="profile" role="tab" data-toggle="tab"><?= $Lang->get('FORUM__FEEDBACK'); ?></a></li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane active" id="comment">
                                <h5><?= $Lang->get('FORUM__PHRASE__LASTCOMMENT'); ?></h5>
                                <?php foreach ($lasts['Comment'] as $last): ?>
                                    <hr />
                                    <div class="forum-profile-comment">
                                        <h4><a href="<?= $last['Topic']['href']; ?>"><?= h($last['Topic']['title']); ?></a></h4>
                                        <p><?= $this->Text->truncate(strip_tags($last['Topic']['content']), 150); ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div role="tabpanel" class="tab-pane" id="thumb">
                                <h5><?= $Lang->get('FORUM__PHRASE__LASTFEEDBACK'); ?></h5>
                                <?php foreach ($lasts['Note'] as $last): ?>
                                    <hr />
                                    <div class="forum-profile-appreciate">
                                        <p><i class="fa fa-thumbs-<?= $last['Note']['fa']; ?> thumb-<?= $last['Note']['class']; ?>" aria-hidden="true"></i> <?= $slug.$last['Note']['txt']; ?><a href="<?= $last['Note']['msg']['href']; ?>"><?= $this->Text->truncate(strip_tags($last['Note']['message']), 150); ?></a></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if($active['socialnetwork']): ?>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Attention</h4>
            </div>
            <div class="modal-body">
                <p>Vous allez quitter le site <b><?= $_SERVER['SERVER_NAME']; ?></b> pour vous rendre sur cette url :</p>
                <div class="text-center"><a class="js-link" href=""></a></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="next" class="btn btn-primary">Continuer</button>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script type="text/javascript">
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    })
    <?php if($active['socialnetwork']): ?>
    function el(site) {
        console.log(site);
        $('#myModal').modal('show');

        $('.js-link').attr('href', site);
        $('.js-link').empty().append(site);
        $(document).on("click", "#next", function(event){
            var uri = window.open(site, '_blank');
            uri.focus().empty();
            $('#myModal').modal('hide');
        });
    }
    <?php endif; ?>
</script>
