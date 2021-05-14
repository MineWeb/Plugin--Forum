<?php
class ForumAppSchema extends CakeSchema
{

    /*
     * Coucou à toi !
     * Si tu es ici, c'est que tu es curieux ! La curiosité n'est pas un défault, mais une qualité !
     * De ce fait, si tu es développeur, n'hésites pas à regarder comment le plugin à été développé et à t'inspirer si tu es aussi développeur.
     * Sache qu'ouvrir un code non écrit pas toi n'es as interdit, c'est une méthode d'apprentissage comme une autre.
     * Sur ce petit message, je te laisse découvrir le code par toi-même, n'hésite pas à poser des questions à l'auteur du plugin si tu voudrais modifier quelque chose.
     * Celui-ci pourra mieux te conseiller ;) afin de ne pas faire de bêtise.
     * Sur ce bonne lecture !
     * Pierre alias PHPierre
     *
     * PS : J'ai caché un autre Easter egg dans le code.
     */

    public $file = 'schema.php';

    public function before($event = [])
    {
        return true;
    }

    public function after($event = []) {}

    public $forum__configs = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'config_name' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 30, 'unsigned' => false],
        'config_value' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 100, 'unsigned' => false],
        'lang' => ['type' => 'text', 'null' => false, 'default' => null, 'length' => 50, 'unsigned' => false]
    ];

    public $forum__conversations = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'id_conversation' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'first' => ['type' => 'boolean', 'null' => true, 'default' => 1, 'length' => 1, 'unsigned' => false],
        'read' => ['type' => 'boolean', 'null' => true, 'default' => 1, 'length' => 1, 'unsigned' => false],
        'title' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 100, 'unsigned' => false],
        'author_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'author_ip' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'unsigned' => false],
        'msg_date' => ['type' => 'datetime', 'null' => false, 'default' => null],
        'content' => ['type' => 'text', 'null' => false, 'default' => null, 'length' => 4000, 'unsigned' => false]
    ];

    public $forum__conversation_recipients = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'id_conversation' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'author_recipient' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false]
    ];

    public $forum__forums = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'id_parent' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'id_user' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'position' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 3, 'unsigned' => false],
        'forum_name' => ['type' => 'text', 'null' => false, 'default' => null, 'length' => 150, 'unsigned' => false],
        'forum_description' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 200, 'unsigned' => false],
        'forum_image' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 500, 'unsigned' => false],
        'lock' => ['type' => 'boolean', 'null' => true, 'default' => 0, 'length' => 1, 'unsigned' => false],
        'permission' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 4000, 'unsigned' => false],
        'automatic_lock' => ['type' => 'boolean', 'null' => true, 'default' => 0, 'length' => 1, 'unsigned' => false],
        'visible' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 200, 'unsigned' => false]
    ];

    public $forum__groups = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'group_name' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 20, 'unsigned' => false],
        'group_description' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 200, 'unsigned' => false],
        'color' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 6, 'unsigned' => false],
        'position' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 2, 'unsigned' => false]
    ];

    public $forum__groups_users = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'id_user' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'id_group' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'domin' => ['type' => 'boolean', 'null' => true, 'default' => 0, 'length' => 1, 'unsigned' => false]
    ];

    public $forum__histories = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'date' => ['type' => 'datetime', 'null' => false, 'default' => null],
        'id_user' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'ip' => ['type' => 'string', 'null' => false, 'default' => '?', 'length' => 50, 'unsigned' => false],
        'category' => ['type' => 'text', 'null' => false, 'default' => null, 'length' => 50, 'unsigned' => false],
        'action' => ['type' => 'text', 'null' => false, 'default' => null, 'length' => 200, 'unsigned' => false],
        'content' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 10000, 'unsigned' => false]

    ];

    public $forum__internals = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'internal_name' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 30, 'unsigned' => false],
        'internal_value' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 100, 'unsigned' => false]
    ];

    public $forum__insults = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'word' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 40, 'unsigned' => false],
        'replace' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 40, 'unsigned' => false]
    ];

    public $forum__msg_reports = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'id_user' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'id_msg' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'date' => ['type' => 'datetime', 'null' => false, 'default' => null],
        'reason' => ['type' => 'text', 'null' => false, 'default' => null, 'length' => 150, 'unsigned' => false],
        'content' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 500, 'unsigned' => false]
    ];

    public $forum__notes = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'id_user' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'id_to_user' =>  ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'id_message' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'type' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false]
    ];

    public $forum__forum_permissions = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'group_id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'name' => ['type' => 'string', 'null' => false, 'default' => 'FORUM', 'length' => 50, 'unsigned' => false],
        'value' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 40, 'unsigned' => false]
    ];

    public $forum__profiles = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'id_user' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        //42*((42*42)^42/42))/(((42^42))*((42^42)))*42+42-42/42+42+42 = 167 -> 300 :)
        'description' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 300, 'unsigned' => false],
        'social' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 2000, 'unsigned' => false],
    ];

    public $forum__punishments = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'id_user' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'id_to_user' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'date' => ['type' => 'datetime', 'null' => false, 'default' => null],
        'reason' => ['type' => 'text', 'null' => false, 'default' => null, 'length' => 150, 'unsigned' => false]
    ];

    public $forum__tags = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'name' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 50, 'unsigned' => false],
        'icon' => ['type' => 'string', 'null' => true, 'default' => null, 'length' => 30, 'unsigned' => false],
        'color' => ['type' => 'string', 'null' => false, 'default' => null, 'length' => 6, 'unsigned' => false],
        'position' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 3, 'unsigned' => false],
        'used' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 500, 'unsigned' => false]
    ];

    public $forum__topics = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'id_parent' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'id_user' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'id_topic' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false],
        'name' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 150, 'unsigned' => false],
        'first' => ['type' => 'boolean', 'null' => true, 'default' => 0, 'length' => 1, 'unsigned' => false],
        'stick' => ['type' => 'boolean', 'null' => true, 'default' => 0, 'length' => 1, 'unsigned' => false],
        'lock' => ['type' => 'boolean', 'null' => true, 'default' => 0, 'length' => 1, 'unsigned' => false],
        'content' => ['type' => 'text', 'null' => false, 'default' => null, 'length' => 4000, 'unsigned' => false],
        'date' => ['type' => 'datetime', 'null' => false, 'default' => null],
        'last_edit' => ['type' => 'datetime', 'null' => true, 'default' => null],
        'permission' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 4000, 'unsigned' => false],
        'visible' => ['type' => 'text', 'null' => true, 'default' => null, 'length' => 200, 'unsigned' => false]
    ];

    public $users = [
        'forum-last_activity' => ['type' => 'datetime', 'null' => true, 'default' => null]
    ];

    public $forum__viewws = [
        'id' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 20, 'unsigned' => false, 'key' => 'primary'],
        'date' => ['type' => 'datetime', 'null' => false, 'default' => null],
        'ip' => ['type' => 'string', 'null' => false, 'default' => '?', 'length' => 50, 'unsigned' => false],
        'id_topic' => ['type' => 'integer', 'null' => false, 'default' => null, 'length' => 8, 'unsigned' => false]
    ];
}
