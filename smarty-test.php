<?php

require_once('libs/Smarty.class.php');

$smarty = new Smarty();

$smarty->template_dir = 'templates/';
$smarty->compile_dir  = 'templates_c/';
//$smarty->config_dir   = '/web/www.example.com/guestbook/configs/';
//$smarty->cache_dir    = '/web/www.example.com/guestbook/cache/';

$name = "Jordan";

$smarty->assign('name',$name);//Permet de faire passer une variable de PHP à Smarty

//** un-comment the following line to show the debug console
$smarty->debugging = true;

$smarty->display('smarty-test.tpl');

?>