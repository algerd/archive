<?php
header("Content-Type: text/html; charset=UTF-8");
/* 
 * bool PDO::beginTransaction ( void ) - Выключает режим автоматической фиксации транзакции.
 * bool PDO::commit ( void ) - Фиксирует транзакцию, возвращая соединение с базой данных в режим автоматической фиксации до тех пор, пока следующий вызов PDO::beginTransaction() не начнет новую транзакцию.
 * bool PDO::rollBack ( void ) - Откатывает изменения в базе данных сделанные в рамках текущей транзакции, которая была создана методом PDO::beginTransaction(). Если активной транзакции нет, будет выброшено исключение PDOException.
 * bool PDO::inTransaction ( void ) - Проверяет, есть ли активные транзакции в настоящее время внутри драйвера.
 * 
 * Механизм транзакций реализован путем "временного сохранения" всех изменений и дальнейшего применения этих изменений, как единого целого.
 * PDO::beginTransaction() выключает режим автоматической фиксации транзакции. 
 * В то время, как режим автоматической фиксации выключен, изменения, внесенные в базу данных через объект экземпляра PDO, 
 * не применяются, пока вы не завершите транзакцию, вызвав PDO::commit(). 
 * Вызов PDO::rollBack() откатит все изменения в базе данных и вернет соединение к режиму автоматической фиксации. 
 * 
 * MySQL автоматически выполняют неявную фиксацию, когда выражения языка описания данных (DDL), такие как DROP TABLE или CREATE TABLE, находятся внутри транзакции. (т.е. откатить их не удастся) 
 * Поэтому применять выражения подобные DROP TABLE или CREATE TABLE очень осторожно.
 */

if ( !in_array('mysql', PDO::getAvailableDrivers()) ) 
	throw new Exception ('Требуется подключение расширения php_pdo_mysql.dll');

// Создадим БД 'test'
$db = new PDO('mysql:host=localhost;', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("DROP DATABASE IF EXISTS test");
$db->exec("CREATE DATABASE test CHARACTER SET utf8 COLLATE utf8_general_ci"); 

// Подключение и работа с БД 'test'
$db = new PDO('mysql:host=localhost;dbname=test', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->exec("
	CREATE TABLE users (
		id        INT         NOT NULL AUTO_INCREMENT,
		login     VARCHAR(50) NOT NULL DEFAULT '',
		password  VARCHAR(50) NOT NULL DEFAULT '',
		CONSTRAINT pk_users_id   PRIMARY KEY (id)                 
	) ENGINE = INNODB
");
$db->exec("
	INSERT INTO users(login, password) VALUES 
		('Иванов', '12aff3'),
		('Петров', '123sf21'),
		('Сидоров', 'sfssf'),
		('Степанов', 'wrfsf'),
		('Никитин', 'sfsfss')
");

///////////////// Транзакция /////////////////////	
try {
	
  $db->beginTransaction();
  if ( $db->inTransaction() ) echo "Выполняется транзакция<br>";
  
  $db->exec("INSERT INTO users (login, password) values ('Joe', 'dgdfgdg')");
  // вставка с ошибкой (неправильное поле error)
  $db->exec("INSERT INTO users (error, password) values ('Joe2', 'dgdfgdg2')");
  
  $db->commit(); 
} 
catch (PDOException $e) {
  $db->rollBack();
  echo "Ошибка: " . $e->getMessage();
}

// Проверка
$result = $db->query('SELECT id, login, password FROM users');
echo '<table>';
foreach ($result as $row) {
    echo '<tr>';
    echo '<td>'.$row['id'].'</td>';
    echo '<td>'.$row['login'].'</td>';
    echo '<td>'.$row['password'].'</td>';
    echo '</tr>';
}
echo '</table>';


/////////////////// Автоматическая, неоткатываемая транзакция  
$db->beginTransaction();
$db->exec("
	CREATE TABLE books (
		id       INT         NOT NULL AUTO_INCREMENT,
		name     VARCHAR(50) NOT NULL DEFAULT '',
		author   VARCHAR(50) NOT NULL DEFAULT '',
		CONSTRAINT pk_users_id   PRIMARY KEY (id)                 
	) ENGINE = INNODB
");
$db->rollBack();
// обратимся к 'books' - ошибки не будет, таблица books была создана несмотря на rollBack()
$db->query('SELECT * FROM books');


$db->beginTransaction();
$db->exec("DROP TABLE books");
$db->rollBack();
// обратимся к 'books' - будет ошибка, таблица books была стёрта несмотря на rollBack()
//$db->query('SELECT * FROM books');