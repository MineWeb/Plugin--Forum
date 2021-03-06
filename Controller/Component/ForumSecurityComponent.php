<?php

class ForumSecurityComponent extends Component
{
    public static function removeScript($content)
    {
//        require "../Plugin/Forum/Core/lib/htmLawed/htmLawed.php";
//
//        return htmLawed($content, ['safe' => 1]);
        require __DIR__ . "/../../Core/lib/htmlpurifier/library/HTMLPurifier.auto.php";
        $config = HTMLPurifier_Config::createDefault();
        $purifier = new HTMLPurifier($config);
        return $purifier->purify($content);
    }
}
