<?php
header("Content-Type: text/html; charset=UTF-8");
/* 
 * bool PDOStatement::bindParam( mixed $parameter , mixed &$variable [, int $data_type = PDO::PARAM_STR [, int $length [, mixed $driver_options ]]] )
 *  $parameter - Идентификатор параметра. Для подготавливаемых запросов с именованными параметрами это будет имя в виде :name. 
 *      Если используются неименованные параметры (знаки вопроса ?) это будет позиция псевдопеременной в запросе (начиная с 1).
 *  $variable - PHP переменной, которую требуется привязать к параметру SQL запроса.
 *  $data_type - Явно заданный тип данных параметра. Тип задается одной из констант PDO::PARAM_*. 
 *      Если параметр используется в том числе для вывода информации из хранимой процедуры, к значению аргумента data_type необходимо добавить PDO::PARAM_INPUT_OUTPUT, используя оператор побитовое ИЛИ.
 *  $length - Размер типа данных. Чтобы указать, что параметр используется для вывода данных из хранимой процедуры, необходимо явно задать его размер.
 *  $driver_options - массив PDOStatement-аттрибутов (для MySQL-драйвера не используется)
 * В отличие от передачи параметров напрямую в PDOStatement::execute(), PDOStatement::bindParam() позволяет детализировать передаваемые значения.
 * 
 * Связывает PHP переменную с именованным или неименованным параметром подготавливаемого SQL запроса. 
 * В отличие от PDOStatement::bindValue(), переменная привязывается по ссылке, и ее значение будет вычисляться во время вызова PDOStatement::execute().
 * В большинстве случаев в подготавливаемых запросах используются только входные параметры, то есть при построении запроса доступ к ним осуществляется только в режиме чтения. 
 * Тем не менее, некоторые драйверы позволяют запускать хранимые процедуры, которые, в свою очередь, могут возвращать данные посредством выходных параметров. 
 * Зачастую, такие параметры используются одновременно как входные и как выходные. 
 * MySQL-драйвер не возвращает значение во входную переменную в хранимой процедуре (PDO::PARAM_INPUT_OUTPUT не работает). 
 * Поэтому после вызова хранимой процедуры надо дополнительно делать SELECT @переменная для её возврата(см ниже).
 */

/*
 * bool PDOStatement::bindValue( mixed $parameter , mixed $value [, int $data_type = PDO::PARAM_STR ] )
 * Задает значение именованной или неименованной псевдопеременной в подготовленном SQL запросе.
 * Т.к. MySQL-драйвер не поддерживает возврат значения переменной из хранимой процедуры, то фактически bindValue() отличается от
 * bindParam() только меньшей детализацией передаваемой переменной ($length)
 * 
 */

/*
 * bool PDOStatement::bindColumn( mixed $column , mixed &$param [, int $type [, int $maxlen [, mixed $driverdata ]]] )
 * Привязывает переменную к заданному столбцу в результирующем наборе запроса. 
 * Каждый вызов PDOStatement::fetch() или PDOStatement::fetchAll() будет обновлять все переменные, задавать им значения столбцов, с которыми они связаны.
 * Замечание: в связи с тем, что информация о столбцах результирующего набора запроса не всегда доступна объекту PDO, пока запрос не будет запущен, 
 * приложениям следует вызывать этот метод после выхова PDOStatement::execute().
 * $column - Номер столбца (начиная с 1) или его имя в результирующем наборе запроса. Используя имя столбца, будьте внимательны, имя должно быть в том же регистре, в каком оно выдано драйвером.
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
		('Иванов', 20),
		('Степанов', 27),
		('Иванов', 35)
");
echo '<pre>';


///////// Пример #1 Выполнение подготовленного запроса с именованными псевдопеременными в bindParam()
$ageMax = 16;
$name = 'Иванов';
$sth = $db->prepare('
    SELECT name, age FROM users
        WHERE age < :ageMax AND name = :name
');
$sth->bindParam(':ageMax', $ageMax, PDO::PARAM_INT);
$sth->bindParam(':name', $name, PDO::PARAM_STR, 50);
$sth->execute();
// Это то же самое что и: (но без детализации параметров)
// $sth->execute([':ageMax'=>$ageMax, ':name'=>$name]);
print_r($sth->fetchAll(PDO::FETCH_OBJ));


///////// Пример #2 Выполнение подготовленного запроса с неименованными псевдопеременными (?) в bindParam()
$sth = $db->prepare('
    SELECT name, age FROM users
        WHERE age < ? AND name = ?
');
$sth->bindParam(1, $ageMax, PDO::PARAM_INT);
$sth->bindParam(2, $name, PDO::PARAM_STR, 50);
$sth->execute();
// Это то же самое что и: (но без детализации параметров)
// $sth->execute([$ageMax, $name]);
print_r($sth->fetchAll(PDO::FETCH_ASSOC));

// Пример #3 Вызов хранимой процедуры MySQL c передачей и возвратом переменных
/*
$colour = 'red';
$sth = $db->prepare('CALL getFruit(:color, @fruit)');
$sth->bindParam(':color', $colour, PDO::PARAM_STR, 50);
$sth->execute();
// возвращаем значение SQL переменной @fruit из хранимой процедуры
$value = $db->query('SELECT @fruit');
$value->fetchColumn();
*/

///////// Пример #4 Выполнение подготовленного запроса с именованными псевдопеременными в bindValue()
$ageMax = 16;
$name = 'Иванов';
$sth = $db->prepare('
    SELECT name, age FROM users
        WHERE age < :ageMax AND name = :name
');
$sth->bindValue(':ageMax', $ageMax, PDO::PARAM_INT);
$sth->bindValue(':name', $name, PDO::PARAM_STR);
$sth->execute();
// Это то же самое что и: (но без детализации параметров)
// $sth->execute([':ageMax'=>$ageMax, ':name'=>$name]);
print_r($sth->fetchAll(PDO::FETCH_OBJ));


///////// Пример #5 Выполнение подготовленного запроса с неименованными псевдопеременными (?) в bindValue()
$sth = $db->prepare('
    SELECT name, age FROM users
        WHERE age < ? AND name = ?
');
$sth->bindValue(1, $ageMax, PDO::PARAM_INT);
$sth->bindValue(2, $name, PDO::PARAM_STR);
$sth->execute();
// Это то же самое что и: (но без детализации параметров)
// $sth->execute([$ageMax, $name]);
print_r($sth->fetchAll(PDO::FETCH_ASSOC));


///////// Пример #6 Выполнение подготовленного запроса в bindColumn()
$sth = $db->prepare('SELECT name, age FROM users');
$sth->execute();
// связывание по имени столбца
$sth->bindColumn('name', $name, PDO::PARAM_STR, 50);
// связывание по номеру столбца
$sth->bindColumn(2, $age, PDO::PARAM_INT);
while ( $sth->fetch(PDO::FETCH_BOUND) ) {
    echo "Имя: $name, возраст: $age<br>";
}

///////// Пример #7 Выполнение мультизапроса
$str1 = 'INSERT INTO users VALUES (6, :name1, :age1)';
$str2 = 'INSERT INTO users VALUES (9, :name2, :age2)';
$sth = $db->prepare($str1.';'.$str2);
$sth->bindValue(':age1', 10, PDO::PARAM_INT);
$sth->bindValue(':name1', 'John', PDO::PARAM_STR);
$sth->bindValue(':age2', 15, PDO::PARAM_INT);
$sth->bindValue(':name2', 'Mike', PDO::PARAM_STR);
$sth->execute();
print_r($sth);

