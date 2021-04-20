<?php

App::uses('ClassRegistry', 'Utility');

class ForumController extends ForumAppController
{

    public $helpers = [];

    public $components = [
        'Security' => [
            'csrfExpires' => '+1 hour'
        ]
    ];

    private $indexExplorer = -1;

    private $valueExplorer;

    private $valueTopic;

    public $debug;

    public function beforeFilter()
    {
        parent::beforeFilter();

        $this->loadModel('User');
        $this->loadModel('Forum.Punishment');
        $this->loadModel('Forum.Config');
        $this->User->updateAll(array('forum-last_activity' => "'" . date("Y-m-d H:i:s") . "'"), array('id' => $this->Session->read('user')));
        $this->Security->csrfExpires = '+1 hour';

        if ($this->isConnected && !in_array($this->request->params['action'], ['banned', 'admin_punishment', 'admin_delete']) && $this->Punishment->get($this->getIdSession())) {
            $this->redirect(Router::url('/', true) . 'forum/banned');
        }

        if (!$this->Config->notempty()) {
            $this->install();
        }

        $this->forumUpdate();

        $this->ㅋㅋㅋㅋㅋ();

        if ($this->theme == 'Justice') $this->layout = 'forum';
    }

    public function beforeRender()
    {
        parent::beforeRender();

        $alertforum['update'] = $this->hourCheck();
        if (!$this->isConnected || !$this->User->isAdmin()) {
            $alertforum['update'] = "";
        }

        $this->set(compact('alertforum'));
    }

    public function index()
    {
        $this->set('title_for_layout', $this->Lang->get('FORUM__TITLE'));

        $this->loadModel('Forum.forums');
        $this->loadModel('Forum.Internal');
        $this->loadModel('Forum.Topic');

        /* Specific */
        $this->loadModel('Forum.Note');
        $this->loadModel('Forum.Historie');

        //$this->loadModel('Navbar');
        /*if($this->Config->is('privatemsg')){
            if($this->Navbar->find('count', ['conditions' => ['name' => '<i class="fa fa-envelope" aria-hidden="true"></i>']]) == 0){
                ClassRegistry::init('navbars')->saveAll(['order' => 99, 'name' => '<i class="fa fa-envelope" aria-hidden="true"></i>', 'type' => 1, 'url' => '{"type":"custom", "url":"/message"}', 'open_new_tab' => 0]);
            }
        }else{
            ClassRegistry::init('navbars')->deleteAll(['order' => 99]);
        }*/

        if (!$this->Config->is('forum')) {
            throw new NotFoundException();
        }

        $perms = $this->perm_l();
        $forums = $this->Forum->getForum();
        foreach ($forums as $key => $forum) {
            if ($this->viewVisible($forums, $key)) {
                $forums[$key]['Forum']['href'] = $this->buildUri('forum', $forum['Forum']['forum_name'], $forum['Forum']['id']);

                $forums[$key]['Forum']['nb_discussion'] = $this->countChildrenTopic('forum', $forum['Forum']['id']);
                $forums[$key]['Forum']['nb_message'] = $this->countChildrenMessage('category', $forum['Forum']['id']);

                if ($forum['Forum']['id_parent'] != 0) {
                    $last = $this->explore($forum['Forum']['id']);

                    $forums[$key]['Forum']['topic_last_idtopic'] = $last['id'];
                    $forums[$key]['Forum']['topic_last_title'] = $last['title'];
                    $forums[$key]['Forum']['topic_last_date'] = $this->date($last['last_date']);
                    $forums[$key]['Forum']['topic_last_authorid'] = $last['last_author_id'];
                    $forums[$key]['Forum']['topic_last_author_color'] = $this->ForumPermission->getRankColorDomin($last['last_author_id']);
                    $forums[$key]['Forum']['topic_last_author'] = $last['last_author'];
                    $forums[$key]['Forum']['topic_last_href'] = $this->buildUri('topic', $last['title'], $last['id']);
                }
            } else {
                unset($forums[$key]);
            }
        }

        $active['statistics'] = ($this->Config->is('statistics')) ? true : false;
        $active['useronline'] = ($this->Config->is('useronline')) ? true : false;
        $active['privatemsg'] = ($this->Config->is('privatemsg')) ? true : false;
        $stats = ($this->Config->is('statistics')) ? $this->Topic->stats() : null;

        if ($this->Config->is('useronline')) {
            $userOnlines = $this->Forum->userOnline($this->User);
            foreach ($userOnlines as $key => $userOnline) {
                $userOnlines[$key]['User']['color'] = $this->ForumPermission->getRankColorDomin($userOnline['User']['id']);
            }
        } else {
            $userOnlines = null;
        }
        $my['id'] = $this->getIdSession();
        $my['user'] = $this->gUBY($this->getIdSession());
        $stats['countuser'] = count($userOnlines);
        $theme = $this->theme();

        $internal['description'] = $this->Internal->get('description');
        $internal['index_title'] = unserialize($this->Internal->get('index_title'));
        $internal['forum_color'] = $this->Internal->get('forum_color');
        $internal['chevron_color'] = $this->Internal->get('chevron_color');
        $internal['last_colortitle'] = $this->Internal->get('lasttopic_titlecolor');
        $internal['last_colordate'] = $this->Internal->get('lasttopic_datecolor');

        $this->set(compact('forums', 'stats', 'userOnlines', 'active', 'my', 'perms', 'theme', 'internal'));
    }

    public function forum($id, $slug, $page = 1)
    {
        $this->loadModel('Forum.forums');
        $this->loadModel('Forum.Internal');
        $this->loadModel('Forum.Tag');
        $this->loadModel('Forum.Topic');
        $this->loadModel('Forum.Vieww');

        if (!$this->Config->is('forum')) {
            throw new NotFoundException();
        }

        if (!$this->ForumPermission->visible('forum', $id) || !$this->viewParent('category', $id)) {
            throw new ForbiddenException();
        }

        if ($this->Forum->forumExist($id, $this->replaceHyppen($slug))) {

            $breadcrumb = $this->breadcrumb('category', $id);

            $forums = $this->Forum->getForum('categorie', $id);
            foreach ($forums as $key => $forum) {
                if ($this->viewVisible($forums, $key)) {
                    $forums[$key]['Forum']['href'] = $this->buildUri('forum', $forum['Forum']['forum_name'], $forum['Forum']['id']);

                    $forums[$key]['Forum']['nb_discussion'] = $this->countChildrenTopic('forum', $forum['Forum']['id']);
                    $forums[$key]['Forum']['nb_message'] = $this->countChildrenMessage('category', $forum['Forum']['id']);

                    if ($forum['Forum']['id_parent'] != 0) {
                        $last = $this->explore($forum['Forum']['id']);

                        $forums[$key]['Forum']['topic_last_idtopic'] = $last['id'];
                        $forums[$key]['Forum']['forum_last_title'] = $last['title'];
                        $forums[$key]['Forum']['forum_last_date'] = $this->date($last['last_date']);
                        $forums[$key]['Forum']['forum_last_authorid'] = $last['last_author_id'];
                        $forums[$key]['Forum']['forum_last_author'] = $last['last_author'];
                        $forums[$key]['Forum']['forum_last_author_color'] = $this->ForumPermission->getRankColorDomin($last['last_author_id']);
                        $forums[$key]['Forum']['forum_last_href'] = $this->buildUri('topic', $last['title'], $last['id']);
                    }

                    $forums[$key]['Forum']['visible'] = $this->ForumPermission->visible('forum', $forum['Forum']['id']);
                } else {
                    unset($forums[$key]);
                }
            }

            $topics_stick = $this->Topic->getTopic($id, 'stick');
            if (!empty($topics_stick)) {
                foreach ($topics_stick as $key => $topic_stick) {
                    $topics_stick[$key]['Topic']['forum_last_authorid'] = $this->Topic->getLastedTopic('id', $topic_stick['Topic']['id_topic'])['id_user'];
                    $topics_stick[$key]['Topic']['forum_last_author'] =  $this->gUBY($topics_stick[$key]['Topic']['forum_last_authorid']);
                    $topics_stick[$key]['Topic']['forum_last_title'] = $this->Topic->getLastedTopic('id', $topics_stick[$key]['Topic']['id_topic'])['name'];
                    $topics_stick[$key]['Topic']['forum_last_date'] =  $this->date($this->Topic->getLastedTopic('id', $topic_stick['Topic']['id_topic'])['date']);
                    $topics_stick[$key]['Topic']['author'] = $this->gUBY($topic_stick['Topic']['id_user']);
                    // ?
                    $topics_stick[$key]['Topic']['date'] = $this->date($topic_stick['Topic']['date'], '%d %B %Y');
                    $topics_stick[$key]['Topic']['nb_message'] = $this->Topic->getNbMessage('topic', $topic_stick['Topic']['id_topic']);
                    $topics_stick[$key]['Topic']['topic_last_author_color'] = $this->ForumPermission->getRankColorDomin($topics_stick[$key]['Topic']['forum_last_authorid']);
                    $topics_stick[$key]['Topic']['total_view'] = $this->Vieww->count($topic_stick['Topic']['id_topic']);
                    $topics_stick[$key]['Topic']['href'] = $this->buildUri('topic', $topic_stick['Topic']['name'], $topic_stick['Topic']['id_topic']);
                    $topics_stick[$key]['Topic']['individualPermission'] = unserialize($topic_stick['Topic']['visible']);
                    $topics_stick[$key]['Topic']['visible'] = $this->ForumPermission->visible('topic', $topic_stick['Topic']['id_topic']);
                    $topics_stick[$key]['Topic']['tags'] = $this->Topic->getTag($topic_stick['Topic']['id_topic']);
                }
            }

            $paginationDb = $this->Topic->pagination('forum', $id);
            $pagination['html'] = $this->forumRender('pagination', ['data' => 'e', 'style' => 'sm', 'page' => $page, 'nbpage' => $paginationDb['nbpage']]);

            $topics = $this->Topic->getTopic($id, 'nostick', $page);
            if (!empty($topics)) {
                foreach ($topics as $key => $topic) {
                    $topics[$key]['Topic']['name'] = $this->Topic->info('title_parent', $topic['Topic']['id_topic']);
                    $topics[$key]['Topic']['forum_last_authorid'] = $this->Topic->getLastedTopic('id', $topic['Topic']['id_topic'])['id_user'];
                    $topics[$key]['Topic']['forum_last_author'] =  $this->gUBY($topics[$key]['Topic']['forum_last_authorid']);
                    $topics[$key]['Topic']['forum_last_title'] = $this->Topic->getLastedTopic('id', $topics[$key]['Topic']['id_topic'])['name'];
                    $topics[$key]['Topic']['forum_last_date'] =  $this->date($this->Topic->getLastedTopic('id', $topic['Topic']['id_topic'])['date']);
                    $topics[$key]['Topic']['author'] = $this->gUBY($this->Topic->info('topic_author', $topic['Topic']['id_topic']));
                    //
                    $topics[$key]['Topic']['date'] = $this->date($topic['Topic']['date'], '%d %B %Y');
                    $topics[$key]['Topic']['nb_message'] = $this->Topic->getNbMessage('topic', $topic['Topic']['id_topic']);
                    $topics[$key]['Topic']['topic_last_author_color'] = $this->ForumPermission->getRankColorDomin($topics[$key]['Topic']['forum_last_authorid']);
                    $topics[$key]['Topic']['total_view'] = $this->Vieww->count($topic['Topic']['id_topic']);
                    $topics[$key]['Topic']['href'] = $this->buildUri('topic', $topics[$key]['Topic']['name'], $topic['Topic']['id_topic']);
                    $topics[$key]['Topic']['individualPermission'] = unserialize($topic['Topic']['visible']);
                    $topics[$key]['Topic']['visible'] = $this->ForumPermission->visible('topic', $topic['Topic']['id_topic']);
                    $topics[$key]['Topic']['tags'] = $this->Topic->getTag($topic['Topic']['id_topic']);
                }
            }

            $parent['forum_parent']['name'] = $this->replaceHyppen($slug);
            $theme = $this->theme();
            $perms = $this->perm_l();
            $listForum = $this->Forum->getForum('withoutforum');
            $isLock = $this->Forum->isLock($id);
            $ranks = $this->ForumPermission->getRanks();
            $tags = $this->Tag->get();

            $this->set('title_for_layout', $this->replaceHyppen($slug) . ' | ' . $this->Lang->get('FORUM__TITLE'));

            $internal['topic_color'] = $this->Internal->get('topic_color');
            $internal['forum_color'] = $this->Internal->get('forum_color');
            $internal['last_colortitle'] = $this->Internal->get('lasttopic_titlecolor');
            $internal['last_colordate'] = $this->Internal->get('lasttopic_datecolor');

            $this->set(compact(
                'forums',
                'slug',
                'topics',
                'topics_stick',
                'parent',
                'id',
                'theme',
                'pagination',
                'perms',
                'isLock',
                'listForum',
                'ranks',
                'tags',
                'breadcrumb',
                'internal'
            ));
        } else {
            throw new ForbiddenException();
        }
    }

    public function topic($id, $slug, $page = 1)
    {
        $this->loadModel('Forum.forums');
        $this->loadModel('Forum.MsgReport');
        $this->loadModel('Forum.Note');
        $this->loadModel('Forum.Profile');
        $this->loadModel('Forum.Tag');
        $this->loadModel('Forum.Topic');
        $this->loadModel('Forum.Vieww');

        if (!$this->Config->is('forum')) {
            throw new NotFoundException();
        }

        if ($this->Topic->topicExist($id, $this->replaceHyppen($slug))) {

            if (!$this->Vieww->exist($this->Util->getIP(), $id)) {
                $this->Vieww->addView($this->Util->getIP(), $id);
            }

            if (!$this->ForumPermission->visible('topic', $id)) {
                throw new ForbiddenException();
            }

            if ($this->viewParent('topic', $id)) {
            } else {
                throw new ForbiddenException();
            }

            $lock = $this->Topic->isLock($id);
            $stick = $this->Topic->isStick($id);

            if ($this->request->is('ajax')) {
                if ($this->isConnected) {

                    $this->autoRender = false;

                    if ($this->Config->is('notemsg')) {

                        if (!empty($this->request->data['json'])) {
                            $idUser = $this->getIdSession();
                            $datas = json_decode($this->request->data['json']);
                            $thumb = $this->Note->search($idUser, $datas->toUser, $datas->id);
                        }

                        if (isset($thumb) && !empty($thumb) && isset($thumb['Note']['type'])) {
                            if ($thumb['Note']['type'] == $datas->type) {
                                $this->logforum($idUser, 'remove_thumb', '', '');
                                $this->Note->removeThumb($thumb['Note']['id']);
                                echo 'reverse';
                            } elseif ($thumb['Note']['type'] != $datas->type) {
                                $this->logforum($idUser, 'remove_thumb', '', '');
                                $this->Note->removeThumb($thumb['Note']['id']);
                                echo 'reset';
                            }
                        } elseif (!empty($this->request->data['idUpdate'])) {
                            $id = $this->request->data['idUpdate'];
                            $message = $this->Topic->getUniqMessage($id)['content'];
                            echo $message;
                        } else {
                            $this->logforum($idUser, 'add_thumb', '', '');
                            $this->Note->addThumb($idUser, $datas->toUser, $datas->id, $datas->type);
                            echo 'normal';
                        }
                    } else {
                        throw new ForbiddenException();
                    }
                } else {
                    throw new ForbiddenException();
                }
            } else {
                if ($this->request->is('post')) {
                    if ($this->isConnected) {
                        if (!empty($this->request->data['content'])) {
                            if (!$lock || $this->ForumPermission->has('FORUM_TOPIC_LOCK')) {
                                $content = $this->removeScript($this->request->data['content']);
                                $content = $this->word($content);

                                $this->Topic->addMessage($this->Topic->info('id_parent', $id), $this->getIdSession(), $id, $content, date('Y-m-d H:i:s'));
                                $this->logforum($this->getIdSession(), 'add_message', $this->gUBY($this->getIdSession()) . $this->Lang->get('FORUM__PHRASE__HISTORY__POST__MSG') . strip_tags(substr($content, 0, 30)), $content);

                                $author = $this->Topic->info('topic_author', $id);
                                $title = $this->Topic->getTitleTopic($id);
                                if ($this->getIdSession() != $author) {
                                    $this->notification('respond_topic', $author, $this->getIdSession(), 'User', $title);
                                }

                                $this->Session->setFlash($this->Lang->get('FORUM__MESSAGE__SEND'), 'default.success');

                                $this->redirect("/forum/redirect/message/$id");
                            }
                        } elseif (!empty($this->request->data['content_update'])) {
                            $idMessage = $this->request->data['id'];
                            $content = $this->removeScript($this->request->data['content_update']);

                            $content = $this->word($content);

                            if ($this->ForumPermission->has('FORUM_MSG_EDIT') or $this->ForumPermission->has('FORUM_MSGMY_EDIT')) {
                                if ($this->ForumPermission->has('FORUM_MSG_EDIT')) {
                                    $state = true;
                                } elseif ($this->ForumPermission->has('FORUM_MSGMY_EDIT')) {
                                    $state = ($this->Topic->getUserId('id_user', 'id', $idMessage) == $this->getIdSession()) ? true : false;
                                }

                                if ($state) {
                                    $this->Topic->updateMessage($idMessage, $content);
                                    $this->logforum($this->getIdSession(), 'add_message', $this->gUBY($this->getIdSession()) . $this->Lang->get('FORUM__PHRASE__HISTORY__EDIT__MSG') . strip_tags(substr($content, 0, 30)), $content);
                                    $this->Session->setFlash($this->Lang->get('FORUM__MESSAGE__EDITED'), 'default.success');
                                } else {
                                    $this->Session->setFlash($this->Lang->get('FORUM__PERMISSION_NECESSARY'), 'default.error');
                                }
                            }
                        } elseif (!empty($this->request->data['content_report'])) {
                            if ($this->Config->is('reportmsg')) {
                                if ($this->ForumPermission->has('FORUM_MSG_REPORT')) {

                                    $idMessage = $this->request->data['id'];
                                    if ($this->Topic->getUserId('id_user', 'id', $idMessage) != $this->getIdSession()) {
                                        $reason = $this->request->data['reason'];
                                        $content = $this->request->data['content_report'];
                                        $this->MsgReport->report($this->getIdSession(), $idMessage, date('Y-m-d H:i:s'), $reason, $content);
                                        $this->logforum($this->getIdSession(), 'add_message', $this->gUBY($this->getIdSession()) . $this->Lang->get('FORUM__PHRASE__HISTORY__REPORT__MSG') . strip_tags(substr($content, 0, 30)) . $this->Lang->get('FORUM__PHRASE__HISTORY_FOR__REASON') . $reason, $content);
                                        $this->Session->setFlash($this->Lang->get('FORUM__REPORT__SEND'), 'default.success');
                                    }
                                } else {
                                    $this->Session->setFlash($this->Lang->get('FORUM__PERMISSION_NECESSARY'), 'default.error');
                                }
                            } else {
                                $this->Session->setFlash($this->Lang->get('FORUM__PHRASE__FLASH__NOTREPORT__MSG'), 'default.error');
                            }
                        } elseif (!empty($this->request->data['delete'])) {
                            $idMessage = $this->request->data['delete'];

                            if ($this->ForumPermission->has('FORUM_MSG_DELETE') or $this->ForumPermission->has('FORUM_MSGMY_DELETE')) {
                                if ($this->ForumPermission->has('FORUM_MSG_DELETE')) {
                                    $state = true;
                                } elseif ($this->ForumPermission->has('FORUM_MSGMY_DELETE')) {
                                    $state = ($this->Topic->getUserId('id_user', 'id', $idMessage) == $this->getIdSession()) ? true : false;
                                }
                                if ($state) {

                                    $idTopic = $this->Topic->info('id_topic_alt', $idMessage);
                                    $title = $this->Topic->getTitleTopic($idTopic);
                                    $idUser = $this->Topic->info('topic_author', $idTopic);

                                    if ($this->Topic->determineIsTopic($idMessage)) {
                                        if ($this->ForumPermission->has('FORUM_TOPIC_DELETE') or $this->ForumPermission->has('FORUM_TOPICMY_DELETE')) {
                                            if ($this->ForumPermission->has('FORUM_TOPIC_DELETE')) {
                                                $state = true;
                                            } elseif ($this->ForumPermission->has('FORUM_TOPICMY_DELETE')) {
                                                $state = ($this->Topic->getUserId('id_user', 'id', $idMessage) == $this->getIdSession()) ? true : false;
                                            }
                                            if ($state) {
                                                $this->Topic->deleteMessages($idTopic);

                                                if ($idUser != $this->getIdSession()) {
                                                    $this->notification('delete_topic', $idUser, $this->getIdSession(), 'User', $title);
                                                }

                                                $this->logforum($this->getIdSession(), 'delete_topic', $this->gUBY($this->getIdSession()) . $this->Lang->get('FORUM__PHRASE__HISTORY__DELETE__TOPIC'), $title);

                                                $this->redirect(Router::url('/', true));
                                            } else {
                                                $this->Session->setFlash($this->Lang->get('FORUM__PERMISSION_NECESSARY'), 'default.error');
                                            }
                                        } else {
                                            $this->Session->setFlash($this->Lang->get('FORUM__PERMISSION_NECESSARY'), 'default.error');
                                        }
                                    } else {
                                        $this->Topic->deleteMessage($idMessage);

                                        if ($idUser != $this->getIdSession()) {
                                            $this->notification('delete_message', $idUser, $this->getIdSession(), 'User', $title);
                                        }

                                        $this->logforum($this->getIdSession(), 'delete_message', $this->gUBY($this->getIdSession()) . $this->Lang->get('FORUM__PHRASE__HISTORY__DELETE__MSG'), '');
                                        $this->Session->setFlash($this->Lang->get('FORUM__MESSAGE__DELETE'), 'default.success');
                                    }
                                } else {
                                    $this->Session->setFlash($this->Lang->get('FORUM__PERMISSION_NECESSARY'), 'default.error');
                                }
                            }
                        } elseif (!empty($this->request->data['deleteall'])) {
                            if ($this->ForumPermission->has('FORUM_TOPIC_DELETE') or ($this->ForumPermission->has('FORUM_TOPICMY_DELETE') && $this->Topic->getUserId('id_user', 'id_topic',  $id) == $this->getIdSession())) {
                                $this->Topic->deleteMessages($id);

                                $idTopic = $this->Topic->getIdTopic($id);
                                $title = $this->Topic->getTitleTopic($idTopic);

                                $idUser = $this->Topic->info('topic_author', $idTopic);

                                if ($idUser != $this->getIdSession()) {
                                    $this->notification('delete_topic', $idUser, $this->getIdSession(), 'User', $title);
                                }

                                $this->logforum($this->getIdSession(), 'delete_topic', $this->gUBY($this->getIdSession()) . $this->Lang->get('FORUM__PHRASE__HISTORY__DELETE__TOPIC'), $this->replaceHyppen($slug));

                                $this->redirect(Router::url('/', true));
                            } else {
                                throw new ForbiddenException();
                            }
                        } elseif (!empty($this->request->data['stick'])) {
                            if ($this->ForumPermission->has('FORUM_TOPIC_STICK')) {

                                $idTopic = $this->request->data['stick'];

                                if ($stick) {
                                    $this->Topic->change('unstick', $idTopic);
                                    $this->logforum($this->getIdSession(), 'unstick_topic', $this->gUBY($this->getIdSession()) . $this->Lang->get('FORUM__PHRASE__HISTORY__UNSTICK__TOPIC'), $this->replaceHyppen($slug));
                                    $this->Session->setFlash($this->Lang->get('FORUM__TOPIC__UNSTICK'), 'default.success');
                                } else {
                                    $this->Topic->change('stick', $this->request->data['stick']);
                                    $this->logforum($this->getIdSession(), 'stick_topic', $this->gUBY($this->getIdSession()) . $this->Lang->get('FORUM__PHRASE__HISTORY__STICK__TOPIC'), $this->replaceHyppen($slug));

                                    $idUser = $this->Topic->info('topic_author', $idTopic);
                                    $title = $this->Topic->getTitleTopic($idTopic);

                                    if ($idUser != $this->getIdSession()) {
                                        $this->notification('stick_topic', $idUser, $this->getIdSession(), 'User', $title);
                                    }

                                    $this->Session->setFlash($this->Lang->get('FORUM__TOPIC__STICK'), 'default.success');
                                }

                                $this->redirect('/topic/' . $slug . '.' . $id . '/');
                            } else {
                                throw new ForbiddenException();
                            }
                        } elseif (!empty($this->request->data['lock'])) {
                            $idUser = $this->Topic->info('topic_author', $idTopic);

                            if ($this->ForumPermission->has('FORUM_TOPIC_LOCK') || ($this->ForumPermission->has('FORUM_TOPICMY_LOCK') && $idUser == $this->getIdSession())) {

                                $idTopic = $this->request->data['lock'];
                                $title = $this->Topic->getTitleTopic($idTopic);

                                if ($lock) {
                                    $this->Topic->change('unlock', $idTopic);

                                    if ($idUser != $this->getIdSession()) {
                                        $this->notification('unlock_topic', $idUser, $this->getIdSession(), 'User', $title);
                                    }

                                    $this->logforum($this->getIdSession(), 'unlock_topic', $this->gUBY($this->getIdSession()) . $this->Lang->get('FORUM__PHRASE__HISTORY__UNLOCK__TOPIC'), $this->replaceHyppen($slug));
                                    $this->Session->setFlash($this->Lang->get('FORUM__TOPIC__UNLOCK'), 'default.success');
                                } else {
                                    $this->Topic->change('lock', $idTopic);

                                    if ($idUser != $this->getIdSession()) {
                                        $this->notification('lock_topic', $idUser, $this->getIdSession(), 'User', $title);
                                    }

                                    $this->logforum($this->getIdSession(), 'lock_topic', $this->gUBY($this->getIdSession()) . $this->Lang->get('FORUM__PHRASE__HISTORY__LOCK__TOPIC'), $this->replaceHyppen($slug));
                                    $this->Session->setFlash($this->Lang->get('FORUM__TOPIC__LOCK'), 'default.success');
                                }

                                $this->redirect('/topic/' . $slug . '.' . $id . '/');
                            } else {
                                throw new ForbiddenException();
                            }
                        } else {
                            $this->Session->setFlash($this->Lang->get('FORUM__TOPIC__MESSAGE_ERROR'), 'default.danger');
                        }
                    } else {
                        throw new ForbiddenException();
                    }
                }

                $paginationDb = $this->Topic->pagination('topic', $id);
                $pagination['html'] = $this->forumRender('pagination', ['data' => 'e', 'style' => 'sm', 'page' => $page, 'nbpage' => $paginationDb['nbpage']]);
                $msgs = $this->Topic->getMessage($id, $page);
                $title = $this->Topic->info('title_parent', $id);

                $breadcrumb = $this->breadcrumb('topic', $id);

                foreach ($msgs as $key => $msg) {
                    $user_id = $msg['Topic']['id_user'];
                    $msgs[$key]['Topic']['thumb_info']['green'] = $this->Note->isNoted('green', $msg['Topic']['id'], $this->getIdSession());
                    $msgs[$key]['Topic']['thumb_info']['red'] = $this->Note->isNoted('red', $msg['Topic']['id'], $this->getIdSession());
                    $msgs[$key]['Topic']['author_info']['nb_message'] = $this->Topic->getNbMessage('user', $user_id);
                    $msgs[$key]['Topic']['author_info']['thumb']['green'] = $this->Note->getNbThumb('green', $user_id);
                    $msgs[$key]['Topic']['author_info']['thumb']['red'] = $this->Note->getNbThumb('red', $user_id);
                    $msgs[$key]['Topic']['author_info']['inscription'] = $this->date($this->dateInscription($user_id));
                    $msgs[$key]['Topic']['author_info']['rank'] = $this->ForumPermission->getRank($user_id);
                    $msgs[$key]['Topic']['author_info']['color'] = $this->ForumPermission->getRankColor($user_id);
                    $msgs[$key]['Topic']['author_info']['sign'] = $this->Profile->get($user_id)['description'];
                    $msgs[$key]['Topic']['author_color'] = $this->ForumPermission->getRankColorDomin($user_id);

                    $msgs[$key]['Topic']['author'] = $this->gUBY($user_id);
                    $msgs[$key]['Topic']['date'] = $this->dateAndTime($msg['Topic']['date'], '%d %B %Y');

                    if ($key == 0) {
                        $parent['forum_parent']['name'] = ($this->Forum->info('parent_title', $msgs[0]['Topic']['id_parent'])) ? $this->Forum->info('parent_title', $msgs[0]['Topic']['id_parent'])['Forum']['forum_name'] : '';
                        $parent['forum_parent']['href'] = (!empty($parent['forum_parent']['name'])) ? $this->replaceSpace($parent['forum_parent']['name']) . "." . $this->Forum->info('parent_title', $msgs[0]['Topic']['id_parent'])['Forum']['id'] : '';
                    }

                    $msgs[$key]['Topic']['thumb']['green'] = $this->Note->getNbThumb('msg_green', $msg['Topic']['id']);
                    $msgs[$key]['Topic']['thumb']['red'] = $this->Note->getNbThumb('msg_red', $msg['Topic']['id']);
                }
                $active['notemsg'] = ($this->Config->is('notemsg')) ? true : false;
                $active['reportmsg'] = ($this->Config->is('reportmsg')) ? true : false;
                $perms = $this->perm_l();
                $theme = $this->theme();
                $tags = $this->Tag->get();
                $topic = $this->Topic->getTag($id);

                $this->set('title_for_layout', $this->replaceHyppen($slug) . ' | ' . $this->Lang->get('FORUM__TITLE'));

                $this->set(compact(
                    'msgs',
                    'parent',
                    'active',
                    'perms',
                    'lock',
                    'id',
                    'stick',
                    'theme',
                    'pagination',
                    'title',
                    'tags',
                    'topic',
                    'breadcrumb'
                ));
            }
        } else {
            throw new NotFoundException('Le topic n\'existe pas !', 404);
        }
    }

    public function addTopic($idParent = false)
    {
        $this->set('title_for_layout', $this->Lang->get('FORUM__ADD__TOPIC'));

        $this->loadModel('Forum.Forums');
        $this->loadModel('Forum.Tag');
        $this->loadModel('Forum.Topic');

        if (!$this->Config->is('forum') or !$this->ForumPermission->has('FORUM_TOPIC_SEND')) {
            throw new NotFoundException();
        }

        $isLock = $this->Forum->isLock($idParent);
        $perms = $this->perm_l();
        $tags = $this->Tag->get();
        $ranks = $this->ForumPermission->getRanks();
        $theme = $this->theme();
        $newTags = "";

        $params['isEdit']['tag'] = $params['isEdit']['tagPublic'] = false;

        if ($this->ForumPermission->has('FORUM_TAG_TOPIC')) {
            $params['isEdit']['tag'] = true;
        }

        if ($this->ForumPermission->has('FORUM_TAG_PUBLIC')) {
            $params['isEdit']['tagPublic'] = true;
        }

        if (!$isLock or $perms['FORUM_TOPIC_LOCK']) {
            if ($this->request->is('post') && $this->isConnected) {
                if (!empty($this->request->data['title']) && !empty($this->request->data['content_insert'])) {

                    $stick = $lock = 0;
                    if ($this->Forum->isAutoLock($idParent)) {
                        $lock = 1;
                    } else {
                        if ($this->ForumPermission->has('FORUM_TOPIC_STICK')) {
                            if (!empty($this->request->data['stick'])) $stick = 1;
                        }
                        if ($this->ForumPermission->has('FORUM_TOPIC_LOCK')) {
                            if (!empty($this->request->data['lock'])) $lock = 1;
                        }
                    }

                    $content = $this->removeScript($this->request->data['content_insert']);
                    $content = $this->word($content);
                    $title = $this->urlRew(trim($this->request->data['title']));

                    $param = $this->Topic->addTopic($idParent, $this->getIdSession(), $title, $stick, $lock, $content);

                    if ($this->ForumPermission->has('FORUM_TAG_TOPIC')) {
                        foreach ($tags as $key => $tag) {
                            if (!empty($this->request->data['tag-' . $tag['Tag']['id']])) {
                                $newTags .= $tag['Tag']['id'] . ',';
                            }
                        }
                    } elseif ($this->ForumPermission->has('FORUM_TAG_PUBLIC')) {
                        foreach ($tags as $key => $tag) {
                            if (!empty($this->request->data['tag-' . $tag['Tag']['id']])) {
                                foreach ($ranks as $key => $rank) {
                                    if (in_array(explode(',', $tag['Tag']['used']), $rank['Group']['id'])) {
                                        $can = true;
                                        break;
                                    }
                                }

                                if ($can) {
                                    $newTags .= $tag['Tag']['id'] . ',';
                                }
                            }
                        }
                    }

                    if ($perms['FORUM_MSG_EDIT']) $this->Topic->updateTag($param['id_topic'], $newTags);

                    $this->logforum($this->getIdSession(), 'create_topic', $this->gUBY($this->getIdSession()) . ' vient de créer un nouveau topic : ' . strip_tags(substr($content, 0, 30)), $content);

                    $this->redirect(Router::url('/', true) . 'topic/' . $this->replaceSpace($param['title']) . '.' . $param['id_topic'] . '/');
                } else {
                    if (!empty($this->request->data['title'])) {
                        $this->Session->setFlash('Vous devez insérer un titre à votre topic !', 'default.error');
                    } elseif (!empty($this->request->data['content_insert'])) {
                        $this->Session->setFlash('Votre topic doit contenir un message !', 'default.error');
                    }
                }

                $this->set(compact('perms', 'theme', 'tags', 'params', 'ranks'));
            } elseif ($this->request->is('get') && $this->isConnected) {

                $this->set(compact('perms', 'theme', 'tags', 'params', 'ranks'));
            } else {
                throw new ForbiddenException();
            }
        } else {
            throw new ForbiddenException();
        }
    }

    public function editTopic($idMessage)
    {
        $this->set('title_for_layout', $this->Lang->get('FORUM__EDIT__TOPIC'));

        $this->loadModel('Forum.Tag');
        $this->loadModel('Forum.Topic');

        if (!$this->Config->is('forum')) {
            throw new NotFoundException();
        }

        $tags = $this->Tag->get();
        $ranks = $this->ForumPermission->getRanks();
        $theme = $this->theme();
        $state = false;

        if ($this->isConnected) {
            if ($this->ForumPermission->has('FORUM_MSG_EDIT') or $this->ForumPermission->has('FORUM_MSGMY_EDIT')) {
                if ($this->ForumPermission->has('FORUM_MSG_EDIT')) {
                    $state = true;
                } elseif ($this->ForumPermission->has('FORUM_MSGMY_EDIT')) {
                    $state = ($this->Topic->getUserId('id_user', 'id', $idMessage) == $this->getIdSession()) ? true : false;
                }
                if ($state) {
                    if ($this->request->is('post')) {

                        $newMessage = $this->removeScript($this->request->data['content']);

                        $this->Topic->updateMessage($idMessage, $newMessage);

                        $content = $this->Topic->getUniqMessage($idMessage);

                        //Si c'est le premier message du topic
                        if ($content['first']) {
                            $idTopic = $content['id_topic'];
                            $title = $this->request->data['title'];

                            if (($this->getIdSession() == $content['id_user'] && $this->ForumPermission->has('FORUM_RENAMEMY_TOPIC')) || $this->ForumPermission->has('FORUM_RENAME_TOPIC')) {
                                $this->Topic->rename($idTopic, $title);
                            }

                            $newTags = "";

                            if ($this->ForumPermission->has('FORUM_TAG_TOPIC')) {
                                foreach ($tags as $key => $tag) {
                                    if (!empty($this->request->data['tag-' . $tag['Tag']['id']])) {
                                        $newTags .= $tag['Tag']['id'] . ',';
                                    }
                                }
                            } elseif (($this->getIdSession() == $content['id_user'] && $this->ForumPermission->has('FORUM_TAG_PUBLIC'))) {
                                foreach ($tags as $key => $tag) {
                                    if (!empty($this->request->data['tag-' . $tag['Tag']['id']])) {
                                        foreach ($ranks as $key => $rank) {
                                            if (in_array(explode(',', $tag['Tag']['used']), $rank['Group']['id'])) {
                                                $can = true;
                                                break;
                                            }
                                        }

                                        if ($can) {
                                            $newTags .= $tag['Tag']['id'] . ',';
                                        }
                                    }
                                }
                            }

                            $this->Topic->updateTag($idTopic, $newTags);
                        }

                        $this->logforum($this->getIdSession(), 'add_message', $this->gUBY($this->getIdSession()) . $this->Lang->get('FORUM__PHRASE__HISTORY__EDIT__MSG') . strip_tags(substr($title, 0, 30)), $title);
                        $this->Session->setFlash($this->Lang->get('FORUM__MESSAGE__EDITED'), 'default.success');

                        return $this->redirect('/topic/' . $title . '.' . $idTopic . '/');
                    } else {
                        $topic = "";
                        $params['isEdit']['title'] = $params['isEdit']['tag'] = $params['isEdit']['tagPublic'] = false;

                        $content = $this->Topic->getUniqMessage($idMessage);

                        if ($content['first']) {
                            $idTopic = $content['id_topic'];
                            $params['title'] = $this->Topic->getTitleTopic($idTopic);

                            if (($this->getIdSession() == $content['id_user']) || $this->ForumPermission->has('FORUM_MSG_EDIT')) {
                                $params['isEdit']['title'] = true;
                            }

                            if ($this->ForumPermission->has('FORUM_TAG_TOPIC')) {
                                $params['isEdit']['tag'] = true;
                            }

                            if (($this->getIdSession() == $content['id_user']) && $this->ForumPermission->has('FORUM_TAG_PUBLIC')) {
                                $params['isEdit']['tagPublic'] = true;
                            }
                        }

                        $this->set(compact('content', 'theme', 'params', 'tags', 'ranks'));
                    }
                } else {
                    throw new ForbiddenException();
                }
            }
        } else {
            throw new ForbiddenException();
        }
    }

    public function report()
    {
        if ($this->ForumPermission->has('FORUM_VIEW_REPORT')) {

            $this->set('title_for_layout', $this->Lang->get('FORUM__MSGREPORT'));
            $this->loadModel('Forum.MsgReport');
            $this->loadModel('Forum.Topic');
            if ($this->request->is('post')) {
                $id = $this->request->data['id'];
                $this->logforum($this->getIdSession(), 'delete_report', $this->gUBY($this->getIdSession()) . ' vient de supprimer un signalement', $id);
                $this->MsgReport->deleteReport($id);
            }

            $msgreports = $this->MsgReport->get();
            foreach ($msgreports as $key => $msgreport) {
                $msgreports[$key]['MsgReport']['user'] = $this->gUBY($msgreport['MsgReport']['id_user']);
                $msgreports[$key]['MsgReport']['date'] = $this->dateAndTime($msgreport['MsgReport']['date']);
                $idTopic = $this->Topic->getUniqMessage($msgreport['MsgReport']['id_msg'])['id_topic'];
                $msgreports[$key]['MsgReport']['href'] = $this->buildUri('topic', $this->Topic->getTitleTopic($idTopic), $idTopic);
            }

            $theme = $this->theme();
            $this->set(compact('msgreports', 'theme'));
        } else {
            throw new ForbiddenException();
        }
    }

    public function banned()
    {
        if ($this->isConnected && $this->Punishment->get($this->getIdSession())) {

            $this->set('title_for_layout', $this->Lang->get('FORUM__BANNED'));

            $infos = $this->Punishment->get($this->getIdSession());
            $infos['date'] = $this->dateAndTime($infos['date']);
            $infos['user'] = $this->gUBY($infos['id_user']);

            $theme = $this->theme();
            $this->set(compact('infos', 'theme'));
        } else {
            throw new NotFoundException();
        }
    }

    /*
     * Admin pages
     */

    public function admin_index()
    {
        if ($this->isConnected and $this->User->isAdmin()) {

            $this->loadModel('Forum.Topic');
            $this->loadModel('Forum.Note');

            $configs = $this->Config->get();
            $stats = $this->Topic->stats();

            if ($this->request->is('ajax')) {

                $this->autoRender = false;

                if (!empty($this->request->data['config'])) {
                    foreach ($configs as $config) {
                        $this->Config->updateConfig($config['Config']['config_name'], $this->request->data[$config['Config']['config_name']]);
                    }

                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__CONFIG__SUCCESS'))));
                } elseif (!empty($this->request->data['drop'])) {
                    $this->dropHistory();
                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__HISTORY__DROP'))));
                } elseif (!empty($this->request->data['install'])) {
                    $this->install();
                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__CONFIG__INSTALLED'))));
                } else {
                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('FORUM__ERROR'))));
                }
            } else {
                $this->layout = 'admin';

                $userOnlines = $this->Forum->userOnline($this->User);
                foreach ($userOnlines as $key => $userOnline) {
                    $userOnlines[$key]['User']['color'] = @$this->ForumPermission->getRankColorDomin($userOnline['User']['id']);
                }

                $stats['countuser'] = count($userOnlines);
                $stats['thumbgreen'] = $this->Note->getNbThumb('total_green');
                $stats['thumbred'] = $this->Note->getNbThumb('total_red');
                $remoteMsg['info'] = $this->remoteAction('ADMINMSG');
                $remoteMsg['changelog'] = $this->remoteAction('CHANGELOG');
                $remoteMsg['nextupdate'] = $this->remoteAction('NEXTUPDATE');

                $this->set(compact('configs', 'stats', 'userOnlines', 'remoteMsg'));
            }
        } else {
            $this->redirect('/');
        }
    }

    public function admin_forum()
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->layout = 'admin';
            $this->loadModel('Forum.forums');

            $forums = $this->Forum->getForum('forum');
            $this->set(compact('forums'));
        } else {
            $this->redirect('/');
        }
    }

    public function admin_category()
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->layout = 'admin';
            $this->loadModel('Forum.forums');
            $forums = $this->Forum->getForum('withoutforum');
            foreach ($forums as $key => $forum) {
                $forums[$key]['Forum']['parent'] = $this->Forum->info('parent_title', $forum['Forum']['id_parent'])['Forum']['forum_name'];
            }

            $this->set(compact('forums'));
        } else {
            $this->redirect('/');
        }
    }

    public function admin_add_forum()
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->set('title_for_layout', $this->Lang->get('FORUM__ADD__FORUM'));

            $this->layout = 'admin';
            $this->loadModel('Forum.forums');
            $forums = $this->Forum->getForum('forum');
            $ranks = $this->ForumPermission->getRanks();

            if ($this->request->is('ajax')) {
                $this->autoRender = false;
                if (!empty($this->request->data['name']) && !empty($this->request->data['position']) && !empty($this->request->data['image'])) {
                    $this->Forum->addForum($this->getIdSession(), $this->request->data['name'], $this->request->data['position'], $this->request->data['image'], $this->request->data['description']);

                    foreach ($ranks as $key => $r) {
                        if (isset($this->request->data[$r['Group']['id']])) {
                            $visible[$r['Group']['id']] = $this->request->data[$r['Group']['id']];
                        }
                    }
                    if (!empty($visible))  $visible = serialize($visible);
                    else $visible = '';

                    $this->Forum->updateVisible($this->Forum->getLastInsertID(), $visible);

                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__ADD__SUCCESS'))));
                } else {
                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('FORUM__ADD__FAILED'))));
                }
            }
            $this->set(compact('forums', 'ranks'));
        } else {
            $this->redirect('/');
        }
    }

    public function admin_add_category()
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->set('title_for_layout', $this->Lang->get('FORUM__ADD__CATEGORY'));
            $this->layout = 'admin';
            $this->loadModel('Forum.forums');
            $forums = $this->Forum->getForum();
            $ranks = $this->ForumPermission->getRanks();

            if ($this->request->is('ajax')) {
                $this->autoRender = false;
                if (!empty($this->request->data['name']) && !empty($this->request->data['position']) && !empty($this->request->data['parent']) && !empty($this->request->data['image'])) {
                    $name = $this->urlRew($this->request->data['name']);
                    $position = $this->request->data['position'];
                    $parent = $this->request->data['parent'];
                    $image = $this->request->data['image'];
                    $lock = (!empty($this->request->data['lock'])) ? 1 : 0;
                    $automaticLock = (!empty($this->request->data['automatic_lock'])) ? 1 : 0;
                    $this->Forum->addCategory($this->getIdSession(), $name, $position, $parent, $image, $lock, $automaticLock);

                    foreach ($ranks as $key => $r) {
                        if (isset($this->request->data[$r['Group']['id']])) {
                            $visible[$r['Group']['id']] = $this->request->data[$r['Group']['id']];
                        }
                    }
                    if (!empty($visible))  $visible = serialize($visible);
                    else $visible = '';

                    $this->Forum->updateVisible($this->Forum->getLastInsertID(), $visible);

                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__ADD__SUCCESS'))));
                } else {
                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('FORUM__ADD__FAILED'))));
                }
            }
            $this->set(compact('forums', 'ranks'));
        } else {
            $this->redirect('/');
        }
    }

    public function admin_delete($type = false, $id = false)
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->autoRender = false;
            if ($this->request->is('get')) {
                if ($type != false && $id != false) {

                    $this->loadModel('Forum.forums');
                    $this->loadModel('Forum.Insult');
                    $this->loadModel('Forum.Group');
                    $this->loadModel('Forum.Topic');
                    $this->loadModel('Forum.MsgReport');
                    $this->loadModel('Forum.Punishment');
                    $this->loadModel('Forum.Tag');

                    if ($type == 'forum') {
                        $this->logforum($this->getIdSession(), 'delete_forum', $this->gUBY($this->getIdSession()) . ' vient de supprimer un forum ', $id);
                        $this->Forum->admin_delete($id);
                    } elseif ($type == 'category') {
                        $this->logforum($this->getIdSession(), 'delete_category', $this->gUBY($this->getIdSession()) . ' vient de supprimer une catégorie ', $id);
                        $this->Forum->admin_delete($id);
                    } elseif ($type == 'word') {
                        $this->logforum($this->getIdSession(), 'delete_word', $this->gUBY($this->getIdSession()) . ' vient de supprimer un mot interdit', $id);
                        $this->Insult->deleteWord($id);
                    } elseif ($type == 'rank') {
                        $this->logforum($this->getIdSession(), 'delete_group', $this->gUBY($this->getIdSession()) . ' vient de supprimer un groupe', $id);
                        $this->Group->deleteGroup($id);
                        $this->ForumPermission->delete('groupsuser', 'id_group', $id);
                        $this->ForumPermission->deleteGroupPermission($id);
                    } elseif ($type == 'permission') {
                        $this->loadModel('Forum.ForumPermission');

                        $this->logforum($this->getIdSession(), 'delete_permission', $this->gUBY($this->getIdSession()) . ' vient de supprimer une permission', $id);
                        $this->ForumPermission->deletePermission($id);
                    } elseif ($type == 'report') {
                        $this->logforum($this->getIdSession(), 'delete_report', $this->gUBY($this->getIdSession()) . ' vient de supprimer un signalement', $id);
                        $this->MsgReport->deleteReport($id);
                    } elseif ($type == 'punishment') {
                        $this->logforum($this->getIdSession(), 'delete_punishment', $this->gUBY($this->getIdSession()) . ' vient de supprimer une sanction', $id);
                        $this->Punishment->deletePunish($id);
                    } elseif ($type == 'topic') {
                        $this->logforum($this->getIdSession(), 'delete_topic', $this->gUBY($this->getIdSession()) . ' vient de supprimer un topic', $id);
                        $this->Topic->deleteMessages($id);
                    } elseif ($type == 'tag') {
                        $this->logforum($this->getIdSession(), 'delete_tag', $this->gUBY($this->getIdSession()) . ' vient de supprimer un tag', $id);
                        $this->Tag->deleteTag($id);
                    }

                    $this->redirect(Router::url('/', true) . 'admin/forum/forum/' . $type);
                } else {
                    throw new ForbiddenException();
                }
            } else {
                throw new ForbiddenException();
            }
        } else {
            $this->redirect('/');
        }
    }

    public function admin_edit($type = false, $id = false)
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->loadModel('Forum.forums');
            $this->loadModel('Forum.Historie');
            $this->loadModel('Forum.MsgReport');
            $this->loadModel('Forum.Note');
            $this->loadModel('Forum.Profile');
            $this->loadModel('Forum.Topic');

            if ($this->request->is('ajax')) {
                $this->autoRender = false;
                if (!empty($this->request->data['name']) && !empty($this->request->data['position']) && !empty($this->request->data['image'])) {
                    $name = str_replace("'", "&#x27;", $this->request->data['name']);
                    $position = $this->request->data['position'];
                    $image = $this->request->data['image'];
                    $description = str_replace("'", "&#x27;", $this->request->data['description']);

                    $this->Forum->update('forum', $this->request->data['id'], ['name' => $name, 'position' => $position, 'image' => $image, 'forum_description' => $description]);

                    $ranks = $this->ForumPermission->getRanks();
                    foreach ($ranks as $key => $r) {
                        if (isset($r['Group']['id'])) {
                            $visible[$r['Group']['id']] = $this->request->data[$r['Group']['id']];
                        }
                    }
                    if (!empty($visible))  $visible = serialize($visible);
                    else $visible = '';

                    $this->Forum->updateVisible($this->request->data['id'], $visible);

                    $this->logforum($this->getIdSession(), 'create_forum', $this->gUBY($this->getIdSession()) . $this->Lang->get('FORUM__PHRASE__HISTORY__CREATE__FORUM') . strip_tags(substr($name, 0, 30)) . ' en position : ' . $position, $name, $image);

                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__ADD__SUCCESS'))));
                } elseif (!empty($this->request->data['name_category'])) {
                    $name = str_replace("'", "&#x27;", $this->urlRew($this->request->data['name_category']));
                    $parent = $this->request->data['parent'];
                    $position = $this->request->data['position'];
                    $image = $this->request->data['image'];
                    $lock = $this->request->data['lock'];
                    $automaticLock = $this->request->data['automatic_lock'];

                    $this->Forum->update('category', $this->request->data['id'], ['name' => $name, 'id_parent' => $parent, 'position' => $position, 'forum_image' => $image, 'lock' => $lock, 'automatic_lock' => $automaticLock]);

                    $ranks = $this->ForumPermission->getRanks();
                    foreach ($ranks as $key => $r) {
                        if (isset($r['Group']['id'])) {
                            $visible[$r['Group']['id']] = $this->request->data[$r['Group']['id']];
                        }
                    }
                    if (!empty($visible))  $visible = serialize($visible);
                    else $visible = '';

                    $this->Forum->updateVisible($this->request->data['id'], $visible);

                    $this->logforum($this->getIdSession(), 'create_forum', $this->gUBY($this->getIdSession()) . $this->Lang->get('FORUM__PHRASE__HISTORY__EDIT__CATEGORY'), $name);

                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__ADD__SUCCESS'))));
                } elseif (!empty($this->request->data['useredit'])) {

                    $this->Profile->updateProfile($this->request->data['description'], $this->request->data['useredit']);
                    $idGroups = $this->request->data['idgroup'];
                    $groups = explode(',', $idGroups);
                    $domin = (isset($this->request->data['domin'])) ? $this->request->data['domin'] : false;

                    foreach ($groups as $key => $value) {
                        $this->ForumPermission->updateGroup($this->request->data['rank_' . $value], $domin, $this->request->data['useredit'], $value);
                    }

                    $this->logforum($this->getIdSession(), 'edit_permission', $this->gUBY($this->getIdSession()) . $this->Lang->get('FORUM__PHRASE__HISTORY__EDITPERM__USER') . $this->gUBY($this->request->data['useredit']), '');
                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__USER__EDIT'))));
                } elseif (!empty($this->request->data['color']) && !empty($this->request->data['name']) && !empty($this->request->data['position'])) {

                    $id = $this->request->data['id'];
                    $name = $this->request->data['name'];
                    $description = $this->request->data['description'];
                    $color = $this->request->data['color'];
                    $position = $this->request->data['position'];

                    $color = str_replace('#', '', $color);

                    if (is_numeric($position)) {
                        $this->ForumPermission->updateRank($name, $description, $color, $id, $position);
                        $this->logforum($this->getIdSession(), 'edit_rank', $this->gUBY($this->getIdSession()) . $this->Lang->get('FORUM__PHRASE__HISTORY__EDIT__GROUP'), $name);
                        $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__ADD__SUCCESS'))));
                    } else {
                        $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('FORUM__ADD__FAILED'))));
                    }
                } elseif (!empty($this->request->data['social'])) {

                    $facebook = $this->request->data['facebook'];
                    $twitter = $this->request->data['twitter'];
                    $youtube = $this->request->data['youtube'];
                    $googleplus = $this->request->data['googleplus'];
                    $snapchat = $this->request->data['snapchat'];

                    $socials = json_encode([
                        'facebook' => $facebook,
                        'twitter' => $twitter,
                        'youtube' => $youtube,
                        'googleplus' => $googleplus,
                        'snapchat' => $snapchat
                    ]);

                    $this->Profile->updateSocials($id, $socials);
                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__ADD__SUCCESS'))));
                } else {
                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('FORUM__ADD__FAILED'))));
                }
            } else {
                if ($type != false && $id != false) {
                    $this->layout = 'admin';
                    if ($type == 'forum') {

                        $datas = $this->Forum->info('forum', $id)[0]['Forum'];
                        $forums = $this->Forum->getForum('forum');
                        $ranks = $this->ForumPermission->getRanks();
                        $individual = unserialize($datas['visible']);

                        $this->set(compact('datas', 'forums', 'type', 'ranks', 'individual'));
                    } elseif ($type == 'category') {

                        $datas = $this->Forum->getForum('id', $id);
                        $datas['Forum']['actualparent'] = $this->Forum->info('parent_title', $datas['Forum']['id_parent'])['Forum']['forum_name'];
                        $forums = $this->Forum->getForum();
                        $categorys = $this->Forum->getForum('withoutforum');
                        $ranks = $this->ForumPermission->getRanks();
                        $individual = unserialize($datas['Forum']['visible']);

                        $this->set(compact('datas', 'forums', 'type', 'categorys', 'ranks', 'individual'));
                    } elseif ($type == 'user') {

                        $datas['user']['id'] = $id;
                        $datas['user']['username'] = $this->gUBY($id);
                        $datas['user']['lastseen'] = $this->dateAndTime($this->lastSeen($id));
                        $datas['rank']['idgroup'] = $this->ForumPermission->getIdGroup($id);
                        $datas['rank']['domin'] = $this->ForumPermission->getDomin($id);
                        $datas['rank']['rankbis'] = ($this->ForumPermission->getRank($id)) ? $this->ForumPermission->getRank($id, 'advanced') : '';
                        $datas['rank']['rank'] = $this->ForumPermission->getRanks();
                        $datas['profile'] = $this->Profile->get($id);
                        $datas['rank']['allrank'] = $this->ForumPermission->getRanks();
                        $datas['rank']['r'] = '';

                        $datas['thumb']['set']['red'] = $this->Note->getNbThumb('red', $id);
                        $datas['thumb']['set']['green'] = $this->Note->getNbThumb('green', $id);
                        $datas['thumb']['get']['red'] = $this->Note->stats('red', $id);
                        $datas['thumb']['get']['green'] = $this->Note->stats('green', $id);

                        $datas['nb']['topic'] = $this->Topic->getNbMessage('user_topic', $id);
                        $datas['nb']['message'] = $this->Topic->getNbMessage('user', $id);

                        $date = $this->lastSeen($id);
                        $datas['user']['isconnected'] = false;
                        if (date('Y-m-d H:i:s') < date('Y-m-d H:i:s', strtotime("$date +5 minutes"))) {
                            $datas['user']['isconnected'] = true;
                        }

                        $datas['user']['isbanned'] = "Non";
                        if ($this->Punishment->get($id) && !empty($this->Punishment->get($id))) {
                            $datas['user']['isbanned'] = "Oui";
                        }

                        foreach ($datas['rank']['allrank'] as $key => $r) {
                            $s = (!$key) ? '' : ',';
                            $datas['rank']['r'] .= $s . $r['Group']['id'];
                        }

                        $history = $this->Historie->_list($id);
                        foreach ($history as $key => $h) {
                            $history[$key]['Historie']['date'] = $this->dateAndTime($h['Historie']['date']);
                        }

                        $msgReport = $this->MsgReport->get($id);
                        foreach ($msgReport as $key => $m) {
                            $msgReport[$key]['MsgReport']['date'] = $this->dateAndTime($m['MsgReport']['date']);
                        }

                        $socialNetworks = $this->socialNetwork($id);

                        $this->set(compact('datas', 'type', 'history', 'msgReport', 'socialNetworks'));
                    } elseif ($type == 'rank') {
                        $datas = $this->ForumPermission->getRanks($id);
                        $this->set(compact('datas', 'type'));
                    } else {
                        throw new ForbiddenException();
                    }
                } else {
                    throw new ForbiddenException();
                }
            }
        } else {
            $this->redirect('/');
        }
    }

    public function admin_switch($type = false, $id = false)
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            if ($this->request->is('get')) {
                if ($type != false && $id != false) {
                    $this->autoRender = false;
                    if ($type == 'permission') {
                        $this->loadModel('Forum.ForumPermission');
                        $this->ForumPermission->_switch($id);
                        $this->logforum($this->getIdSession(), 'edit_group', $this->gUBY($this->getIdSession()) . ' vient de modifier l\'autorisation d\'un groupe : ' . $id, '');
                    }
                    $this->redirect('/admin/forum/forum/' . $type);
                }
            } else {
                throw new ForbiddenException();
            }
        } else {
            $this->redirect('/');
        }
    }

    public function admin_word()
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->loadModel('Forum.Insult');
            if ($this->request->is('ajax')) {
                $this->autoRender = false;
                if (!empty($this->request->data['word'])) {
                    $this->Insult->add($this->request->data['word'], $this->request->data['replace']);
                    $this->logforum($this->getIdSession(), 'add_word', $this->gUBY($this->getIdSession()) . 'vient d\'ajouter un mot interdit', $this->request->data['word'] . ' -> ' . $this->request->data['replace']);
                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__ADD__WORD'))));
                } else {
                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('FORUM__ADD__FAILED'))));
                }
            } else {
                $this->layout = 'admin';

                $words = $this->Insult->get();
                $this->set(compact('words'));
            }
        } else {
            $this->redirect('/');
        }
    }

    public function admin_history()
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->loadModel('Forum.Historie');
            if ($this->request->is('ajax')) {
                $this->autoRender = false;
                if (!empty($this->request->data['drop'])) {
                    $this->dropHistory();
                    $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__HISTORY__DROP'))));
                } else {
                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('FORUM__ERROR'))));
                }
            } else {
                $this->layout = 'admin';

                $history = $this->Historie->get();
                foreach ($history as $key => $h) {
                    $history[$key]['Historie']['pseudo'] = $this->gUBY($h['Historie']['id_user']);
                    $history[$key]['Historie']['date'] = $this->dateAndTime($h['Historie']['date']);
                }
                $this->set(compact('history'));
            }
        } else {
            $this->redirect('/');
        }
    }

    public function admin_permission()
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->loadModel('Forum.ForumPermission');
            $this->loadModel('Forum.Group');

            $permissions = $this->ForumPermission->get(1);
            $permissionsAll = $this->ForumPermission->get();
            $groups = $this->Group->get();
            $this->ForumPermission = $this->Components->load('Forum.ForumPermission');

            if ($this->request->is('ajax')) {
                $this->autoRender = false;
                foreach ($permissionsAll as $permission) {
                    $indexA = $permission['ForumPermission']['id'];
                    $idGroup = $permission['ForumPermission']['group_id'];
                    $index = $idGroup . '-' . $indexA;
                    $this->ForumPermission->updatePermission($this->request->data[$index], $indexA);
                }
                return $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__ADD__SUCCESS'))));
            } else {
                $this->layout = 'admin';
                foreach ($permissions as $key => $permission) {
                    $rank = $this->Group->getName($permission['ForumPermission']['group_id']);
                    $permissions[$key]['ForumPermission']['group_name'] = ($rank) ? $rank : $this->Lang->get('FORUM__RANK__BASIC');
                }
                $this->set(compact('permissions', 'groups'));
            }
        } else {
            $this->redirect('/');
        }
    }

    public function admin_rank()
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->loadModel('Forum.Group');
            if ($this->request->is('ajax')) {
                $this->autoRender = false;
                if (!empty($this->request->data['rank']) && !empty($this->request->data['description']) && !empty($this->request->data['color']) && !empty($this->request->data['position'])) {
                    $rank = $this->request->data['rank'];
                    $description = $this->request->data['description'];
                    $color = $this->request->data['color'];
                    $position = $this->request->data['position'];

                    if (is_numeric($position)) {

                        $color = str_replace('#', '', $color);

                        $id = $this->Group->addGroup($rank, $description, $color, $position);

                        $this->loadModel('Forum.ForumPermission');

                        $permissions = $this->ForumPermission->get(1);
                        foreach ($permissions as $permission) {
                            $name = $permission['ForumPermission']['name'];
                            $this->ForumPermission->addPermission($id, $name, 0);
                        }
                        $this->ForumPermission = $this->Components->load('Forum.ForumPermission');

                        $this->logforum($this->getIdSession(), 'add_group', $this->gUBY($this->getIdSession()) . ' vient d\'ajouter un groupe : ' . $rank, '');

                        $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__ADD__GROUP'))));
                    } else {
                        $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('FORUM__ADD__FAILED'))));
                    }
                } else {
                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('FORUM__ADD__FAILED'))));
                }
            } else {
                $this->layout = 'admin';
                $groups = $this->Group->get();
                $this->set(compact('groups'));
            }
        } else {
            $this->redirect('/');
        }
    }

    public function admin_user()
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->loadModel('User');
            $this->layout = 'admin';

            $users = $this->User->find('all');
            foreach ($users as $key => $user) {
                $users[$key]['User']['rank'] = $users[$key]['User']['color'] = '';
                $users[$key]['User']['rank'] = ($this->ForumPermission->getRank($user['User']['id'])) ? $this->ForumPermission->getRank($user['User']['id']) : '';
                $users[$key]['User']['color'] = ($this->ForumPermission->getRankColor($user['User']['id'])) ? $this->ForumPermission->getRankColor($user['User']['id']) : '';
            }

            $this->set(compact('users'));
        } else {
            $this->redirect('/');
        }
    }

    public function admin_punishment()
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->loadModel('Forum.Punishment');
            if ($this->request->is('ajax')) {
                $this->autoRender = false;
                if (!empty($this->request->data['pseudo']) && !empty($this->request->data['date']) && !empty($this->request->data['reason'])) {
                    $idto = $this->User->getFromUser('id', $this->request->data['pseudo']);
                    if ($idto) {
                        $date = date('Y-m-d H:i:s', strtotime($this->request->data['date']));
                        $reason = $this->request->data['reason'];
                        $this->Punishment->addPunish($this->getIdSession(), $idto, $date, $reason);
                        $this->logforum($this->getIdSession(), 'add_punishment', $this->gUBY($this->getIdSession()) . ' vient d\'ajouter une sanction', $this->request->data['pseudo']);

                        $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__ADD__PUNISHMENT'))));
                    } else {
                        $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('FORUM__ADD__USERUNKNOW'))));
                    }
                } else {
                    $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('FORUM__ADD__FAILED'))));
                }
            } else {
                $this->layout = 'admin';
                $bannis = $this->Punishment->get();
                foreach ($bannis as $key => $banni) {
                    $bannis[$key]['Punishment']['user'] = $this->gUBY($banni['Punishment']['id_user']);
                    $bannis[$key]['Punishment']['userto'] = $this->gUBY($banni['Punishment']['id_to_user']);
                    $bannis[$key]['Punishment']['date'] = $this->dateAndTime($banni['Punishment']['date']);
                }

                $this->set(compact('bannis'));
            }
        } else {
            $this->redirect('/');
        }
    }

    public function admin_report()
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->loadModel('Forum.MsgReport');
            $this->loadModel('Forum.MsgReport');
            $this->loadModel('Forum.Topic');

            $this->layout = 'admin';

            $msgreports = $this->MsgReport->get();

            foreach ($msgreports as $key => $msgreport) {
                $msgreports[$key]['MsgReport']['user'] = $this->gUBY($msgreport['MsgReport']['id_user']);
                $msgreports[$key]['MsgReport']['date'] = $this->dateAndTime($msgreport['MsgReport']['date']);
                $idTopic = $this->Topic->getUniqMessage($msgreport['MsgReport']['id_msg'])['id_topic'];
                $msgreports[$key]['MsgReport']['href'] = $this->buildUri('topic', $this->Topic->getTitleTopic($idTopic), $idTopic);
            }

            $this->set(compact('msgreports'));
        } else {
            $this->redirect('/');
        }
    }

    public function admin_topic()
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->loadModel('Forum.Topic');
            $this->layout = 'admin';
            $topics = $this->Topic->getTopic(0, 'topic');

            foreach ($topics as $key => $topic) {
                $topics[$key]['Topic']['author'] = $this->gUBY($topic['Topic']['id_user']);
                $topics[$key]['Topic']['date'] = $this->dateAndTime($topic['Topic']['date']);
                $topics[$key]['Topic']['href'] = $this->buildUri('topic', $topic['Topic']['name'], $topic['Topic']['id_topic']);
            }

            $this->set(compact('topics'));
        } else {
            $this->redirect('/');
        }
    }

    public function admin_backup()
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $stateExec = false;

            if ($this->isExec()) {
                if (isset($this->request->{'params'}['pass'][0])) {

                    switch ($this->request->{'params'}['pass'][0]) {
                        case 'new':
                            $this->backup('start');
                            break;
                        case 'delete':
                            $this->backup('delete', $this->request->{'params'}['pass'][1]);
                            break;
                        case 'deleteall':
                            $this->backup('deleteall');
                            break;
                        case 'set':
                            $this->backup('set', $this->request->{'params'}['pass'][1]);
                            break;
                    }

                    $this->redirect('/admin/forum/forum/backup');
                }

                $stateExec = true;
            } else {
                // Use Mysqldump vendor
            }
            $this->layout = 'admin';

            $dir = '../Plugin/Forum/Core/database';
            $lists = [];
            if ($dh = opendir($dir)) {
                $i = 0;
                while (($file = readdir($dh)) !== false) {
                    if ($file != '.' && $file != '..' && $file != '.gitkeep') {
                        $lists[$i]['name'] = $file;
                        $file = str_replace('backup_forum_', '', $file);
                        $file = str_replace('__', '-', $file);
                        $file = str_replace('_', ':', $file);
                        $file = str_replace('.zip', '', $file);
                        $lists[$i]['date'] = $this->dateAndTime($file);
                        $i++;
                    }
                }
                closedir($dh);
            }

            $this->set(compact('lists', 'stateExec'));
        } else {
            $this->redirect('/');
        }
    }

    public function admin_tag()
    {
        if ($this->isConnected and $this->User->isAdmin()) {

            $this->loadModel('Forum.Tag');

            $ranks = $this->ForumPermission->getRanks();

            if ($this->request->is('ajax')) {

                $this->autoRender = false;

                $label = $this->request->data['label'];
                $color = $this->request->data['color'];
                $position = $this->request->data['position'];
                $icon = (!empty($this->request->data['icon'])) ? $this->request->data['icon'] : '';

                $color = str_replace('#', '', $color);

                $used = '';

                if (!empty($this->request->data['free'])) {

                    foreach ($ranks as $key => $r) {
                        if (isset($this->request->data[$r['Group']['id']])) {
                            $used[$r['Group']['id']] = $this->request->data[$r['Group']['id']];
                        }
                    }
                    if (!empty($used)) $used = serialize($used);
                }

                $this->Tag->add($label, $icon, $color, $position, $used);

                return $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__ADD__LABEL'))));
            } else {
                $this->layout = 'admin';

                $tags = $this->Tag->get();

                $this->set(compact('tags', 'ranks'));
            }
        } else {
            $this->redirect('/');
        }
    }

    public function admin_cache()
    {
        if ($this->isConnected and $this->User->isAdmin()) {
            $this->layout = 'admin';

            $config = $this->Config->is('cache');
            $router = $this->explodeRouter(5);
            $forums = $this->Forum->getForum();

            if (!is_array($router)) {
                if ($router == "active") {
                    $this->Config->updateConfig('cache', 1);
                } elseif ($router == "empty") {
                    foreach ($forums as $key => $forum) {
                        if ($forum['Forum']['id_parent']) {
                            $key = 'count_topic_category_' . $forum['Forum']['id'];
                            Cache::delete($key);
                        }
                    }
                } elseif ($router == "delete") {
                    $id = $router = $this->explodeRouter(6);
                    $key = 'count_topic_category_' . $id;
                    Cache::delete($key);
                } elseif ($router == "deactivate") {
                    $this->Config->updateConfig('cache', 0);
                }

                $this->redirect(['plugin' => 'forum', 'controller' => 'forum', 'action' => 'admin_cache']);
            }

            foreach ($forums as $key => $forum) {
                if ($forum['Forum']['id_parent'] != 0) {
                    $key = 'count_topic_category_' . $forum['Forum']['id'];
                    $cache['count']['topicCatgory'][$key]['id'] = $forum['Forum']['id'];
                    $cache['count']['topicCatgory'][$key]['key'] = $key;
                    $cache['count']['topicCatgory'][$key]['title'] = $this->Forum->info('forum', $forum['Forum']['id'])[0]['Forum']['forum_name'];
                    $cache['count']['topicCatgory'][$key]['value'] = Cache::read($key)[0];
                }
            }

            $this->set(compact('config', 'cache'));
        } else {
            $this->redirect('/');
        }
    }

    public function admin_stats()
    {
        if ($this->isConnected and $this->User->isAdmin()) {

            $this->loadModel('Forum.Conversation');
            $this->loadModel('Forum.forums');
            $this->loadModel('Forum.Note');
            $this->loadModel('Forum.Topic');
            $this->loadModel('Forum.Vieww');

            $this->layout = 'admin';

            $listDays = [
                7, 6, 5, 4, 3, 2, 1, 0
            ];

            $listMonths = [
                11, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1, 0
            ];

            $stats['general'] = $this->Topic->stats();
            $stats['thumbgreen'] = $this->Note->getNbThumb('total_green');
            $stats['thumbred'] = $this->Note->getNbThumb('total_red');
            $stats['usertoday'] = $this->countActiveToday();
            $stats['category'] = $this->Forum->count('category');
            $stats['forum'] = $this->Forum->count('forum');
            $stats['mp'] = $this->Conversation->count();

            $lastactivities = $this->activities();

            foreach ($lastactivities as $key => $l) {
                $lastactivities[$key]['User']['forum-last_activity'] = $this->dateAndTime($l['User']['forum-last_activity']);
            }

            foreach ($listDays as $key => $l) {
                $date = date('Y-m-d', strtotime('-' . $l . ' day'));

                $stats['x'][$key]['date'] = $this->date($date);
                $stats['view'][$key]['nb'] = $this->Vieww->getView($date);
                $stats['message'][$key]['nb'] = $this->Topic->getNbTopic($date);
                $stats['topic'][$key]['nb'] = $this->Topic->getNbTopic($date, false);
            }

            foreach ($listMonths as $key => $l) {
                $date = date('Y-m-01', strtotime('-' . $l . ' month'));

                $stats['month']['topic'][$key]['date'] = $this->date($date, false);
                $stats['month']['topic'][$key]['view'] = $this->Vieww->getView($date, true, false);
                $stats['month']['message'][$key]['nb'] = $this->Topic->getNbTopic($date, true, true);
                $stats['month']['topic'][$key]['nb'] = $this->Topic->getNbTopic($date, false, true);
            }

            $this->set(compact('stats', 'lastactivities'));
        } else {
            $this->redirect('/');
        }
    }

    public function admin_stats_top()
    {
        if ($this->isConnected and $this->User->isAdmin()) {

            $this->loadModel('Forum.Conversation');
            $this->loadModel('Forum.forums');
            $this->loadModel('Forum.Note');
            $this->loadModel('Forum.Topic');
            $this->loadModel('Forum.Vieww');

            $this->layout = 'admin';

            $ranking['message']['nb'] = $this->Topic->statsTop();
            foreach ($ranking['message']['nb'] as $key => $r) {
                $ranking['message']['nb'][$key]['username'] = $this->gUBY($r['Topic']['id_user']);
            }

            $ranking['topic']['nb'] = $this->Topic->statsTop(true);
            foreach ($ranking['topic']['nb'] as $key => $r) {
                $ranking['topic']['nb'][$key]['username'] = $this->gUBY($r['Topic']['id_user']);
            }

            $ranking['view']['nb'] = $this->Vieww->statsTop();
            foreach ($ranking['view']['nb'] as $key => $r) {
                $ranking['view']['nb'][$key]['title'] = $this->Topic->getTitleTopic($r['Vieww']['id_topic']);
            }

            $ranking['mp']['nb'] = $this->Conversation->statsTop();
            foreach ($ranking['mp']['nb'] as $key => $r) {
                $ranking['mp']['nb'][$key]['username'] = $this->gUBY($r['Conversation']['author_id']);
            }

            $this->set(compact('ranking'));
        } else {
            $this->redirect('/');
        }
    }

    public function admin_personalize()
    {
        if ($this->isConnected and $this->User->isAdmin()) {

            $this->loadModel('Forum.Internal');

            $this->layout = 'admin';

            $router = $this->explodeRouter(5);

            switch ($router) {
                case 'home':
                    if ($this->request->is('ajax')) {
                        $this->autoRender = false;

                        if (!empty($this->request->data['background'])) {
                            switch ($this->request->data['background']) {
                                case '[background][none]':
                                    $array = [
                                        'type' => 'default',
                                        'value' => 'none'
                                    ];
                                    break;
                                case '[background][color]':
                                    $array = [
                                        'type' => 'color',
                                        'value' => $this->request->data['background-color']
                                    ];
                                    break;
                                case '[background][image]':
                                    $array = [
                                        'type' => 'image',
                                        'value' => $this->request->data['background-image']
                                    ];
                                    break;
                            }

                            $array = serialize($array);
                            $this->Internal->update('background', $array);
                        } elseif (!empty($this->request->data['description'])) {

                            if ($this->request->data['description'] == "description-base") {
                                $this->Internal->update('description', "description");
                            } elseif ($this->request->data['description'] == "description-tooltip") {
                                $this->Internal->update('description', "tooltip");
                            }
                        } elseif (!empty($this->request->data['type'])) {

                            switch ($this->request->data['type']) {
                                case 'icon':
                                    $iHome = $this->request->data['icon-home'];
                                    $iFlag = $this->request->data['icon-flag'];
                                    $iEnvelope = $this->request->data['icon-envelope'];
                                    $iUser = $this->request->data['icon-user'];
                                    $iOut = $this->request->data['icon-out'];
                                    $iIn = $this->request->data['icon-in'];

                                    $array = [
                                        'home' => $iHome,
                                        'flag' => $iFlag,
                                        'envelope' => $iEnvelope,
                                        'user' => $iUser,
                                        'out' => $iOut,
                                        'in' => $iIn
                                    ];

                                    $array = serialize($array);
                                    $this->Internal->update('icons', $array);

                                    break;
                                case 'arrow':
                                    $color = $this->request->data['color'];
                                    $this->Internal->update('chevron_color', $color);

                                    break;

                                case 'forum':
                                    $color = $this->request->data['color'];
                                    $this->Internal->update('forum_color', $color);

                                    break;

                                case 'home':
                                    $colorStats = $this->request->data['stats'];
                                    $colorUsers = $this->request->data['users'];

                                    $array = [
                                        'stats' => $colorStats,
                                        'users' => $colorUsers
                                    ];

                                    $array = serialize($array);
                                    $this->Internal->update('index_title', $array);

                                    break;
                            }
                        } else {
                            return $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('FORUM__ADD__FAILED'))));
                        }

                        return $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__ADD__SUCCESS'))));
                    } else {
                        $configTheme['background'] = unserialize($this->Internal->get('background'));
                        $configTheme['index_title'] = unserialize($this->Internal->get('index_title'));
                        $configTheme['description'] = $this->Internal->get('description');
                        $configTheme['icons'] = unserialize($this->Internal->get('icons'));
                        $configTheme['forum_color'] = $this->Internal->get('forum_color');
                        $configTheme['chevron_color'] = $this->Internal->get('chevron_color');
                    }
                    break;
                case 'forum':
                    if ($this->request->is('ajax')) {
                        $this->autoRender = false;

                        if (!empty($this->request->data['type'])) {
                            switch ($this->request->data['type']) {
                                case 'last':
                                    $title = $this->request->data['title'];
                                    $date  = $this->request->data['date'];

                                    $this->Internal->update('lasttopic_titlecolor', $title);
                                    $this->Internal->update('lasttopic_datecolor', $date);

                                    break;
                                case 'topic':
                                    $color = $this->request->data['color'];

                                    $this->Internal->update('topic_color', $color);

                                    break;
                            }
                        } else {
                            return $this->response->body(json_encode(array('statut' => false, 'msg' => $this->Lang->get('FORUM__ADD__FAILED'))));
                        }

                        return $this->response->body(json_encode(array('statut' => true, 'msg' => $this->Lang->get('FORUM__ADD__SUCCESS'))));
                    } else {
                        $configTheme['topic_color'] = $this->Internal->get('topic_color');
                        $configTheme['last_colortitle'] = $this->Internal->get('lasttopic_titlecolor');
                        $configTheme['last_colordate'] = $this->Internal->get('lasttopic_datecolor');
                    }
                    break;
            }

            $this->set(compact('router', 'configTheme'));
        } else {
            $this->redirect('/');
        }
    }

    public function admin_help()
    {
        if ($this->isConnected and $this->User->isAdmin()) {

            $this->layout = 'admin';
            $version = $this->version;

            $this->set(compact('version'));
        } else {
            $this->redirect('/');
        }
    }

    /*
     * Function calc, back end ...
     */

    private function install()
    {
        /**
         * Add Model WHERE for the default installation
         **/

        $models = ['Config', 'Forum', 'Group', 'Groups_user', 'Historie', 'Insult', 'Note', 'ForumPermission', 'Topic'];

        foreach ($models as $model) {
            $this->loadModel('Forum.' . $model);
            $table = lcfirst($model);
            if ($model != 'ForumPermission') {
                $this->$model->query('TRUNCATE TABLE forum__' . $table . 's');
            }
        }
        // And Exeption Model & Table
        $this->loadModel('Forum.MsgReport');
        $this->loadModel('Forum.ForumPermission');
        $this->$model->query('TRUNCATE TABLE forum__msg_reports');
        $this->$model->query('TRUNCATE TABLE forum__forum_permissions');

        $install = $this->installArray();

        foreach ($models as $model) {
            if ($model != 'ForumPermission') {
                $table = lcfirst($model);
                $this->$model->saveAll($install[$table]);
            } else {
                $this->$model->saveAll($install['forum_permission']);
            }
        }
        $this->ForumPermission = $this->Components->load('Forum.ForumPermission');
    }

    private function word($string)
    {
        $this->loadModel('Forum.Insult');

        $words = $this->Insult->get();

        foreach ($words as $word) {
            $string = preg_replace('/\b' . $word['Insult']['word'] . '\b/i', $word['Insult']['replace'], $string);
        }

        return $string;
    }

    private function dropHistory()
    {
        $this->loadModel('Forum.Historie');
        $this->Historie->drop($this->Util->getIP(), $this->getIdSession());
    }

    private function explodeRouter($id)
    {
        $url = $this->request->here;
        $explode = explode("/", $url);

        if (isset($explode[$id])) {
            return $explode[$id];
        }

        return $explode;
    }

    private function remoteAction($type, $value = false)
    {
        $options = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];

        switch ($type) {
            case 'ADMINMSG':
                $json = $this->core();
                $jsonLastVersion = file_get_contents('https://www.phpierre.fr/mineweb/forum/lastversion/' . $json, false, stream_context_create($options));
                $lastVersion = json_decode($jsonLastVersion, true)['version'];

                if ($this->version != $lastVersion) {
                    $jsonMsgadmin = file_get_contents('https://www.phpierre.fr/mineweb/forum/msgadmin/e/', false, stream_context_create($options));
                    $msg = json_decode($jsonMsgadmin, true)['msg'];
                    $site = $_SERVER['SERVER_NAME'];
                    $msg = str_replace('[LIEN]', $site, $msg);
                    return $msg;
                } else {
                    return '';
                }

                break;
            case 'NEXTUPDATE':
                $json = file_get_contents('https://www.phpierre.fr/mineweb/forum/nextupdate/e/', false, stream_context_create($options));
                $msg = json_decode($json, true);
                return $msg;

                break;
            case 'MSGALL':
                $json = $this->newCore();
                $json = file_get_contents('https://www.phpierre.fr/mineweb/forum/last/' . $json, false, stream_context_create($options));
                $msg = json_decode($json, true);

                return $msg;
                break;
        }
    }

    private function reset()
    {
        $models = ['Config', 'Conversation', 'ConversationRecipient', 'Forum', 'Group', 'Groups_user', 'Historie', 'Insult', 'MsgReport', 'Note', 'Profile', 'ForumPermission', 'Punishment', 'Topic', 'Vieww'];
        foreach ($models as $model) {
            $this->loadModel('Forum.' . $model);
            $table = lcfirst($model);
            if ($model != 'ForumPermission' or $model != 'MsgReport' or $model != 'ConversationRecipient') {
                $this->$model->query('TRUNCATE TABLE forum__' . $table . 's');
            }
        }

        $this->$model->query('TRUNCATE TABLE forum__msg_reports');
        $this->$model->query('TRUNCATE TABLE forum__forum_permissions');
        $this->$model->query('TRUNCATE TABLE forum__conversation_recipients');

        $install = $this->installArray();
        $this->ForumPermission->saveAll($install['forum_permission']);
        $this->Config->saveAll($install['config']);

        $this->ForumPermission = $this->Components->load('Forum.ForumPermission');
    }

    public function debug($hash)
    {

        /**
         * 1 = BTB in another language in full
         * 2 = DNS first VM Vagrant
         * 3 = April Month in another language in full
         * 4 = Louise's fullname
         **/

        /**
         * 1 = View forum version
         * 2 = View Perms
         * 3 = Clear logs
         * 4 = View logs
         **/

        switch (hash('sha384', $hash)) {
            case '016259d8713c2dc12cd48ff0af6f3cfef13525c7526ebf2de2efbcba7675962ff28c139490a9378ff3e1d2d4222c613f':
                $this->autoRender = null;

                header('Content-Type: application/json');
                echo '{"forum_version":"' . $this->version . '"}';
                break;
            case '0c3eb61273f5c320fb45a479e8b8b05fc3b841f435f1c69a497d320ac88e105fda5bbd0bf0089d65a94279eb481ed6b5':
                $this->autoRender = null;

                header('Content-Type: application/json');
                var_dump($this->perm_l());
                break;
            case '1dd69db38977428bd8068c728d14fc96a70690e23c34ac09ec26c397850273a7c539fdadb714ef60bf81adcee246f56b':
                $this->autoRender = null;
                $dir = '../tmp/logs/';
                @unlink($dir . 'error.log');
                @unlink($dir . 'debug.log');

                echo 'Done';
                break;
            case '821ab95e69ce6d9168aa4ee9c84b8d5dc3b49b2ab626b9c328d490c5d780666138e388264ca1b158060aea9c8df57b37':
                $this->autoRender = null;
                $dir = '../tmp/logs/';

                echo 'error.log <br />';
                $errorFile = @file($dir . 'error.log');
                if (!empty($errorFile)) {
                    foreach ($errorFile as $f) {
                        echo htmlspecialchars($f) . "<br />\n";
                    }
                }

                echo '<br /> debug.log <br />';
                $debugFile = @file($dir . 'debug.log');
                if (!empty($debugFile)) {
                    foreach ($debugFile as $f) {
                        echo htmlspecialchars($f) . "<br />\n";
                    }
                }

                break;
            default:
                throw new ForbiddenException();
                break;
        }
    }

    private function installArray()
    {
        $date = date("Y-m-d H:i:s");
        $array = [
            'config' => [
                ['config_name' => 'useronline', 'config_value' => true, 'lang' => 'Utilisateur en ligne'],
                ['config_name' => 'statistics', 'config_value' => true, 'lang' => 'Statistiques'],
                ['config_name' => 'privatemsg', 'config_value' => true, 'lang' => 'Message privés'],
                ['config_name' => 'reportmsg', 'config_value' => true, 'lang' => 'Signaler les messages'],
                ['config_name' => 'notemsg', 'config_value' => true, 'lang' => 'Noter les messages'],
                ['config_name' => 'userpage', 'config_value' => true, 'lang' => 'Profil utilisateur du forum'],
                ['config_name' => 'forum', 'config_value' => true, 'lang' => 'Forum'],
                ['config_name' => 'socialnetwork', 'config_value' => true, 'lang' => 'Réseaux sociaux'],
                ['config_name' => 'cache', 'config_value' => false, 'lang' => 'Cache'],
            ],
            'forum' => [
                ['id_parent' => 0, 'id_user' => 1, 'position' => 1, 'forum_name' => 'Mineweb', 'forum_description' => 'Ceci est une description', 'forum_image' => 'folder-open'],
                ['id_parent' => 0, 'id_user' => 1, 'position' => 2, 'forum_name' => 'Support', 'forum_description' => 'Ceci est une description', 'forum_image' => 'folder-open'],
                ['id_parent' => 0, 'id_user' => 1, 'position' => 2, 'forum_name' => 'Documentation', 'forum_description' => 'Ceci est une description', 'forum_image' => 'folder-open'],

                ['id_parent' => 1, 'id_user' => 1, 'position' => 1, 'forum_name' => 'Règlement', 'forum_description' => 'Ceci est une description', 'forum_image' => 'folder-open'],
                ['id_parent' => 1, 'id_user' => 1, 'position' => 1, 'forum_name' => 'Catégorie random', 'forum_description' => 'Ceci est une description', 'forum_image' => 'folder-open'],

                ['id_parent' => 4, 'id_user' => 1, 'position' => 1, 'forum_name' => 'Catégorie random', 'forum_description' => 'Ceci est une description', 'forum_image' => 'folder-open'],
            ],
            'group' => [
                ['group_name' => 'Administrateur', 'group_description' => 'Ceci est le groupe des administrateurs du serveur Minecraft', 'color' => 'e74c3c', 'position' => 1],
                ['group_name' => 'Modérateur', 'group_description' => 'Ceci est le groupe des modérateurs du serveur Minecraft', 'color' => 'e67e22', 'position' => 2],
                ['group_name' => 'Développeur', 'group_description' => 'Ceci est le groupe des développeurs du serveur Minecraft', 'color' => '2ecc71', 'position' => 3],
            ],
            'groups_user' => [
                ['id_user' => 1, 'id_group' => 1, 'domin' => true],
                ['id_user' => 1, 'id_group' => 2],
                ['id_user' => 1, 'id_group' => 3],
            ],
            'historie' => [
                ['date' => $date, 'id_user' => 1, 'ip' => $this->Util->getIP(), 'category' => 'general', 'action' => 'Configuration de base du site', 'content' => ''],
            ],
            'insult' => [
                ['word' => 'merde', 'replace' => 'm****'],
                ['word' => 'putin', 'replace' => 'p****'],
                ['word' => 'fdp', 'replace' => ''],
            ],
            'note' => [
                ['id_user' => 1, 'id_to_user'  => 1, 'id_message' => 1, 'type' => 1],
            ],
            'forum_permission' => [

                ['group_id' => 1, 'name' => 'FORUM_MP_SEND', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_MP_SEND', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_MP_SEND', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_MP_SEND', 'value' => 1],

                ['group_id' => 1, 'name' => 'FORUM_MP_REPLY', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_MP_REPLY', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_MP_REPLY', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_MP_REPLY', 'value' => 1],

                ['group_id' => 1, 'name' => 'FORUM_TOPIC_SEND', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_TOPIC_SEND', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_TOPIC_SEND', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_TOPIC_SEND', 'value' => 1],

                ['group_id' => 1, 'name' => 'FORUM_TOPIC_REPLY', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_TOPIC_REPLY', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_TOPIC_REPLY', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_TOPIC_REPLY', 'value' => 1],

                ['group_id' => 1, 'name' => 'FORUM_TOPIC_STICK', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_TOPIC_STICK', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_TOPIC_STICK', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_TOPIC_STICK', 'value' => 0],

                ['group_id' => 1, 'name' => 'FORUM_TOPIC_LOCK', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_TOPIC_LOCK', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_TOPIC_LOCK', 'value' => 0],
                ['group_id' => 99, 'name' => 'FORUM_TOPIC_LOCK', 'value' => 0],

                ['group_id' => 1, 'name' => 'FORUM_MSGMY_EDIT', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_MSGMY_EDIT', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_MSGMY_EDIT', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_MSGMY_EDIT', 'value' => 1],

                ['group_id' => 1, 'name' => 'FORUM_MSG_EDIT', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_MSG_EDIT', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_MSG_EDIT', 'value' => 0],
                ['group_id' => 99, 'name' => 'FORUM_MSG_EDIT', 'value' => 0],

                ['group_id' => 1, 'name' => 'FORUM_MSGMY_DELETE', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_MSGMY_DELETE', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_MSGMY_DELETE', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_MSGMY_DELETE', 'value' => 1],

                ['group_id' => 1, 'name' => 'FORUM_MSG_DELETE', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_MSG_DELETE', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_MSG_DELETE', 'value' => 0],
                ['group_id' => 99, 'name' => 'FORUM_MSG_DELETE', 'value' => 0],

                ['group_id' => 1, 'name' => 'FORUM_MSG_REPORT', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_MSG_REPORT', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_MSG_REPORT', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_MSG_REPORT', 'value' => 1],

                ['group_id' => 1, 'name' => 'FORUM_TOPICMY_DELETE', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_TOPICMY_DELETE', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_TOPICMY_DELETE', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_TOPICMY_DELETE', 'value' => 0],

                ['group_id' => 1, 'name' => 'FORUM_TOPIC_DELETE', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_TOPIC_DELETE', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_TOPIC_DELETE', 'value' => 0],
                ['group_id' => 99, 'name' => 'FORUM_TOPIC_DELETE', 'value' => 0],

                ['group_id' => 1, 'name' => 'FORUM_VIEW_REPORT', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_VIEW_REPORT', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_VIEW_REPORT', 'value' => 0],
                ['group_id' => 99, 'name' => 'FORUM_VIEW_REPORT', 'value' => 0],

                ['group_id' => 1, 'name' => 'FORUM_MOOVE_TOPIC', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_MOOVE_TOPIC', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_MOOVE_TOPIC', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_MOOVE_TOPIC', 'value' => 0],

                //1.3.0 ++
                ['group_id' => 1, 'name' => 'FORUM_TOPICMY_LOCK', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_TOPICMY_LOCK', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_TOPICMY_LOCK', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_TOPICMY_LOCK', 'value' => 1],

                ['group_id' => 1, 'name' => 'FORUM_TAG_TOPIC', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_TAG_TOPIC', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_TAG_TOPIC', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_TAG_TOPIC', 'value' => 0],

                ['group_id' => 1, 'name' => 'FORUM_RENAMEMY_TOPIC', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_RENAMEMY_TOPIC', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_RENAMEMY_TOPIC', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_RENAMEMY_TOPIC', 'value' => 0],

                ['group_id' => 1, 'name' => 'FORUM_RENAME_TOPIC', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_RENAME_TOPIC', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_RENAME_TOPIC', 'value' => 0],
                ['group_id' => 99, 'name' => 'FORUM_RENAME_TOPIC', 'value' => 0],

                ['group_id' => 1, 'name' => 'FORUM_TOPIC_VISIBILY', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_TOPIC_VISIBILY', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_TOPIC_VISIBILY', 'value' => 0],
                ['group_id' => 99, 'name' => 'FORUM_TOPIC_VISIBILY', 'value' => 0],

                ['group_id' => 1, 'name' => 'FORUM_TAG_PUBLIC', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_TAG_PUBLIC', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_TAG_PUBLIC', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_TAG_PUBLIC', 'value' => 1],

                ['group_id' => 1, 'name' => 'FORUM_CREATE_POLL', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_CREATE_POLL', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_CREATE_POLL', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_CREATE_POLL', 'value' => 1],

                ['group_id' => 1, 'name' => 'FORUM_USE_PUNISHMENT', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_USE_PUNISHMENT', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_USE_PUNISHMENT', 'value' => 0],
                ['group_id' => 99, 'name' => 'FORUM_USE_PUNISHMENT', 'value' => 0],

                ['group_id' => 1, 'name' => 'FORUM_TAG_USER', 'value' => 1],
                ['group_id' => 2, 'name' => 'FORUM_TAG_USER', 'value' => 1],
                ['group_id' => 3, 'name' => 'FORUM_TAG_USER', 'value' => 1],
                ['group_id' => 99, 'name' => 'FORUM_TAG_USER', 'value' => 0],
            ],
            'profile' => [
                ['id_user' => 1, 'description' => 'Ceci est un description généré lors de l\'installation du plugin ']
            ],
            'topic' => [
                ['id_parent' => 4, 'id_user' => 1, 'id_topic' => 1, 'name' => 'Votre premier Topic !', 'first' => 1, 'content' => 'Ceci est votre premier message. C\'est pour vous montrez un petit parçu du plugin.', 'date' => $date, 'last_edit' => $date],
            ],
        ];

        return $array;
    }

    public function forumAction($type, $act, $params = false)
    {
        $this->layout = false;
        $this->autoRender = false;

        $perm_l = $this->perm_l();

        if ($this->request->is('ajax')) {
            if ($type == 'topic') {
                $this->loadModel('Forum.Topic');

                switch ($act) {
                    case 'message':
                        return $this->Topic->getUniqMessage($params);
                        break;
                }
            }
        } elseif ($this->request->is('post')) {
            if ($type == 'topic') {
                $this->loadModel('Forum.Topic');
                $this->loadModel('Forum.Tag');

                switch ($act) {
                    case 'moove':
                        $to = $this->request->data['forum'];
                        if ($perm_l['FORUM_MOOVE_TOPIC']) $this->Topic->moove($params, $to);
                        break;
                    case 'rename':
                        $newName = $this->urlRew(trim($this->request->data['name']));
                        if ($perm_l['FORUM_MSG_EDIT']) $this->Topic->rename($params, $newName);
                        break;
                    case 'view':
                        $ranks = $this->ForumPermission->getRanks();
                        foreach ($ranks as $key => $r) {
                            if (isset($this->request->data[$r['Group']['id']])) {
                                $visible[$r['Group']['id']] = $this->request->data[$r['Group']['id']];
                            }
                        }
                        if (!empty($visible))  $visible = serialize($visible);
                        else $visible = '';
                        if ($perm_l['FORUM_MSG_EDIT']) $this->Topic->updateVisible($params, $visible);
                        break;
                    case 'tag':
                        $tags = $this->Tag->get();
                        $newTag = '';
                        foreach ($tags as $key => $tag) {
                            if (isset($this->request->data['tag-' . $tag['Tag']['id']])) {
                                $newTag .= $this->request->data['tag-' . $tag['Tag']['id']] . ',';;
                            }
                        }
                        if ($perm_l['FORUM_MSG_EDIT']) $this->Topic->updateTag($params, $newTag);
                        break;
                }
            }
        } else {
            if ($type == 'topic') {
                $this->loadModel('Forum.Topic');

                switch ($act) {
                    case 'delete':
                        if ($perm_l['FORUM_TOPIC_DELETE']) $this->Topic->deleteMessages($params);
                        break;
                    case 'lock':
                        if ($perm_l['FORUM_TOPIC_LOCK']) $this->Topic->change('lock', $params);
                        break;
                    case 'unlock':
                        if ($perm_l['FORUM_TOPIC_LOCK']) $this->Topic->change('unlock', $params);
                        break;
                    case 'stick':
                        if ($perm_l['FORUM_TOPIC_STICK']) $this->Topic->change('stick', $params);
                        break;
                    case 'unstick':
                        if ($perm_l['FORUM_TOPIC_STICK']) $this->Topic->change('unstick', $params);
                        break;
                }
            }
        }

        return $this->redirect($this->referer());
    }

    private function countChildrenTopic($type, $id)
    {
        $this->loadModel('Forum.forums');
        $this->loadModel('Forum.Topic');

        $nb = 0;

        switch ($type) {
            case 'forum':
                $nb += $this->countChildrenTopic('category', $id);

                $categorys = $this->Forum->getForum('categorie', $id);

                if (!empty($categorys)) {
                    foreach ($categorys as $key => $category) {
                        $nb += $this->countChildrenTopic('category', $category['Forum']['id']);
                    }
                }
                break;
            case 'category':
                $nb = $this->Topic->info('nb_topic', $id);
                break;
            default:
                return null;
        }

        return $nb;
    }

    private function countChildrenMessage($type, $id)
    {
        $this->loadModel('Forum.forums');
        $this->loadModel('Forum.Topic');

        $nb[0] = 0;

        switch ($type) {
                /*case 'forum':
                $nb += $this->countChildrenTopic('category', $id);

                $categorys = $this->Forum->getForum('categorie', $id);

                if(!empty($categorys)){
                    foreach ($categorys as $key => $category){
                        $nb += $this->countChildrenTopic('category', $category['Forum']['id']);
                    }
                }
                break;*/
            case 'category':

                $categorys = $this->Forum->getForum('categorie', $id);

                if (!empty($categorys)) {
                    foreach ($categorys as $category) {
                        $nb[0] += $this->countChildrenMessage('category', $category['Forum']['id']);
                    }
                }

                $topics = $this->Topic->getTopic($id);

                $key = 'count_topic_' . $type . '_' . $id;

                if ($this->Config->is('cache')) {
                    $nb = Cache::remember($key, function () use ($topics, $nb) {
                        if (!empty($topics)) {
                            foreach ($topics as $topic) {
                                $nb[0] += $this->Topic->getNbMessage('topic', $topic['Topic']['id_topic']);
                            }
                        }
                        return $nb;
                    });
                } else {
                    if (!empty($topics)) {
                        foreach ($topics as $topic) {
                            $nb[0] += $this->Topic->getNbMessage('topic', $topic['Topic']['id_topic']);
                        }
                    }
                }

                break;
            case 'topic':
                return $this->Topic->info('nb_msg', $id);
                break;
            default:
                return null;
        }
        return $nb[0];
    }

    private function breadcrumb($type, $id)
    {

        $this->loadModel('Forum.forums');
        $this->loadModel('Forum.Topic');

        if ($type == 'category') {

            $i = 0;
            $state = true;
            $list = [];

            while ($state) {
                if ($i == 0) {
                    $infos = $this->Forum->getForum('id', $id);
                } else {
                    $u = $i - 1;
                    $infos = $this->Forum->getForum('id', $list[$u]['id_parent']);
                }
                if ($infos['Forum']['id_parent'] == 0) {
                    $state = false;
                } else {
                    $list[$i]['id'] = $infos['Forum']['id'];
                    $list[$i]['id_parent'] = $infos['Forum']['id_parent'];
                    $list[$i]['name'] = $infos['Forum']['forum_name'];
                    $list[$i]['url'] = $this->replaceSpace($list[$i]['name']) . '.' . $list[$i]['id'];
                    $i++;
                }
            }

            $list = array_reverse($list);

            return $list;
        } else {
            $idParent = $this->Topic->info('id_parent', $id);

            $list = $this->breadcrumb('category', $idParent);

            return $list;
        }
    }

    private function explore($id, $test = false)
    {
        if (!$test) {
            $this->valueExplorer = null;
            $this->valueTopic = null;
        }

        $this->loadModel('Forum.Topic');

        if (!$test) {
            $datas = $this->inCategory($id);
        } else {
            $datas = $id;
        }

        $topics[] = $this->Topic->getTopic($id);

        if (empty($datas) && empty($topics)) {
            return;
        }

        if (is_array($datas)) {
            foreach ($datas as $key => $d) {
                if (is_array($d)) {
                    if ($test) {
                        return $this->explore($d, true);
                    } else {
                        $this->explore($d, true);
                    }
                } else {
                    if ($d) {
                        $topics[] = $this->Topic->getTopic($d);
                    }
                }
            }
        }

        foreach ($topics as $key => $t) {
            if (!empty($t)) {
                if (isset($t[1]['Topic'])) {
                    foreach ($t as $index => $value) {
                        $this->indexExplorer = $this->indexExplorer + 1;
                        $this->valueExplorer[$this->indexExplorer] = $value;
                    }
                } else {
                    $this->indexExplorer = $this->indexExplorer + 1;
                    $this->valueExplorer[$this->indexExplorer] = $t[0];
                }
            }
        }


        if (is_array($this->valueExplorer)) {
            foreach ($this->valueExplorer as $key => $n) {
                $this->valueTopic[$key] = $n['Topic']['id_topic'];
            }
        }

        $allTopics = $this->Topic->find('all', ['conditions' => ['id_topic' => $this->valueTopic], 'order' => ['date' => 'DESC']]);

        if (!empty($allTopics)) {
            $lastMessage = $allTopics[0]['Topic'];
            $lastTopic = $allTopics[0]['Topic']['id_topic'];
            $title = $this->Topic->getTitleTopic($lastTopic);

            $return = [
                'title' => $title,
                'id' => $lastTopic,
                'last_author_id' => $lastMessage['id_user'],
                'last_author' => $this->gUBY($lastMessage['id_user']),
                'last_date' => $lastMessage['date']
            ];

            return $return;
        }
    }

    private function inCategory($id)
    {
        $categorys = $this->Forum->getForum('categorie', $id);

        $datas = [];

        if (!empty($categorys)) {
            foreach ($categorys as $key => $category) {
                $datas[] = $category['Forum']['id'];
                $datas[] = $this->inCategory($category['Forum']['id']);
            }
        } else {
            return false;
        }

        return $datas;
    }

    private function hourCheck()
    {
        $this->loadModel('Forum.Internal');
        $date = $this->Internal->get('start');

        $dif = 0;
        $alertforum['update'] = "";
        $version = $this->Internal->get('last_version');

        if (!is_null($date) && !empty($date)) {
            $now = new DateTime();
            $hour = DateTime::createFromFormat('Y-m-d H:i:s', $date);
            $dif = $now->format('U') - $hour->format('U');
        }

        if (is_null($date) || empty($date) || $dif > 43200) {
            $version = $this->remoteAction('MSGALL')['version'];
            $this->Internal->update('start', date('Y-m-d H:i:s'));
            $this->Internal->update('last_version', $version);
        }

        if ($version != $this->version) {
            $alertforum['update'] .= "<div class=\"row mt20\">";
            $alertforum['update'] .= "<div class=\"col-md-12\">";
            $alertforum['update'] .= "<div class=\"alert alert-info\" role=\"alert\">";
            $alertforum['update'] .= "<strong>" . $this->Lang->get('FORUM__INFORMATION') . "</strong> ";
            $alertforum['update'] .= "Une mise à jour du plugin ";
            $alertforum['update'] .= "<a target=\"_blanks\" href=\"" . Router::url('/', true) . "admin/plugin\" class=\"alert-link\">" . $this->Lang->get('FORUM__ISAVAILAVLE') . "</a>";
            $alertforum['update'] .= "</div>";
            $alertforum['update'] .= "</div>";
            $alertforum['update'] .= "</div>";
        }

        return $alertforum['update'];
    }

    private function ㅋㅋㅋㅋㅋ()
    {

        /*
         * debug
         */

        /*$this->debug['IndexStart'] = microtime();
            $this->debug['IndexAdMid'] = microtime();
            $this->debug['IndexMid'] = microtime();
            $this->debug['IndexEnd'] = microtime();
            $this->log($this->debug);*/

        /*
         * end debug
         */

        $array = ['Kraken', 'Kuro'];
        /*
         * Pourquoi ces thèmes sont bloqués ?
         * Car ces thèmes sont soit abandonné, soit non mis à jour et donc manquent les dernières fonctions ou contiennent des failles de sécurités.
         * Rien ne vous empêche de les débloquer, à vos risques et péril.
         */

        if (in_array($this->theme, $array)) {
            echo "<script>alert('Ce thème n\'est pas compatible avec le forum')</script>";
            throw new UnauthorizedException();
        }
    }
}
