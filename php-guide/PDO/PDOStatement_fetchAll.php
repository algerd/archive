<?php
header("Content-Type: text/html; charset=UTF-8");
/* 
 * Возвращает массив, содержащий все строки результирующего набора
 * 
 * array PDOStatement::fetchAll([ int $fetch_style [, mixed $fetch_argument [, array $ctor_args = array() ]]] )
 *  $fetch_style - Определяет содержимое возвращаемого массива (см в PDOStatement::fetch()). По умолчанию параметр принимает значение PDO::ATTR_DEFAULT_FETCH_MODE (которое в свою очередь имеет умолчание PDO::FETCH_BOTH)
 *      PDO::FETCH_COLUMN - Чтобы извлечь значения только одного столбца, передайте в качестве значения этого параметра константу PDO::FETCH_COLUMN. С помощью параметра column-index(в $fetch_argument) можно задать столбец, из которого требуется извлечь данные.
 *      PDO::FETCH_COLUMN|PDO::FETCH_UNIQUE - Если требуется извлечь только уникальные строки одного столбца, нужно передать побитовое ИЛИ констант PDO::FETCH_COLUMN и PDO::FETCH_UNIQUE.
 *      PDO::FETCH_COLUMN|PDO::FETCH_GROUP - Чтобы получить ассоциативный массив строк сгруппированный по значениям определенного столбца, нужно передать побитовое ИЛИ констант PDO::FETCH_COLUMN и PDO::FETCH_GROUP. (Группировка идёт на php-сервере)
 *      PDO::FETCH_LAZY, PDO::FETCH_INTO не могут быть применены аргументом $fetch_style
 * $fetch_argument - Его значение зависит от параметра fetch_style:
 *      PDO::FETCH_COLUMN: Будет возвращен указанный столбец. Индексация столбцов начинается с 0.
 *      PDO::FETCH_CLASS: Будет создан и возвращен новый объект указанного класса. Свойствам объекта будут присвоены значения столбцов, имена которых совпадут с именами свойств.
 *      PDO::FETCH_FUNC: Будут возвращены результаты вызовов указанной функции. Данные каждой строки результирующего набора будут передаваться в эту функцию.
 *  $ctor_args - Аргументы конструктора класса. Для случаев, когда параметру fetch_style присвоено значение PDO::FETCH_CLASS
 * 
 * Возвращает массив, содержащий все оставшиеся строки результирующего набора. 
 * Массив представляет каждую строку либо в виде массива значений одного столбца, либо в виде объекта, имена свойств которого совпадают с именами столбцов.
 * Использование этого метода для извлечения строк больших результирующих наборов может пагубно сказаться на производительности системы и сетевых ресурсов. 
 * Вместо извлечения всех данных и их обработки в PHP рекомендуется использовать встроенные средства СУБД. Например, использование выражений WHETE и ORDER BY языка SQL может уменьшить размеры результирующего набора. 
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
echo'<pre>';

// Все константы из примера PDOStatement_fetch.php аналогично применяются и для fetchAll, напр.
print_r($sth->fetchAll());
$sth->execute();
print_r($sth->fetchAll(PDO::FETCH_NUM));
$sth->execute();
print_r($sth->fetchAll(PDO::FETCH_ASSOC));
$sth->execute();
print_r($sth->fetchAll(PDO::FETCH_OBJ));
$sth->execute();
print_r($sth->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_CLASSTYPE));


class User{
    public $login;
    function __construct($test = null) {
        $this->login = 'guest';
        if ( $test !== null ) $this->test = $test;
    }
}
$sth->execute();
print_r($sth->fetchAll(PDO::FETCH_CLASS, 'User'));
$sth->execute();
print_r($sth->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User'));
$sth->execute();
print_r($sth->fetchAll(PDO::FETCH_CLASS, 'User', ['testUser']));
echo '<hr>';

/*
 *  Специфичные для fetchAll() аргументы:
 */
$sth->execute();
print_r($sth->fetchAll(PDO::FETCH_COLUMN, 0));
$sth->execute();
print_r($sth->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_UNIQUE, 1));
$sth->execute();
print_r($sth->fetchAll(PDO::FETCH_COLUMN|PDO::FETCH_GROUP, 1));

function test($login, $password) {
    return $login.'/'.$password;
}
$sth->execute();
print_r($sth->fetchAll(PDO::FETCH_FUNC, 'test'));

$test = function($login, $password) {
    return $login.'/'.$password;
};
$sth->execute();
print_r($sth->fetchAll(PDO::FETCH_FUNC, $test));