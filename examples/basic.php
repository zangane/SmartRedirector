<?php

require "../vendor/autoload.php";

use SmartRedirector\SmartRedirector;

(new SmartRedirector("https://example.com"))
    ->statusCode(301)
    ->expireAt("2025-12-31 23:59:59")
    ->allowIP("1.2.3.4")
    ->blockIP("5.6.7.8")
    ->allowUserAgent("Chrome")
    ->setLogFile(__DIR__ . "/../logs/redirect.log")
    ->execute();
