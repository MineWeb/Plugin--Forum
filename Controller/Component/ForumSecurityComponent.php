<?php

class ForumSecurityComponent extends Component
{
    public static function removeScript($content)
    {
        require __DIR__ . "/../../Core/lib/htmlpurifier/library/HTMLPurifier.auto.php";
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($content);
    }
}
