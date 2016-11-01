<?php
/*
	Класс Closure используется для создания анонимных функций. Анонимные функции являются объектами данного класса. 

 Closure 
{
	private __construct ( void ) - доступ на создание объектов от него закрыт
	public __invoke( mixed )	 - реализует доступ к объекту как к функции	
	public Closure bindTo (object $newthis [,mixed $newscope='static']) - Дублирует замыкание с указанием связанного объекта и области видимости класса
    public static Closure bind (Closure $closure, object $newthis [,mixed $newscop ='static']) - статический вариант метода bindTo
}
	Метод bindTo($object, $newscop) делает видимыми методы и свойства объекта $object внутри анонимной функции, причём обращаться к ним внутри анонимной функции можно через $this-> 
	Метод bindTo как бы говорит анонимной функции - залезь в этот объект и бери с него всё, что хочешь (в зависимости от второго параметра)
    $newscop - указывает какие свойства и методы будут видны:
		- ничего не указываем (по умолчанию 'static'), значит будут видны только все публичные свойства и методы
 		- указываем название класса (в виде строки: 'User') или пишем переменную связываемого объекта ($object) - будут видны все свойства и методы
		- 'static' - сохранение текущей области видимости
		- указание $newscop метод запоминает и второй раз уже не надо указывать ту же область видимости объекта
	
	Действие bindTo() с некоторыми различиями можно сравнить с передачей в анонимку объекта через её аргумент:
	function ($object){}, но нет возможности работать с приватными свойствами и методами объекта $object !!!
 
	Статический метод Closure::bind() работает аналогично bindTo():
 		- он связывает анонимку $closure (парам1) с объектом $newthis (парам2), доступ к свойствам и методам указывается в $newscop (парам3)
 
////////////////////////////////////////////////////////////////////////////////////////////////////
/*
 * Примеры использования метода связывания bindTo()
 */
// Создаём анонимные функции, которые не связаны ни с какими объектами.
$getName = function(){ return $this->name; };
$getPassProperty = function(){ return $this->_password; };
$getPassMethod = function(){ return $this->getPassword(); };

// Какой-то класс
class User
{
    public  $name;
	private $_password;	
	function __construct($n, $p) {
		$this->name = $n;
		$this->_password = $p;
	}
	private function getPassword(){
		return $this->_password;
	}	
}

// Объект класса User
$user1 = new User('Alex', 12345);
$user2 = new User('John', 67890);
// Связываем объект $user1 с анонимной функцией $getPassword (как бы делаем видимыми все публичные методы и свойства объекта $user1, причём обращаться к ним можно через $this-> )
$getName = $getName->bindTo($user1);	// теперь в $getName объект класса Closure(анонимная функция), который видит все публичные свойства и методы объекта $user1
echo $getName().'<br>'; //Alex

// Связываем объект $user1 с анонимной функцией $getPassProperty
$getPassProperty = $getPassProperty->bindTo($user1,'User');	// ($user1, $user1)	// вторым параметром указываем, что анонимная функция теперь будет видеть и private/protected свойства и методы класса 'User'
echo $getPassProperty().'<br>'; //12345						
$getPassProperty = $getPassProperty->bindTo($user2);		// уже ранее вторым параметром был указан private/protected доступ к классу User
echo $getPassProperty().'<br>'; //67890

// Связываем объект $user1 с анонимной функцией $getPassMethod
$getPassMethod = $getPassMethod->bindTo($user1, $user1);	// ($user1,'User') // вторым параметром указываем, что анонимная функция теперь будет видеть и private/protected свойства и методы класса 'User'
echo $getPassMethod().'<br>'; //12345
$getPassMethod = $getPassMethod->bindTo($user2, 'static');  // уже ранее вторым параметром был указан private/protected доступ к классу User
echo $getPassMethod().'<br>'; //67890

echo '<hr>';
////////////////////////////////////////////////////////////////////////////////////////////////////

class Album{
	public $name;
	public $object;
	public function __construct($n, $o) {
		$this->name = $n;
		$this->object = $o;
	}
	// метод возвращает объект анонимную функцию
	public function getClosure(){
		$str = '-это объект'.$this->object;
		// передаём обработанные данные $str объекта этого класса в анонимную функцию, которую впоследствии можем связать с другим объектом, причём другого класса!!!
		return function() use($str) {return $this->name.$str;};
	}
}

class SuperAlbum{
	public $name = 'SuperAlex';
}

// объекты класса Album
$obj1 = new Album('Alex', 'obj1');
$obj2 = new Album('John', '$obj2');

$anonim = $obj1->getClosure(); // в $anonim объект анонимная функция
echo $anonim().'<br>'; //Alex

// связываем объект $obj2 со встроенной в метод анонимной функцией. 
// Трюк в том, что объект $anonim был получен из объекта $obj1!!! 
$anonim = $anonim->bindTo($obj2);
echo $anonim().'<br>'; // John

// объект класса SuperAlbum
$super = new SuperAlbum;
// Супертрюк!!! связываем $anonim, полученный из объекта $obj1 класса Album, c объектом $super другого!!! класса SuperAlbum
$anonim = $anonim->bindTo($super);
echo $anonim().'<br>'; // SuperAlex

/*
 * Вывод: bindTo() позволяет инкапсулировать один объект в другой через связывание в анонимной функции.
 * В нашем случае объект $super класса SuperAlbum(его свойства и методы) был инкапсулирован в объект $obj1 (в анонимную функцию метода) класса Album.  
 */
echo '<hr>';

////////////////////////////////////////////////////////////////////////////////////////////////////
/*
 * Примеры использования статического метода связывания bind()
 */


class A {
    private static $sfoo = 1;
    private $ifoo = 2;
}

$cl1 = static function() { return A::$sfoo; };	// статическая анонимная функция
$cl2 = function() { return $this->ifoo; };		// простая анонимная функция

// связываем анонимную функцию $bcl1 с классом A, а не с объектом (null)
$bcl1 = Closure::bind($cl1, null, 'A');
// связываем анонимную функцию $bcl2 с объектом new A() c доступом ко всем свойствам и методам класса A
$bcl2 = Closure::bind($cl2, new A(), 'A');
echo $bcl1(), "\n";
echo $bcl2(), "\n";

?>