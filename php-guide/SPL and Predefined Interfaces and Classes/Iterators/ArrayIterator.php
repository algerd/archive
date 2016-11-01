<?php
/*
ArrayIterator implements Iterator, Traversable, ArrayAccess, SeekableIterator, Serializable, Countable
{
  flags:
	const integer STD_PROP_LIST = 1 ;	- Свойста объекта получают стандартное поведение, при доступе к объекту как к списку (var_dump, foreach и т.д.).
	const integer ARRAY_AS_PROPS = 2 ;  - Записи могут быть доступны как свойства (для чтения и записи).
 
	__construct ([ mixed $array = array() [, int $flags = 0 ]] ) -	$flags = ArrayIterator::STD_PROP_LIST или $flags = ArrayIterator::ARRAY_AS_PROPS
	void append ( mixed $value )		— Добавить элемент как последний элемент массива
	array getArrayCopy ( void )			— Возвращает копию массива
	void getFlags ( void )				— Возвращает текущие флаги
	void setFlags ( string $flags )		— Устанавливает флаги (0- Свойства объекта имеют свои нормальные функции при доступе в виде списка (var_dump, foreach, etc.), $flags = ArrayIterator::STD_PROP_LIST или $flags = ArrayIterator::ARRAY_AS_PROPS
											
   - сортировка
	void asort ( void )					— Сортирует массив по значениям
	void ksort ( void )					— Сортирует записи массив по ключам
	void uasort ( string $cmp_function )	— Сортирует записи в массиве по значениям, используя функцию сортировки, определенную пользователем. 
	void uksort ( string $cmp_function )	— Сортирует записи в массиве по ключам, используя функцию сортировки, определенную пользователем
	void natcasesort ( void )			— Сортирует массив "натурально", с учетом регистра
	void natsort ( void )				— Сортирует массив "натурально"	(сначало верхний регистр, потом нижний)
  
  перегрузка методов интерфейса итератора Iterator (Traversable) - в foreach идёт перебор этих методов по кругу (итерация)
	void rewind ( void )				— Перемещает указатель в начало массива
	void next ( void )					— Перемещает указатель на следующую запись
	bool valid ( void )					— Проверяет, содержит ли массив еще записи
	mixed key ( void )					— Возвращает ключ текущего элемента массива
	mixed current ( void )				— Возвращает текущий элемент в массиве
 
  перегрузка методов интерфейса доступа к объекту как к массиву ArrayAccess
	void offsetExists ( string $index )	— Проверяет существует ли запись с индексом $index
	mixed offsetGet ( string $index )	— Получает значение записи	с индексом $index
	void offsetSet ( string $index , string $value )	— Устанавливает значение записи $index => $value
	void offsetUnset ( string $index )	— Удаляет запись с индексом $index

  перегрузка методов интерфейса контейнера-итератора с определённой позиции SeekableIterator
	void seek ( int $position )			— Перещает указатель на выбранную позицию
 
  перегрузка методов интерфейса для индивидуальной сериализации Serializable
	string serialize ( void )			— Сериализует массив
	string unserialize ( string $serialized )	— Десериализация	
 
  перегрузка методов интерфейса подсчёта элементов объекта Countable
	int count ( void )					— Посчитать количество элементов
}

Класс ArrayIterator создаёт контейнер-объект (итератор) для передаваемого ему одномерного! массива (или объекта класса ArrayObject), 
через методы которого предоставляется хороший инструментарий работы с переданным массивом в циклах foreach (и вне циклов).
Он реализует методы ряда других интерфейсов, делая его незаменимым для работы с массивами в циклах и вне.

Класс ArrayIterator позволяет сбрасывать и модифицировать значения и ключи в процессе итерации по массивам и объектам.
Когда вы хотите перебрать некоторый массив несколько раз, вы должны создать экземпляр ArrayObject и позволить ему создать экземпляр ArrayIterator, 
ссылающийся на него при использовании foreach, или при вызове метода getIterator() вручную (см. ArrayObject.php).
 
Чтобы подчеркнуть логику кода, лучше итерировать ArrayIterator через итерирующий итератор IteratorIterator, а не напрямую:
	$array = array();
	$iterator = new ArrayIterator($array);
	$iterator = new IteratorIterator($iterator);
	foreach (iterator as $key=>$value){ };
	unset ($iterator, $array);
	// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach ( new IteratorIterator( new ArrayIterator(array()) ) as $key=>$value ); 
Можно и напрямую итерировать ArrayIterator без IteratorIterator, но это вносит путаницу в код.
Это подобно типизации переменных, можно не указывать, но чтобы код был понятнее и жёстче - лучше указывать.
Только такой путь итерирования итератора работает для итерирования ArrayIterator итераторами-наследниками IteratorIterator:
    foreach ( new LimitIterator( new ArrayIterator(array()), $pos, $count ) as $key=>$value ); 			
*/
/*
$reflection = new ReflectionClass('ArrayIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
////////////////////////////////////////////////////////////////////////////////////////////////////
$array = array('Вася', 'Петя', 'Иван', 'Маша', 'Джон', 'Даша', 'Наташа', 'Света');

/*
 *  Прямая и "правильная" итерации ArrayIterator
 */ 
echo 'Прямая итерация ArrayIterator без IteratorIterator: <br>';
$object = new ArrayIterator($array);
foreach ( $object as $key=>$value ) echo "$key => $value <br>";

echo '"Правильная" итерация ArrayIterator с использованием IteratorIterator: <br>';
foreach ( new IteratorIterator( new ArrayIterator($array) ) as $key=>$value ); 
	 echo "$key => $value <br>";

/*
 * Последовательный проход по перегруженным методам итератора Iterator (Traversable)
 */
try {
	$object = new ArrayIterator($array);
	// последовательная итерация rewind(),next()->valid()->key()->current()->next()
	$object->rewind(); 
	while($object->valid()){
		echo $object->key().' -&gt; '.$object->current().'<br />';
		$object->next();
	}
}catch (Exception $e){
	echo $e->getMessage();
}
echo '<hr>';
/*
 * Итерация массива с помощью foreach - автоматический  проход по перегруженным методам итератора Iterator (Traversable)
 */
try {
	$object = new ArrayIterator($array);
	foreach($object as $key=>$value){
		echo $key.' => '.$value.'<br />';
	}
}catch (Exception $e){
	echo $e->getMessage();
}
echo '<hr>';
/*
 * Использование объекта как массив с помощью перегруженных методов итератора  ArrayAccess
 */
echo '<ul>';
try {
	$object = new ArrayIterator($array);
	if($object->offSetExists(2)){
		$object->offSetSet(2, 'Goanna');
	}
	foreach($object as $key=>$value){
		if($object->offSetGet($key) === 'Джон'){
			$object->offSetUnset($key);
		}
		echo '<li>'.$key.' - '.$value.'</li>'."\n";
	}
}
catch (Exception $e){
	echo $e->getMessage();
}
echo '</ul>';
echo '<hr>';
/*
 * Сортировки и другие собственные методы  ArrayIterator
 */
$fruits = array("d" => "Lemon", "a" => "orange", "b" => "banana", "c" => "apple");

try {
	$object = new ArrayIterator($fruits);
	$object->append('ananas');	// добавляем элемент	
	//$object->asort();			// сортировка по значениям
	//$object->ksort();			// сортировка по ключам
	//$object->natcasesort();	// сортировка по значениям с учётом регистра
	$object->natsort();			// сортировка по значениям без учёта регистра (сначало верхний регистр, потом нижний)
	// количество элементов
	echo 'Элементов: '.$object->count().'<br />';
	
	foreach($object as $key=>$value){
		echo $key.' => '.$value.'<br />';
	}
}catch (Exception $e){
	echo $e->getMessage();
}

?>