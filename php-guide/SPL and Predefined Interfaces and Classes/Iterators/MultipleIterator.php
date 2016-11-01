<?php
/*
 MultipleIterator implements Iterator , Traversable 
{
	const integer MIT_NEED_ANY = 0 ;	- Не требовать, чтобы все подытераторы были действительными в итерации. 
	const integer MIT_NEED_ALL = 1 ;	- Требовать, чтобы все подытераторы были действительными в итерации. 	
	const integer MIT_KEYS_NUMERIC = 0 ;- Ключи создаются из позиции подытератора. 
	const integer MIT_KEYS_ASSOC = 2 ;	- Ключи создаются из ассоциативной информации подытератора. 

	__construct ([ int $flags = MultipleIterator::MIT_NEED_ALL|MultipleIterator::MIT_KEYS_NUMERIC ] ) - флаги-конмтанты итератора	
	void attachIterator ( Iterator $iterator [, string $infos ] ) - Присоединяет итератор $iterator.
		$infos - информация о ключах для итератора, которая должна быть представлена целым (integer), строкой (string), или NULL.
	void detachIterator ( Iterator $iterator )	- Отсоединяет итератор.
	void containsIterator ( Iterator $iterator )	- Проверяет, присоединен итератор, или нет. 
	void countIterators ( void )					- Получает число присоединенных итераторов
	int getFlags ( void )						- Получает информацию о флагах. 
	void setFlags ( int $flags )					- Флаги для установки, согласно предопределенным константам итератора
			
перегрузка методов интерфейса итератора Iterator (Traversable) - в foreach идёт перебор этих методов по кругу (итерация)
	void rewind ( void )					— Перемещает указатель в начало массива
	void next ( void )					— Перемещает указатель на следующую запись
	bool valid ( void )					— Проверяет, содержит ли массив еще записи
	mixed key ( void )					— Возвращает ключ текущего элемента массива
	mixed current ( void )				— Возвращает текущий элемент в массиве			
}
 
Класс MultipleIterator - итератор, который последовательно перебирает все подключенные одномерные итераторы (напр ArrayIterator), 
совершая параллельную склейку итераторов.
В результате склеек итераторов, получаем итератор массивов, длина которых равна числу итераторов, а значения
соответствуют позиции в итераторах array(iterator1[0],iterator2[0]), array(iterator1[1],iterator2[1]) и т.д.
В отличие от AppendIterator, который к концу одного итератора приклеивал другой, здесь после элемента одного итератора 
добавляется элемент другого итератора (не последовательная склейка, а параллельная). Конфликт длин итераторов 
разрешается установкой флагов в конструкторе (или в setFlags()).
*/
/*
$reflection = new ReflectionClass('MultipleIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
$people = new ArrayIterator(array('John', 'Jane', 'Jack', 'Judy'));
$roles  = new ArrayIterator(array('Developer', 'Scrum Master', 'Project Owner'));

// $flags = MultipleIterator::MIT_NEED_ALL|MultipleIterator::MIT_KEYS_NUMERIC; // число итераций = длине наименьшего итератора, ключи - позиции массивов
// $flags = MultipleIterator::MIT_NEED_ANY|MultipleIterator::MIT_KEYS_NUMERIC;   // число итераций = длине наибольшего итератора, ключи - значения $infos в attachIterator($iterator [,string $infos]) для соответствующего итератора
 $flags = MultipleIterator::MIT_NEED_ALL|MultipleIterator::MIT_KEYS_ASSOC;
// $flags = MultipleIterator::MIT_NEED_ANY|MultipleIterator::MIT_KEYS_NUMERIC;

$team = new MultipleIterator($flags);
$team->attachIterator($people, 'person');
$team->attachIterator($roles, 'role');

foreach ($team as $member) {
	echo'<pre>';
    print_r($member);
}

echo 'Число итераторов: '.$team->countIterators() . "<br>"; 

?>