<?php
require_once('classes/App.php');
require_once('classes/AppConst.php');
$environment = AppConst::ENV_LOCAL;
//$environment = AppConst::ENV_PRODUCTION;
//$environment = AppConst::ENV_STAGING;
App::setEnvironment($environment);
App::init();
error_reporting(E_ALL);
set_time_limit(0);
date_default_timezone_set('America/New_York');
?>
