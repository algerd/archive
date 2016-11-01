<?php
header("Content-Type: text/html; charset=UTF-8");
/* 
 * bool PDOStatement::execute ([ array $input_parameters ] )
 *  $input_parameters - Массив значений, содержащий столько элементов, сколько параметров заявлено в SQL запросе. 
 *      Все значения будут приняты, как имеющие тип PDO::PARAM_STR.
 *      Нельзя привязать несколько значений к одному параметру; например, нельзя привязать два значения к именованному параметру в выражении IN().
 *      Нельзя привязать больше значений, чем заявлено в запросе; если в массиве input_parameters больше элементов, чем заявлено в SQL запросе методом PDO::prepare(), выполнение запроса завершится неудачей, и будет вызвана ошибка.
 *  
 * Запускает подготовленный запрос. Если запрос содержит метки параметров (псевдопеременные), вы должны либо:
 *  - вызвать PDOStatement::bindParam(), чтобы привязать PHP переменные к параметрам запроса: связанные переменные передадут свои значения в запрос и получат выходные значения (если есть)
 *  - либо передать массив значений входных (только входных) параметров
 * 
 * Возвращает TRUE в случае успешного завершения или FALSE в случае возникновения ошибки.
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

echo '<pre>';
 
$sth = $db->prepare('
    SELECT name, age FROM users
        WHERE age < :ageMax AND age > :ageMin
');
$sth->execute(array(':ageMax' => 25, ':ageMin' => 15));
print_r($sth->fetchAll());


// Подготовка SQL запроса с неименованными параметрами (?)
$sth = $db->prepare('
	SELECT name, age FROM users
		WHERE age < ? AND age > ?
');
$sth->execute(array(18, 5));
print_r($sth->fetchAll(PDO::FETCH_OBJ));
