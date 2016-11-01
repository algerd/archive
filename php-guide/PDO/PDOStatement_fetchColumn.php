<?php
header("Content-Type: text/html; charset=UTF-8");
/* 
 * Возвращает данные одного столбца следующей строки результирующей таблицы. Если в результате запроса строк больше нет, функция вернет FALSE.
 * 
 * string PDOStatement::fetchColumn([ int $column_number = 0 ] )
 *      $column_number - Номер столбца, данные которого необходимо извлечь. Нумерация начинается с 0. 
 *          Если параметр не задан, PDOStatement::fetchColumn() выберет данные первого столбца.
 */

// Создадим БД 'test'
$db = new PDO('mysql:host=localhost;', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("DROP DATABASE IF EXISTS test");
$db->exec("CREATE DATABASE test CHARACTER SET utf8 COLLATE utf8_general_ci"); 
// Подключение и работа с БД 'test'
$db = new PDO('mysql:host=localhost;dbname=test', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("
	CREATE TABLE user (
		id        INT         NOT NULL AUTO_INCREMENT,
		login     VARCHAR(50) NOT NULL DEFAULT '',
		password  VARCHAR(50) NOT NULL DEFAULT '',
		CONSTRAINT pk_users_id   PRIMARY KEY (id)                 
	) ENGINE = INNODB
");
$db->exec("
	INSERT INTO user(login, password) VALUES 
		('Иванов', '111'),
		('Петров', '111'),
		('Сидоров', '111'),
		('Степанов', '222'),
		('Никитин', '222'),
        ('Михайлов', '333'),
        ('Васильев', '333')
");

$sth = $db->prepare("SELECT login, password FROM user");
$sth->execute();

echo "Извлекаем построчно данные столбца 1: <br>";
while($column = $sth->fetchColumn(0))
    echo $column.'<br>';
