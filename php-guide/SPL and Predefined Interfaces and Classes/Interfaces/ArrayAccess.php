<?php
/*
interface ArrayAccess 
{
    abstract public boolean offsetExists ( mixed $offset )             - вызывается при проверке существования элемента объекта-массива с индексом $offset
    abstract public mixed   offsetGet    ( mixed $offset )             - вызывается при обращении за элементом объекта-массива с индексом $offset
    abstract public void    offsetSet ( mixed $offset , mixed $value ) - вызывается при записи элемента в объект-массив $offset => $value
    abstract public void    offsetUnset  ( mixed $offset )             - вызывается при удалении элемента с индексом $offset из объекта-массива
}
 
Интерфейс ArrayAccess - обеспечивает доступ объекту как к массиву.
$offset - это ключ елемента объекта массива, $value - значение елемента объекта массива
*/
/*
$reflection = new ReflectionClass('ArrayAccess');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
class book implements ArrayAccess
{
	public $title;
	public $author;
	public $isbn;
    // проверка наличия элемента
	public function offsetExists($item){
		return isset($this->$item);
	}
    // создание нового элемента
	public function offsetSet($item, $value){
		$this->$item = $value;
	}
    // возвращение элементе
	public function offsetGet($item){
		return $this->$item;
	}
    // удаление элемента
	public function offsetUnset($item){
		unset($this->$item);
	}
}
////////////////////////////////////////////////////////////////////////////////////////////////////

$book = new book;

$book['title'] = 'PHP 4';   // offsetSet ( mixed $offset , mixed $value )
if(isset($book['title'])){  // offsetExists ( mixed $offset )
    echo $book['title'];    // offsetGet ( mixed $offset )
}
unset($book['title']);      // offsetUnset  ( mixed $offset )

$book['title']= 'PHP 5';
$book['author'] = 'John Smith';
$book['isbn'] = 1590598199;
print_r($book);
