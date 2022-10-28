<?php
require_once __DIR__.'/vendor/autoload.php';

\Lottery\User::doAuthorize();

echo \Lottery\Kernel::routing();
