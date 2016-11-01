<?php
/*
void spl_autoload ( string $class_name [, string $file_extensions = spl_autoload_extensions() ] )
	class_name - Имя класса (и пространства имен) в нижнем регистре, описание которого требуется загрузить.
	file_extensions - По умолчанию фунция будет искать файлы с расширениями .inc and .php. по всем include-путям, где может располагаться искомое описание класса.
			
spl_autoload — Реализация метода __autoload() по умолчанию - встроенный автозагрузчик.
Эта фунция представляет из себя базовую реализацию метода __autoload(): загрузчик php если не находит класс, то автоматически вызывает функцию __autoload(), в которой указывается путь поиска. 
Если пользователь не определил свою реализацию, и spl_autoload_register() вызывается без параметров, то при каждом последующем вызове __autoload() будет работать именно эта функция.

Суть:
	Если вызывается функция spl_autoload ($class_name, $file_extensions), то запускается встроенный автозагрузчик, 
    который ищет файл class_name.file_extensions в текущей директории и в директории include_path. 
    Если не указывать $file_extensions, то будут искаться файлы с расширениями .inc и .php
	Также расширение можно изменить с помощью функции spl_autoload_extensions('расшир').
	Реализовать функцию spl_autoload(прописать её тело) подобно __autoload нельзя.
 */

//function spl_autoload ($param) {}; // Fatal error: Cannot redeclare spl_autoload() 

spl_autoload('User', '.php'); // поиск файла User.php встроенным загрузчиком в текущей директории илл в include_path

// пример автозагрузки по умолчанию, прописанной в зарегистрированной загрузкиком функции autoload
// такой способ позволит регистрировать и другие функции загрузки, сохраняя автозагрузку по умолчанию
function autoload($className) {
    set_include_path('./library/classes/');
    spl_autoload($className); // загрузка встроенным загрузчиком className.class.php из include_path - ./library/classes/
}
spl_autoload_extensions('.class.php');
spl_autoload_register('autoload');

