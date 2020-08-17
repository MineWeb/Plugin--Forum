<?php

App::uses('CakeTime', 'Utility');

class ForumAppController extends AppController
{

    public $components = [
        'Forum.ForumRender',
        'Forum.ForumBackup',
        'Forum.ForumPermission',
        'Forum.ForumSecurity'

        /*'DebugKit.Toolbar' => [
            'panels' => ['history' => false]
        ]*/
    ];

    public $atualTheme;

    protected $version = '1.4.7';


    protected function date($date, $day = true)
    {
        if ($day) {
            return $this->format(CakeTime::format($date, '%d %B %Y'));
        } else {
            return $this->format(CakeTime::format($date, '%B %Y'));
        }
    }

    protected function time($time)
    {
        return $this->format(CakeTime::format($time, '%H:%M'));
    }

    protected function dateAndTime($date)
    {
        return $this->format(CakeTime::format($date, '%d %B %Y %H:%M'));
    }

    protected function notification($class, $to, $from, $type, $params = null)
    {
        $this->loadModel('Notification');

        switch ($class) {
            case 'respond_topic':
                $content = $this->gUBY($from).$this->Lang->get('FORUM__NOTIFICATION__MESSAGE_RESPONSE');
                $content = str_replace('[TITLE]', $params, $content);
                break;
            case 'send_mp':
                $content = $this->gUBY($from).$this->Lang->get('FORUM__NOTIFICATION__MP__SEND');
                break;
            case 'respond_mp':
                $content = $this->gUBY($from).$this->Lang->get('FORUM__NOTIFICATION__MP__RESPONSE');
                break;
            case 'delete_topic':
                $content = $this->Lang->get('FORUM__NOTIFICATION__TOPIC__DELETE').$this->gUBY($from);
                $content = str_replace('[TITLE]', $params, $content);
                break;
            case 'delete_message':
                $content = $this->Lang->get('FORUM__NOTIFICATION__MESSAGE__DELETE').$this->gUBY($from);
                $params = substr($params, 0, 40);
                $content = str_replace('[MESSAGE]', $params, $content);
                break;
            case  'stick_topic':
                $content = $this->Lang->get('FORUM__NOTIFICATION__TOPIC__STICK').$this->gUBY($from);
                $content = str_replace('[TITLE]', $params, $content);
                break;
            case  'lock_topic':
                $content = $this->Lang->get('FORUM__NOTIFICATION__TOPIC__LOCK').$this->gUBY($from);
                $content = str_replace('[TITLE]', $params, $content);
                break;
            case  'unlock_topic':
                $content = $this->Lang->get('FORUM__NOTIFICATION__TOPIC__UNLOCK').$this->gUBY($from);
                $content = str_replace('[TITLE]', $params, $content);
                break;
        }

        return $this->Notification->setToUser($content, $to, $from, $type);
    }

    protected function トロール(){
        return 'こんにちは';
    }

    protected function format($format)
    {
        $enDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        $enMonths = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'Décember'];
        $frDays = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
        $frMonths = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
        return str_replace($enMonths, $frMonths, str_replace($enDays, $frDays, $format));
    }

    protected function dateInscription($id){
        return $this->User->find('first', ['conditions' => ['id' => $id]])['User']['created'];
    }

    protected function lastSeen($id)
    {
        return $this->User->find('first', ['fields' => 'forum-last_activity', 'conditions' => ['id' => $id]])['User']['forum-last_activity'];
    }

    protected function activities(){
        return $this->User->find('all', ['order' => ['forum-last_activity' => 'DESC']]);
    }

    protected function countActiveToday()
    {
        $date = date("Y-m-d");
        $max = date("Y-m-d H:i:s");
        return $this->User->find('count', ['conditions' => ['forum-last_activity >=' => $date, 'forum-last_activity <=' => $max]]);
    }

    protected function getIdSession()
    {
        return isset($_SESSION['user']) ? $_SESSION['user'] : false;
    }

    protected function logforum($idUser, $category, $action, $content)
    {
        $this->loadModel('Forum.Historie');
        $this->Historie->add($this->Util->getIP(), $idUser, $category, $action, $content);
    }

    protected function gUBY($id)
    {
        //Bricollage en attendant la prochaine maj cc @Eywek avec le $this->User->getUsernameById($id);
        $search_user = $this->User->find('first', array('conditions' => array('id' => $id)));
        return (!empty($search_user)) ? $search_user['User']['pseudo'] : '';
    }

    protected function forumRender($type, $value)
    {
        return $this->ForumRender->index($type, $value);
    }

    public function theme()
    {
        //Hack for Justice Thème
        $theme = ($this->theme != 'Justice') ? 'container' : '';
        $this->atualTheme = $theme;
        return $theme;
    }

    protected function urlRew($url)
    {
        $array = [
            '/' => '',
            '{' => '',
            '}' => '',
            '-' => '.',
            ':' => '',
            '?' => '',
            '#' => '',
            '@' => '',
            '~' => '',
            '`' => '',
            '\\' => '',
            ';' => '',
            'http://' => '',
            'www' => '',
            '<script' => '',
            '<?php' => '',
            '<?=' => ''
        ];
        $url = strtr($url, $array);
        return $url;
    }

    protected function core()
    {
        $array = [
            'version' => $this->version,
            'host' => env('SERVER_NAME')
        ];

        $json = json_encode($array);
        return $json;
    }

    protected function newCore()
    {
        $this->loadModel('Forum.Profile');
        $this->loadModel('Forum.Topic');

        $stats = $this->Topic->stats();

        $array = [
            'version' => $this->version,
            'host' => env('SERVER_NAME'),
            'nbtopic' => $stats['total_topic'],
            'nbmsg' => $stats['total_msg'],
            'nbuser' => $this->Profile->count()
        ];

        $json = json_encode($array);
        return $json;
    }

    protected function replaceSpace($string)
    {
        return str_replace(" ", "-", $string);
    }

    protected function replaceHyppen($string)
    {
        return str_replace("-", " ", $string);
    }

    protected function buildUri($type, $name, $id, $anchor =  '')
    {
        $name = h($name);

        if (!empty($anchor)) {
            return $this->base.'/'.$type.'/'.$this->replaceSpace($name).'.'.$id.'/#'.$anchor;
        } else {
            return $this->base.'/'.$type.'/'.$this->replaceSpace($name).'.'.$id.'/';
        }
    }

    protected function socialNetwork($id)
    {
        $this->loadModel('Forum.Profile');
        $socialNetworks = $this->Profile->getSocial($id);
        $socialNetworks = json_decode($socialNetworks, true);
        return $socialNetworks;
    }

    protected function perm_l()
    {
        return $this->ForumPermission->perm_l();
    }

    protected function forumUpdate()
    {

        //1.1.4
        $db = ConnectionManager::getDataSource('default');
        $exist[0] = $db->query('SELECT column_type FROM information_schema.columns WHERE table_name = "forum__forums"');
        if ($exist[0][8]['columns']['column_type'] == 'tinyint(1)') {
            $db->query('
                ALTER TABLE forum__forums MODIFY visible TEXT;
                ALTER TABLE forum__topics MODIFY visible TEXT;
                INSERT INTO forum__configs (config_name, config_value, lang) VALUES ("socialnetwork", 1, "Réseaux sociaux")
           ');
        }

        //1.1.5
        $exist[1] = $db->query('SHOW COLUMNS FROM forum__groups LIKE "position"');
        if (empty($exist[1])) {
            $db->query('
                ALTER TABLE forum__groups ADD position INT;
           ');
        }

        //1.1.7
        $exist[2] = $db->query('SELECT config_name FROM forum__configs WHERE config_name="socialnetwork"');
        if (empty($exist[2])) {
            $db->query('
                INSERT INTO forum__configs (config_name, config_value, lang) VALUES ("socialnetwork", 1, "Réseaux sociaux")
           ');
        }

        //1.1.8
        $exist[3] = $db->query('SHOW TABLES LIKE "forum__tags"');
        if (empty($exist[3])) {
            $db->query('
                CREATE TABLE forum__tags(
                  id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
                  name VARCHAR(50) NOT NULL,
                  icon VARCHAR(30),
                  color VARCHAR(6) NOT NULL,
                  position INT(3) NOT NULL
                );
           ');
        }

        $exist[4] = $db->query('SHOW COLUMNS FROM forum__topics LIKE "tags"');
        if (empty($exist[4])) {
            $db->query('
                ALTER TABLE forum__topics ADD tags VARCHAR(20);
           ');
        }

        //1.1.9 : none

        //1.1.10 : none

        //1.2.0 & 1.2.1 & 1.2.2
        $exist[5] = $db->query('SHOW TABLES LIKE "forum__internals"');
        $exist[6] = $db->query('SELECT * FROM forum__internals');
        if (empty($exist[5]) || empty($exist[6])) {
            $db->query('
                CREATE TABLE IF NOT EXISTS forum__internals(
                  id INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
                  internal_name VARCHAR(255),
                  internal_value VARCHAR(255)
                );
                INSERT INTO forum__internals (internal_name, internal_value) VALUES ("start", NULL);
                INSERT INTO forum__internals (internal_name, internal_value) VALUES ("last_version", "'.$this->version.'")
           ');
        }

        //1.3.0
        $exist[7] = $db->query('SELECT internal_name FROM forum__internals WHERE internal_name="description"');
        if (empty($exist[7])) {
            $db->query('
                INSERT INTO forum__internals (internal_name, internal_value) VALUES ("description", "description");
                INSERT INTO forum__internals (internal_name, internal_value) VALUES ("background", "");
                INSERT INTO forum__internals (internal_name, internal_value) VALUES ("background_effect", "");
                INSERT INTO forum__internals (internal_name, internal_value) VALUES ("icons", "");
                INSERT INTO forum__internals (internal_name, internal_value) VALUES ("index_title", "");
                INSERT INTO forum__internals (internal_name, internal_value) VALUES ("forum_color", "");
                INSERT INTO forum__internals (internal_name, internal_value) VALUES ("topic_color", "");
                INSERT INTO forum__internals (internal_name, internal_value) VALUES ("lasttopic_titlecolor", "");
                INSERT INTO forum__internals (internal_name, internal_value) VALUES ("lasttopic_datecolor", "");
                INSERT INTO forum__internals (internal_name, internal_value) VALUES ("chevron_color", "");
           ');
        }

        //1.4.0

        $this->loadModel('Forum.Group');
        $this->loadModel('Forum.Groups_user');

        if ($this->ForumPermission->findIfInstall('FORUM_TOPICMY_LOCK')) {

            $groups = $this->Group->get();

            $groups[] = [
                'Group' => [
                    'id' => 99
                ]
            ];

            foreach ($groups as $key => $g) {
                $this->ForumPermission->installNew(['group_id' => $g['Group']['id'], 'name' => 'FORUM_TOPICMY_LOCK', 'value' => 1]);
                $this->ForumPermission->installNew(['group_id' => $g['Group']['id'], 'name' => 'FORUM_TAG_TOPIC', 'value' => 0]);
                $this->ForumPermission->installNew(['group_id' => $g['Group']['id'], 'name' => 'FORUM_RENAMEMY_TOPIC', 'value' => 1]);
                $this->ForumPermission->installNew(['group_id' => $g['Group']['id'], 'name' => 'FORUM_RENAME_TOPIC', 'value' => 0]);
                $this->ForumPermission->installNew(['group_id' => $g['Group']['id'], 'name' => 'FORUM_TOPIC_VISIBILY', 'value' => 0]);
                $this->ForumPermission->installNew(['group_id' => $g['Group']['id'], 'name' => 'FORUM_TAG_PUBLIC', 'value' => 0]);
                $this->ForumPermission->installNew(['group_id' => $g['Group']['id'], 'name' => 'FORUM_CREATE_POLL', 'value' => 0]);
                $this->ForumPermission->installNew(['group_id' => $g['Group']['id'], 'name' => 'FORUM_USE_PUNISHMENT', 'value' => 0]);
                $this->ForumPermission->installNew(['group_id' => $g['Group']['id'], 'name' => 'FORUM_TAG_USER', 'value' => 0]);
            }

            $db->query('ALTER TABLE forum__tags ADD used TEXT;');
            $db->query('INSERT INTO forum__configs (config_name, config_value, lang) VALUES ("cache", 0, "Cache");');
        }

        return true;
    }

    protected function backup($type, $value = false)
    {
        switch ($type){
            case 'start':
                $this->ForumBackup->start();
                break;
            case 'delete':
                $this->ForumBackup->delete($value);
                break;
            case 'deleteall':
                $this->ForumBackup->deleteall();
                break;
            case 'set':
                $this->ForumBackup->set($value);
                break;
        }
    }

    protected function isExec(){
        if(exec('echo EXEC') == 'EXEC') return true;
        return false;
    }

    protected function viewVisible($forums, $key)
    {
        $groups = $this->ForumPermission->getRank($this->getIdSession(), true);

        $uns = unserialize($forums[$key]['Forum']['visible']);
        if ($uns) {
            if (array_sum($uns) == 0) return true;
            if(!empty($groups)){
                foreach ($groups as $group){
                    if (isset($uns[$group['id']])) {
                        if($uns[$group['id']] == '1') return true;
                    } else {
                        return false;
                    }
                }
            }
            return false;
        } else {
            return true;
        }
    }

    /*
     * @param $type = ('category', 'topic')
     */

    protected function viewParent($type, $id)
    {
        $this->loadModel('Forum.Forum');

        $groups = $this->ForumPermission->getRank($this->getIdSession(), true);

        switch ($type) {
            case 'category':
                $forums[0]['Forum']['visible'] = $this->Forum->viewVisible($id);

                //Check the category
                if ($this->viewVisible($forums, 0)) {

                    //Check the forum parent
                    $idparent = $this->Forum->info('id_parent', $id);
                    $forums[1]['Forum']['visible'] = $this->Forum->viewVisible($idparent);

                    if ($this->viewVisible($forums, 1)) {
                        return true;
                    }

                }

                return false;

                break;
            case 'topic':

                $this->loadModel('Forum.Topic');

                $idCategory = $this->Topic->info('id_parent', $id);

                if ($this->viewParent('category', $idCategory)) {
                    $topic[0]['Forum']['visible'] = $this->Topic->info('visible', $id);

                    if ($this->viewVisible($topic, 0)) {
                        return true;
                    }
                }

                return false;

                break;
            default :
                return false;

        }
    }

    public function removeScript($content)
    {
        return $this->ForumSecurity->removeScript($content);
    }

}