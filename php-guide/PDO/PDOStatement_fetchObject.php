<?php
header("Content-Type: text/html; charset=UTF-8");
/* 
 * Извлекает следующую строку и возвращает ее в виде объекта. 
 * Этот метод является альтернативой вызову PDOStatement::fetch() с параметром PDO::FETCH_CLASS или PDO::FETCH_OBJ.
 * В отличие от PDOStatement::fetch() здесь нельзя задать параметр PDO::FETCH_PROPS_LATE чтобы свойства заполнялись из полей после конструктора,
 * поэтому инициализация свойств из конструктора не должна пересекаться с заполнением свойств из полей.
 * 
 * mixed PDOStatement::fetchObject([ string $class_name = "stdClass" [, array $ctor_args ]] )
 *      $class_name - Имя класса создаваемого объекта.
 *      $ctor_args - Элементы этого массива будут переданы в конструктор класса.
 * Возвращает новый объект указанного класса, имена свойств которого соответствуют именам столбцов результирующего набора или FALSE в случае возникновения ошибки.
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
echo '<pre>';


class User{
    public $login;
    public $test;
    function __construct($test = null) {
        $this->test = $test;
        // конструктор переопределит свойство после его заполнения из поля:
        //$this->login = 'guest';
    }
}
echo "Извлекаем построчно данные в объекты класса User как fetch(PDO::FETCH_CLASS): <br>";
print_r($sth->fetchObject('User', ['testUser']));

echo "Извлекаем построчно данные в объекты класса stdClass как fetch(PDO::FETCH_OBJ): <br>";
print_r($sth->fetchObject());


