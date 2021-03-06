<?php

/**
 * Class PageStatusTest
 *
 * How to execute this test (need composer & PHPUnit before) :
 * Move to the app folder and execute this command : .\Console\cake test Forum Controller/PageStatus
 *
 * app\Controller\Component\UtilComponent.php:46
 * if (!isset($_SERVER["REMOTE_ADDR"]))
 * {
 *     return "::1";
 * }
 *
 * app\Controller\Component\UpdateComponent.php:97
 * if($release == NULL) {
 *     return "1.7.0";
 * }
 *
 * app\Controller\Component\StatisticsComponent.php:76
 * if (php_sapi_name() == "cli") {
 * +       return;
 * +  }
 */

App::uses('Controller', 'Controller');
App::uses('View', 'View');

App::uses('AppController', 'AppController');
App::uses('ForumAppController', 'Forum.ForumAppController');
App::uses('ForumController', 'Forum.ForumController');

class PageStatusTest extends ControllerTestCase
{
    /**
     * @var Controller
     */
    private $Controller;

    /**
     * @var View
     */
    private $View;

    public function setUp()
    {
        parent::setUp();

        $this->Controller = new Controller();
        $this->View = new View($this->Controller);
    }

    public function testForum()
    {
        $result = $this->View->requestAction('/forum', [
            'return' => 'contents'
        ]);

        $this->assertContains('forum-breadcrumb-home', $result);
    }

}
