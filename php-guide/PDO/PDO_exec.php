<?php
header("Content-Type: text/html; charset=UTF-8");
/*
 * int PDO::exec ( string $statement )
 * Запускает SQL запрос на выполнение и возвращает количество строк, которые были модифицированы или удалены в ходе его выполнения. 
 * Если таких строк нет, PDO::exec() вернет 0.
 * PDO::exec() не возвращает результат выборки оператором SELECT. Для этого надо использовать PDO::query()
 * Если вам нужно выбрать данные этим оператором единожды в ходе выполнения программы, пользуйтесь методом PDO::query(). 
 * Если требуется запускать один и тот же запрос на выборку множество раз, лучше создать подготовленный запрос PDOStatement методом PDO::prepare(), 
 * а затем запускать его методом PDOStatement::execute() столько раз, сколько потребуется. 
 * Параметр $statement для безопасности должно быть экранировано с помощью метода PDO::quote()
 */
/*
 * string PDO::lastInsertId ([ string $name = NULL ] )
 * Возвращает ID последней вставленной строки либо последнее значение, которое выдал объект последовательности.
 */

try {
    // Проверка подключения php-расширения нужного драйвера
    if ( !in_array('mysql', PDO::getAvailableDrivers()) ) 
        throw new Exception ('Требуется подключение расширения php_pdo_mysql.dll');
    
    // Создадим БД 'test'
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

	$result = $db->exec("
		INSERT INTO users(login, password) VALUES 
			('Иванов', '12aff3'),
			('Петров', '123sf21'),
			('Сидоров', 'sfssf'),
			('Степанов', 'wrfsf'),
			('Никитин', 'sfsfss')
	");
	echo 'Число строк, задействованых в ходе выполнения запроса: '.$result."<br>\n"; 
	
	$db->exec( "INSERT INTO users(login, password) VALUES('Смит', 'xvxdvxv')" );
	echo 'Id последнего инсерта: '.$db->lastInsertId()."<br>\n";
	
}
catch (PDOException $e) {
    echo $e->getMessage();
}
catch (Exception $e) {
    echo $e->getMessage();   
}
