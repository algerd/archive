<?php
header("Content-Type: text/html; charset=UTF-8");
/*
 * PDOStatement PDO::query ( string $statement )
 * PDOStatement PDO::query ( string $statement , int $PDO::FETCH_COLUMN , int $colno )
 * PDOStatement PDO::query ( string $statement , int $PDO::FETCH_CLASS , string $classname , array $ctorargs )
 * PDOStatement PDO::query ( string $statement , int $PDO::FETCH_INTO , object $object )
 * Выполняет SQL запрос без подготовки и возвращает результирующий набор (если есть) в виде объекта PDOStatement.
 * Если запрос будет запускаться многократно, для улучшения производительности приложения имеет смысл этот запрос один раз подготовить методом PDO::prepare(), 
 * а затем запускать на выполнение методом PDOStatement::execute() столько раз, сколько потребуется.
 * Если после выполнения предыдущего запроса вы не выбрали все данные из результирующего набора, следующий вызов PDO::query() может потерпеть неудачу. 
 * В таких случаях следует вызываеть метод PDOStatement::closeCursor(), который освободит ресурсы базы данных занятые предыдущим объектом PDOStatement. 
 * После этого можно безопасно вызывать PDO::query().
 * 
 * PDO::query() применяется для выборок с помощью SELECT. 
 * Если при выполнении запроса не важно возвращаемое значение, то лучше использовать PDO::exec(), который врзвращает число строк, подвергнутых изменениям в результате запроса.
 * Параметр $statement для безопасности должно быть экранировано с помощью метода PDO::quote()
 */

// Дополнительная проверка подключения php-расширения нужного драйвера
if ( !in_array('mysql', PDO::getAvailableDrivers()) ) 
	throw new Exception ('Требуется подключение расширения php_pdo_mysql.dll');

$db = new PDO('mysql:host=localhost;', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("DROP DATABASE IF EXISTS test");
$db->exec("CREATE DATABASE test CHARACTER SET utf8 COLLATE utf8_general_ci");

$db = new PDO('mysql:host=localhost;dbname=test', 'root');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$db->exec("
    CREATE TABLE users (
        id           INT         NOT NULL AUTO_INCREMENT,
        login        VARCHAR(50) NOT NULL DEFAULT '',
        password     VARCHAR(50) NOT NULL DEFAULT '',
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

/*
 * PDO::query() возвращает объект PDOStatement и для его детальной обработки надо воспользоваться методами PDOStatement:
 */
$result->fetch(PDO::FETCH_ASSOC);
print_r($result);