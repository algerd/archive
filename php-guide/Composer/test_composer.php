<?php
// подключение автозагрузчика composer
require './vendor/autoload.php';

///////////////// PSR-4 (PSR-0)
// Загрузка класса Admin с директории src/controller/main/admin.php, прописанной композером в файле vendor/composer/autoload_psr4 (autoload_namespaces.php для psr-0) 
use \Main\Admin;
// Загрузка класса User c директории src/model/user.php, прописанной композером в файле vendor/composer/autoload_psr4 (autoload_namespaces.php для psr-0) 
use \Model\User;

$main = new \Main\Admin();
$main->show();

$main = new Main\Admin();
$main->show();

$main = new Admin();
$main->show();

$user = new User();
$user->show();


///////////////// Classmap
// Загрузка класса Moder c директории classes/moder.php, прописанной композером в файле vendor/composer/autoload_classmap.php
$moder = new Moder();
$moder->show();

////////////////// Files
// Загрузка функции show() с директории functions/show.php, прописанной композером в файле vendor/composer/autoload_files.php
show();
name();