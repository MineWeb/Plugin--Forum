<?= $this->Html->css('Forum.forum-style.css?' . rand(1, 1000000)) ?>
<?= $this->Html->css('Forum.custom.css') ?>

<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"
      integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<div class="container marge">
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <h1 class="text-center"><?= $Lang->get('FORUM__MYPROFILE'); ?></h1>
            <?= @$this->Session->flash(); ?>
            <form action="" method="post">
                <input name="data[_Token][key]" value="<?= $csrfToken ?>" type="hidden"/>
                <div class="forum-forum">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="forum-forum-header">
                                <p class="forum-forum-title"><i class="fa fa-id-card"
                                                                aria-hidden="true"></i> <?= $Lang->get('FORUM__DESCRIPTION'); ?>
                                </p>
                            </div>
                            <div class="forum-category">
                                <textarea name="description" class="form-control"
                                          rows="5"><?= h(strip_tags($infos['description'])); ?></textarea>
                                <div class="pull-right font-min"> Max 300</div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if ($active['socialnetwork']): ?>
                    <div class="forum-forum mt20">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="forum-forum-header">
                                    <p class="forum-forum-title"><i class="fa fa-id-card"
                                                                    aria-hidden="true"></i> <?= $Lang->get('FORUM__SOCIAL__NETWORK'); ?>
                                    </p>
                                </div>
                                <div class="forum-category">
                                    <div class="form-group">
                                        <label><?= $Lang->get('FORUM__SOCIAL__FACEBOOK'); ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-facebook" aria-hidden="true"></i>
                                            </div>
                                            <input class="form-control" type="text" name="facebook"
                                                   placeholder="https://www.facebook.com/FacebookFrance"
                                                   value="<?= h(strip_tags($socialNetworks['facebook'])); ?>"/>
                                        </div>
                                        <input class="form-control" type="hidden" name="social" value="social"/>
                                    </div>
                                    <div class="form-group">
                                        <label><?= $Lang->get('FORUM__SOCIAL__TWITTER'); ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-twitter" aria-hidden="true"></i>
                                            </div>
                                            <input class="form-control" type="text" name="twitter"
                                                   placeholder="https://twitter.com/TwitterFrance"
                                                   value="<?= h(strip_tags($socialNetworks['twitter'])); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><?= $Lang->get('FORUM__SOCIAL__YOUTUBE'); ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-youtube-play" aria-hidden="true"></i>
                                            </div>
                                            <input class="form-control" type="text" name="youtube"
                                                   placeholder="https://www.youtube.com/user/GoogleFrance"
                                                   value="<?= h(strip_tags($socialNetworks['youtube'])); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><?= $Lang->get('FORUM__SOCIAL__GOOGLEPLUS'); ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-google-plus" aria-hidden="true"></i>
                                            </div>
                                            <input class="form-control" type="text" name="googleplus"
                                                   placeholder="https://plus.google.com/+GoogleFrance"
                                                   value="<?= h(strip_tags($socialNetworks['googleplus'])); ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label><?= $Lang->get('FORUM__SOCIAL__SNAPCHAT'); ?></label>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-snapchat-ghost" aria-hidden="true"></i>
                                            </div>
                                            <input class="form-control" type="text" placeholder="snapchat"
                                                   name="snapchat"
                                                   value="<?= h(strip_tags($socialNetworks['snapchat'])); ?>"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                <div class="row mt30">
                    <div class="col-md-12">
                        <div class="text-center">
                            <button type="submit" class="btn btn-theme"><i
                                        class="fa fa-share"></i> <?= $Lang->get('FORUM__EDIT__MYPROFILE'); ?></button>
                        </div>
                    </div>
                </div>
        </div>
        </form>
    </div>
</div>