<?php
/*
 * PDO::getAvailableDrivers — Возвращает массив доступных драйверов PDO
 */
print_r(PDO::getAvailableDrivers());
echo "<br/>\n";

/* 
 * Объект соединения с бд создаётся с помощью конструктора:
 * PDO::__construct ( string $dsn [, string $username [, string $password [, array $driver_options ]]] )
 *	string $dsn - состоит из имени драйвера PDO, за которым следует двоеточие и специфический синтаксис подключения драйвера PDO.
 *  $driver_options - массив ['PDO::аттрибут'=>'PDO::константа', ...]  (задаются также с помощью setAttribute()) 
 */
/*
 * Примеры с MySQL:
 */
// Дополнительная проверка подключения php-расширения нужного драйвера
if ( !in_array('mysql', PDO::getAvailableDrivers()) ) 
	throw new Exception ('Требуется подключение расширения php_pdo_mysql.dll');
// Создадим БД 'test' для выполнения примеров
$db = new PDO('mysql:host=localhost;', 'root');
$db->exec("DROP DATABASE IF EXISTS test");
$db->exec("CREATE DATABASE test CHARACTER SET utf8 COLLATE utf8_general_ci");


try{
    // Подключиться к существующей базе данных 'test' и задать параметры(аттрибуты) соединения
    $dsn = 'mysql:host=localhost;dbname=test';
    $user = 'root';
    $password = '';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];
    $db = new PDO($dsn, $user, $password, $options);
    
    /*... какой-то код с $db ...*/
    
    //Закрыть соединение:
    $db = null;
    
}
catch (PDOException $e) {
    echo $e->getMessage();
}
    
/*
	Параметры(аттрибуты) соединения можно задавать с помощью метода: 
		setAttribute ( int $attribute , mixed $value )
			int $attribute - константы PDO::ATTR_...
			mixed $value - константы PDO::..., которые соответсвуют константам PDO::ATTR_
			
	PDO::ATTR_CASE: Приводить имена столбцов к заданному регистру.
		PDO::CASE_LOWER: Приводить имена столбцов к нижнему регистру.
		PDO::CASE_NATURAL: Оставлять имена столбцов в том виде, в котором они выданы драйвером.
		PDO::CASE_UPPER: Приводить имена столбцов к верхнему регистру.

	PDO::ATTR_ERRMODE: Режим сообщений об ошибках.
		PDO::ERRMODE_SILENT: Только установка кодов ошибок.
		PDO::ERRMODE_WARNING: Вызывать E_WARNING.
		PDO::ERRMODE_EXCEPTION: Выбрасывать исключения.

	PDO::ATTR_ORACLE_NULLS (доступен для всех драйверов, не только для Oracle): Преобразование NULL в пустые строки.
		PDO::NULL_NATURAL: Без преобразования.
		PDO::NULL_EMPTY_STRING: Пустые строки преобразовывать в NULL.
		PDO::NULL_TO_STRING: NULL преобразовывать в пустые строки.

	PDO::ATTR_STRINGIFY_FETCHES: Преобразовывать числовые значения в строки во время выборки. Значение типа bool.
	PDO::ATTR_STATEMENT_CLASS: Задает пользовательский класс производный от PDOStatement. 
 		Атрибут нельзя использовать с PDO, использующими постоянные соединения. Принимает массив array(string classname, array(mixed constructor_args)).
	PDO::ATTR_TIMEOUT: Задает таймаут в секундах. Не все драйверы поддерживают эту опцию(MySQL не поддерживает). Также назначение этого таймаута может отличаться в разных драйверах. 
 		Например, sqlite будет ждать это количество времени получения блокировки на запись. А другие драйверы могут использовать его, как таймаут подключения или чтения. Атрибут принимает значение типа int.
	PDO::ATTR_AUTOCOMMIT (доступен в OCI, Firebird и MySQL): Требуется ли автоматическая фиксация каждого отдельного выражения в запросе.
	PDO::ATTR_EMULATE_PREPARES Включение или выключение эмуляции подготавливаемых запросов. Некоторые драйверы не поддерживают подготавливаемые запросы, либо их поддержка ограничена.
 		Эта настройка указывает PDO всегда эмулировать подготавливаемые запросы (если TRUE) или пытаться использовать родные средства драйвера (если FALSE). Если драйвер не сможет подготовить запрос, эта настройка сбросится в режим эмуляции. Атрибут принимает значение типа bool.
	PDO::MYSQL_ATTR_USE_BUFFERED_QUERY (доступен в MySQL): Использовать буферизованные запросы.
	PDO::ATTR_DEFAULT_FETCH_MODE: Задает режим выборки данных по умолчанию. Описание возможных режимов приведено в документации к методу PDOStatement::fetch().
 */

// Set the case in which to return column_names.
$db->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); 


/*
 * Получить параметр(атрибут) соеденения с базой данных можно с помощью 
 *		getAttribute ( int $attribute ):
 *	PDO::ATTR_AUTOCOMMIT
 *	PDO::ATTR_CASE
 *	PDO::ATTR_CLIENT_VERSION
 *	PDO::ATTR_CONNECTION_STATUS
 *	PDO::ATTR_DRIVER_NAME
 *	PDO::ATTR_ERRMODE
 *	PDO::ATTR_ORACLE_NULLS
 *	PDO::ATTR_PERSISTENT 
 *	PDO::ATTR_PREFETCH (не поддерживается MySQL)
 *	PDO::ATTR_SERVER_INFO
 *	PDO::ATTR_SERVER_VERSION
 *	PDO::ATTR_TIMEOUT (не поддерживается MySQL)
 */
$attributes = array(
    "AUTOCOMMIT", 
	"CASE", 
	"CLIENT_VERSION",
	"CONNECTION_STATUS",
	"DRIVER_NAME",
	"ERRMODE", 
    "ORACLE_NULLS", 
	"PERSISTENT", 
	"SERVER_INFO", 
	"SERVER_VERSION",
);
foreach ($attributes as $val) {
    echo "PDO::ATTR_$val: ";
    echo $db->getAttribute(constant("PDO::ATTR_$val")) . "<br/>\n";
}
