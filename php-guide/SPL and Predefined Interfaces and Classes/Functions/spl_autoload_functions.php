<?php
/*
array spl_autoload_functions ( void )
	Возвращаемые значения: Массив (array) имен всех зарегистрированных фунций __autoload. Если стэк функций автозагрузки не активирован, будет возвращено значение FALSE. 
	Если зарегистрированных функций нет, будет возвращен пустой массив.

spl_autoload_functions — Получение списка всех зарегистрированных фунций __autoload()
*/

function my_autoloader1($class) { include 'classes1/' . $class . '.class.php';}
function my_autoloader2($class) { include 'classes2/' . $class . '.class.php';}

spl_autoload_register('my_autoloader1');
spl_autoload_register('my_autoloader2', true, true); // будет выбрасывать исключения при невозможности автозагрузки и my_autoloader2 ставим выше my_autoloader1 в очереди загрузки

var_dump(spl_autoload_functions());