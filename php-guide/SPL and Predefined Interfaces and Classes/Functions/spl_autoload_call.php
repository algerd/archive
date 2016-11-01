<?php
/*
void spl_autoload_call ( string $class_name )
    class_name - Имя искомого класса.

spl_autoload_call — Попытка загрузить описание класса всеми зарегистрированными методами __autoload()
Эту фунцию можно использовать для ручного поиска описания класса или интерфейса, используя все зарегистрированные реализации метода __autoload.
*/

function my_autoloader1($class) { include 'classes1/' . $class . '.class.php';}
function my_autoloader2($class) { include 'classes2/' . $class . '.class.php';}

spl_autoload_register('my_autoloader1');
spl_autoload_register('my_autoloader2');

// принудительная загрузка класса, используя зарегистрированные функции my_autoloader1 и my_autoloader2
spl_autoload_call('User');    


