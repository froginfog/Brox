<?php
require_once  __DIR__."/vendor/autoload.php";
date_default_timezone_set(TIMEZONE);
session_start();

\Core\Router::match();