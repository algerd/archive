<?php
header("Content-Type: text/html; charset=UTF-8");
/* 
 * Задает режим выборки по умолчанию для объекта запроса
 * 
 * bool PDOStatement::setFetchMode ( int $mode )
 * bool PDOStatement::setFetchMode ( int $PDO::FETCH_COLUMN , int $colno )
 * bool PDOStatement::setFetchMode ( int $PDO::FETCH_CLASS , string $classname , array $ctorargs )
 * bool PDOStatement::setFetchMode ( int $PDO::FETCH_INTO , object $object )
 *      mode - Режим выборки: одна из констант PDO::FETCH_... (см. PDOStatement_fetch.php)
 *      colno - Номер столбца.
 *      classname - Имя класса.
 *      ctorargs - массив аргументов конструктора класса.
 *      object - Объект. 
 * Возвращает 1 в случае успешной установки режима или FALSE в случае возникновения ошибки.
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


print("PDO::FETCH_ASSOC:<br>");
$sth->setFetchMode( PDO::FETCH_ASSOC);
$sth->execute();
print_r($sth->fetch());

print("PDO::FETCH_COLUMN:<br>");
$sth->setFetchMode( PDO::FETCH_COLUMN, 0);
$sth->execute();
echo $sth->fetch().'<br>';

class User{
    public $login;
    function __construct( $test = null ) {
        $this->login = 'guest';
        if ( $test !== null ) $this->test = $test;
    }
}
print("PDO::FETCH_CLASS: Запрос сам создаёт объект указанного класса, наполняет его свойствами из полей и только потом вызывает его конструктор:<br>");
$sth->setFetchMode( PDO::FETCH_CLASS, 'User');
$sth->execute();
$user = $sth->fetch();
print_r ($user);

print("PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE: Запрос сам создаёт объект указанного класса, вызывает его конструктор и только потом заполняет его свойствами из полей:<br>");
$sth->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User', ['MyTest']);
$sth->execute();
$user = $sth->fetch(); 
print_r ($user);

print("PDO::FETCH_INTO: Принудительно создаём объект и передаём его для заполнения свойств полями:<br>");
$user= new user();
$sth->setFetchMode( PDO::FETCH_INTO, $user);
$sth->execute();
$user= $sth->fetch();
print_r ($user);
