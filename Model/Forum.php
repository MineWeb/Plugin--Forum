<?php
class Forum extends ForumAppModel
{

    public function getForum($type = '', $id = false)
    {
        switch ($type) {
            case 'id' :
                return $this->find('first', ['conditions' => ['id' => $id]]);
                break;
            case 'forum' :
                return $this->find('all', ['conditions' => ['id_parent' => 0], 'order' => ['position' => 'ASC']]);
                break;
            case 'withoutforum' :
                return $this->find('all', ['conditions' => ['id_parent >' => 0], 'order' => ['position' => 'ASC']]);
                break;
            case 'categorie' :
                return $this->find('all', ['conditions' => ['id_parent' => $id], 'order' => ['position' => 'ASC']]);
                break;
            default :
                return $this->find('all', ['order' => ['position' => 'ASC']]);
        }
    }

    public function userOnline($userModel, $limit = null)
    {
        $date = date('Y-m-d H:i:s', strtotime('-5 minutes'));
        return $userModel->find('all', ['fields' => ['id', 'pseudo'], 'conditions' => ['forum-last_activity >' => $date], 'limit' => $limit]);
    }

    public function addForum($idUser, $name, $position, $image, $description)
    {
        $this->create();
        $this->set(['id_user' => $idUser, 'id_parent' => 0, 'forum_name' => $name, 'position' => $position, 'forum_image' => $image, 'forum_description' => $description]);
        return $this->save();
    }

    public function addCategory($idUser, $name, $position, $parent, $image, $lock, $automaticLock)
    {
        $this->create();
        $this->set(['id_user' => $idUser, 'forum_name' => $name, 'position' => $position, 'id_parent' => $parent,  'forum_image' => $image, 'lock' => $lock, 'automatic_lock' => $automaticLock]);
        return $this->save();
    }

    public function admin_delete($id)
    {
        return $this->delete($id);
    }

    //Get a information
    public function info($type = false, $id = false, $name = false)
    {
        switch ($type) {
            case 'forum' :
                return $this->find('all', ['conditions' => ['id' => $id]]);
                break;
            case 'parent_title' :
                return $this->find('first', ['conditions' => ['id' => $id]]);
                break;
            case 'parent_href' :
                return $this->find('first', ['conditions' => ['id' => $id]]);
                break;
            case 'id_parent' :
                return $this->find('first', ['conditions' => ['id' => $id]])['Forum']['id_parent'];
                break;
        }
    }

    public function update($type = false, $id = false, $datas = false)
    {
        if ($type == 'forum') {
            return $this->updateAll(['forum_name' => "'".$datas['name']."'", 'position' => "'".$datas['position']."'", 'forum_image' => "'".$datas['image']."'", 'forum_description' => "'".str_replace("'", "\'", $datas['forum_description'])."'"], ['id' => $id]);
        } elseif ($type == 'category') {
            return $this->updateAll(['forum_name' => "'".$datas['name']."'", 'id_parent' => "'".$datas['id_parent']."'", 'position' => $datas['position'], 'forum_image' => "'".$datas['forum_image']."'", 'lock' => $datas['lock'], 'automatic_lock' => $datas['automatic_lock']], ['id' => $id]);
        }
    }

    public function forumExist($id, $slug)
    {
        if($this->hasAny(['id' => $id, 'forum_name' => $slug])) return true;
    }

    public function getConfig()
    {
        $config = $this->find('first');
        if(empty($config)) {
            return true;
        }
    }

    public function isLock($id)
    {
        return ($this->hasAny(['id' => $id, 'lock' => true])) ? true : false;
    }

    public function isAutoLock($id)
    {
        return $this->find('first', ['conditions' => ['id' => $id]])['Forum']['automatic_lock'];
    }

    public function updateVisible($id, $visible)
    {
        return $this->updateAll(['visible' => "'".$visible."'"], ['id' => $id]);
    }

    public function viewVisible($id)
    {
        return $this->find('first', ['conditions' => ['id' => $id]])['Forum']['visible'];
    }

    public function count($type)
    {
        switch ($type) {
            case 'all':
                return $this->find('count');
                break;
            case 'category':
                return $this->find('count', ['conditions' => ['id_parent !=' => 0]]);
                break;
            case 'forum':
                return $this->find('count', ['conditions' => ['id_parent' => 0]]);
                break;
        }
    }
}