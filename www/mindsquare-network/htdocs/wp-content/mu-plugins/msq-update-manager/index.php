<?php

use Msq\UpdateManager;

require "vendor/autoload.php";

global $updateManager;
$updateManager = new UpdateManager('msq');
