<?php
header("Content-Type: text/html; charset=UTF-8");
/* 
 * Извлекает следующую строку из результирующего набора объекта PDOStatement.
 * 
 * mixed PDOStatement::fetch ([ int $fetch_style [, int $cursor_orientation = PDO::FETCH_ORI_NEXT [, int $cursor_offset = 0 ]]] )
 *  $fetch_style - Определяет, в каком виде следующая строка будет возвращена в вызывающий метод. Это может быть одна из констант PDO::FETCH_*. По умолчанию PDO::ATTR_DEFAULT_FETCH_MODE (что равносильно PDO::FETCH_BOTH).
 *      PDO::FETCH_ASSOC: возвращает массив, индексированный именами столбцов результирующего набора
 *      PDO::FETCH_BOTH (по умолчанию): возвращает массив, индексированный именами столбцов результирующего набора, а также их номерами (начиная с 0)
 *      PDO::FETCH_BOUND: возвращает TRUE и присваивает значения столбцов результирующего набора переменным PHP, которые были привязаны к этим столбцам методом PDOStatement::bindColumn()
 *      PDO::FETCH_CLASS: создает и возвращает объект запрошенного класса, присваивая значения столбцов результирующего набора именованным свойствам класса. 
 *          Если fetch_style включает в себя атрибут PDO::FETCH_CLASSTYPE (например, PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE), то имя класса, от которого нужно создать объект, будет взято из первого столбца.
 *      PDO::FETCH_INTO: обновляет существующий объект запрошенного класса, присваивая значения столбцов результирующего набора именованным свойствам объекта
 *      PDO::FETCH_LAZY: комбинирует PDO::FETCH_BOTH и PDO::FETCH_OBJ, создавая новый объект со свойствами, соответствующими именам столбцов результирующего набора
 *      PDO::FETCH_NUM: возвращает массив, индексированный номерами столбцов (начиная с 0)
 *      PDO::FETCH_OBJ: создает анонимный объект со свойствами, соответствующими именам столбцов результирующего набора
 * $cursor_orientation - Для объектов PDOStatement представляющих прокручиваемый курсор, этот параметр определяет, какая строка будет возвращаться в вызывающий метод. 
 *      Значением параметра должна быть одна из констант PDO::FETCH_ORI_*, по умолчанию PDO::FETCH_ORI_NEXT. Чтобы запросить прокручиваемый курсор для запроса PDOStatement, необходимо задать атрибут PDO::ATTR_CURSOR со значением PDO::CURSOR_SCROLL во время подготовки запроса методом PDO::prepare().
 * $offset - Для объектов PDOStatement, представляющих прокручиваемый курсор, параметр cursor_orientation которых принимает значение PDO::FETCH_ORI_ABS, 
 *      эта величина означает абсолютный номер строки, которую необходимо извлечь из результирующего набора.
 *      Для объектов PDOStatement, представляющих прокручиваемый курсор, параметр cursor_orientation которых принимает значение PDO::FETCH_ORI_REL, 
 *      эта величина указывает, какая строка относительно текущего положения курсора будет извлечена функцией PDOStatement::fetch().
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
		('Иванов', '12aff3'),
		('Петров', '123sf21'),
		('Сидоров', 'sfssf'),
		('Степанов', 'wrfsf'),
		('Никитин', 'sfsfss'),
        ('Михайлов', 'bfdbx'),
        ('Васильев', 'xfbxb')
");

$sth = $db->prepare("SELECT login, password FROM user");
$sth->execute();
echo'<pre>';

print("PDO::FETCH_ASSOC: ");
print("Возвращаем следующую строку в виде массива, индексированного именами столбцов\n");
$result = $sth->fetch(PDO::FETCH_ASSOC);
print_r($result);
echo '<hr>';

print("PDO::FETCH_NUM: ");
print("Возвращаем следующую строку в виде числового массива\n");
$result = $sth->fetch(PDO::FETCH_NUM);
print_r($result);
echo '<hr>';

print("PDO::FETCH_BOTH: ");
print("Возвращаем следующую строку в виде массива, индексированного как именами столбцов, так и их номерами\n");
$result = $sth->fetch(PDO::FETCH_BOTH); // или просто fetch()
print_r($result);
echo '<hr>';

print("PDO::FETCH_OBJ: ");
print("Возвращаем следующую строку в виде объекта stdClass со свойствами, соответствующими столбцам\n");
$result = $sth->fetch(PDO::FETCH_OBJ);
print_r($result);
print '$result->login: '.$result->login.'<br>';
echo '<hr>';

print("PDO::FETCH_LAZY: ");
print("Возвращаем следующую строку в виде объекта PDORow со свойствами, соответствующими столбцам\n");
$result = $sth->fetch(PDO::FETCH_LAZY);
print_r($result);
print '$result[0]: '.$result[0].'<br>';
print '$result[login]: '.$result['login'].'<br>';
print '$result->login: '.$result->login.'<br>';
echo '<hr>';

print("PDO::FETCH_CLASS|PDO::FETCH_CLASSTYPE: ");
print("Возвращаем строку в виде объекта, имя класса которого берётся из первого поля, если такой класс не определён, то возвращается объект stdClass\n");
$result = $sth->fetch(PDO::FETCH_CLASS|PDO::FETCH_CLASSTYPE);
print_r($result);
echo '<hr>';

print("PDO::FETCH_BOUND: Передаём значения полей строки в связанные переменные:<br>");
$sth->execute();
// Связывание по имени столбца
$sth->bindColumn('login', $name);
$sth->bindColumn('password', $passw);
$sth->fetch( PDO::FETCH_BOUND );
print 'login/$name: '.$name. ' password/$passw: '.$passw;
echo '<hr>';



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
$user = $sth->fetch( PDO::FETCH_CLASS );
// можно без указания параметра, тогда он автоматически будет браться из setFetchMode()
// $user= $sth->fetch();
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
$user= $sth->fetch( PDO::FETCH_INTO );
print_r ($user);
echo '<hr>';

/*
// В MySQL Выборка строк средствами прокручиваемого курсора идёт только в одной последовательности, поэтому применение курсоров под вопросом???
// Выборка строк средствами прокручиваемого курсора в прямой последовательности:
$sth = $db->prepare("SELECT login, password FROM user", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$sth->execute();
while ( $result = $sth->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_NEXT)) {
    print $result[0].'<br>';
}

// Выборка строк средствами прокручиваемого курсора в обратной последовательности:
$sth = $db->prepare("SELECT login, password FROM user", array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
$sth->execute();
$row = $sth->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_LAST);
do {
  print $row[0].'<br>';
} while ($row = $sth->fetch(PDO::FETCH_NUM, PDO::FETCH_ORI_PRIOR));
*/