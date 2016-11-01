<?php
header("Content-Type: text/html; charset=UTF-8");
/*
PDOException extends RuntimeException 
{	
	// Свойства
	public array $errorInfo ;
	protected string $message ;
	protected string $code ;
	
	//Наследуемые методы
	final public string Exception::getMessage( void )
	final public Exception Exception::getPrevious( void )
	final public mixed Exception::getCode( void )
	final public string Exception::getFile( void )
	final public int Exception::getLine( void )
	final public array Exception::getTrace( void )
	final public string Exception::getTraceAsString( void )
	public string Exception::__toString( void )
	final private void Exception::__clone( void )
}

Представляет ошибку, вызываемую PDO. 
errorInfo - Соответствует PDO::errorInfo() или PDOStatement::errorInfo()
message - Текстовое сообщение об ошибке. Используйте Exception::getMessage(), чтобы получить его содержимое.
code - SQLSTATE код ошибки. Чтобы его получить, используйте Exception::getCode().
*/
/*
$reflection = new ReflectionClass('PDOException');
var_dump($reflection->getConstants());
var_dump($reflection->getProperties());
var_dump($reflection->getMethods());
*/
try {
    // Создадим БД 'test'
    $db = new PDO('mysql:host=localhost;', 'root');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec("DROP DATABASE IF EXISTS test");
    $db->exec("CREATE DATABASE test CHARACTER SET utf8 COLLATE utf8_general_ci");
    
    // делаем ошибку
    $db->query('DELECT name FROM people');   
}
catch (PDOException $e) {
    echo 'Cообшение исключения: '.$e->getMessage().'<br>';
    echo 'Код исключения: '.$e->getCode().'<br>';
    echo 'Файл, где выброшено исключение: '.$e->getFile().'<br>';
    echo 'Строка, выбросившая исключениe: '.$e->getLine().'<br>';
	echo 'Стек исключения в виде строки: '.$e->getTraceAsString().'<br>';
	echo 'Объект исключения в видестроки (__toString): '.$e.'<br>';
    echo '<pre>';
    print_r($e->errorInfo);
}
catch (Exception $e) {
    echo $e->getMessage().'<br>';   
}