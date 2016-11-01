<?php
header("Content-Type: text/html; charset=UTF-8");
/* 
 * PDOStatement PDO::prepare ( string $statement [, array $driver_options = array() ] )
 *		$driver_options - массив содержит одну или более пар ключ=>значение для установки значений атрибутов объекта PDOStatement, который будет возвращен из этого метода. 
 *		В основном, вы будете использовать этот массив для присвоения значения PDO::ATTR_CURSOR атрибуту PDO::CURSOR_SCROLL, чтобы получить прокручиваемый курсор.
 *			PDO::CURSOR_FWDONLY (integer) - Предписание создать объект PDOStatement с последовательным курсором. Последовательные курсоры выбираются по умолчанию, так как это наиболее общий и быстрый способ доступа к данным в БД из PHP.
 *			PDO::CURSOR_SCROLL (integer) - Предписание создать объект PDOStatement с прокручиваемым курсором. Передача констант PDO::FETCH_ORI_* позволяет задать режим работы курсора, то, как будут выбираться строки из результирующего набора запроса.
 * Если СУБД успешно подготовила запрос, PDO::prepare() возвращает объект PDOStatement. 
 * Если подготовить запрос не удалось, PDO::prepare() возвращает FALSE или выбрасывает исключение PDOException
 * 
 * Подготавливает SQL запрос к базе данных к запуску посредством метода PDOStatement::execute(). 
 * Запрос может содержать именованные (:name) или неименованные (?) псевдопеременные, которые будут заменены реальными значениями во время запуска запроса на выполнение. 
 * Использовать одновременно и именованные, и неименованные псевдопеременные в одном запросе нельзя, необходимо выбрать что-то одно.
 * Вы должны подбирать уникальные имена псевдопеременных для каждого значение, которое необходимо передавать в запрос при вызове PDOStatement::execute(). 
 * Нельзя использовать одну псевдопеременную в запросе дважды. Также нельзя к одной именованной псевдопеременной привязать несколько значений, как например, в выражении IN() SQL запроса.
 * 
 * Подготовленный запрос автоматически экранирует вставляемые значения переменных, поэтому дополнительного экранирования с помощью PDO::quote() не требуется.
 * Вызов PDO::prepare() и PDOStatement::execute() для запросов, которые будут запускаться многократно с различными параметрами, повышает производительность приложения, 
 * так как позволяет драйверу кэшировать на клиенте и/или сервере план выполнения запроса и метаданные, 
 * а также помогает избежать SQL иньекций, так как нет необходимости экранировать передаваемые параметры.
 * 
 * PDO::prepare() создаёт объект подготовленного запроса PDOStatement и для работы с ним надо использовать методы PDOStatement (execute(), fetch() и т.д.)
 *
 */

// Создадим БД 'test'
$db = new PDO('mysql:host=localhost;', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("DROP DATABASE IF EXISTS test");
$db->exec("CREATE DATABASE test CHARACTER SET utf8 COLLATE utf8_general_ci"); 

// Полключимся к БД 'test'
$db = new PDO('mysql:host=localhost;dbname=test', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("
	CREATE TABLE users (
		id      INT         NOT NULL AUTO_INCREMENT,
		name    VARCHAR(50) NOT NULL DEFAULT '',
		age     INT			NOT NULL DEFAULT 0,
		CONSTRAINT pk_users_id   PRIMARY KEY (id)                 
	) ENGINE = INNODB
");
$db->exec("
	INSERT INTO users(name, age) VALUES 
		('Иванов', 10),
		('Петров', 16),
		('Сидоров', 20),
		('Степанов', 27),
		('Никитин', 35)
");

///////////////////// Prepare ///////////////////////////////////////
echo '<pre>';
// Подготовка SQL запроса с именованными параметрами
$sql = 'SELECT name, age FROM users
    WHERE age < :ageMax AND age > :ageMin';

$sth = $db->prepare($sql);
//$sth = $db->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

$sth->execute(array(':ageMax' => 25, ':ageMin' => 15));
print_r($sth->fetchAll());

$sth->execute(array(':ageMax' => 40, ':ageMin' => 20));
print_r($sth->fetchAll());


// Подготовка SQL запроса с неименованными параметрами (?)
$sth = $db->prepare('
	SELECT name, age FROM users
		WHERE age < ? AND age > ?
');

$sth->execute(array(18, 5));
print_r($sth->fetchAll(PDO::FETCH_OBJ));

$sth->execute(array(30, 17));
print_r($sth->fetchAll(PDO::FETCH_OBJ));

