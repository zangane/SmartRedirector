<?php
require __DIR__ . '/../vendor/autoload.php';

use Zangane\SmartRedirector\Redirector;

try {
    $redirector = new Redirector(__DIR__ . '/../redirect-rules.json');
    $redirector->handle();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
