<?php

declare(strict_types=1);

define('BASE_PATH', __DIR__);

require BASE_PATH . '/src/bootstrap.php';

App\Core\App::run();
