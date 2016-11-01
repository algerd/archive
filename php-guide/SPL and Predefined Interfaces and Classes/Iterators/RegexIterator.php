<?php
/*
RegexIterator extends FilterIterator 
{
    public $replacement;			- Свойство замены записи
  preg_flags
	const integer MATCH = 0 ;		- Только выполнение сравнения (фильтра) для текущей записи (смотрите preg_match()). 
	const integer GET_MATCH = 1 ;	- Вовзарщает первое совпадение для текущей записи (смотрите preg_match()). 
	const integer ALL_MATCHES = 2 ; - Возвращает все совпадения для текущий записи (смотрите preg_match_all())
	const integer SPLIT = 3 ;		- Возвращает разделенные значения для текущей записи (смотрите preg_split()). 
	const integer REPLACE = 4 ;		- Замена текущей записи (смотртрите preg_replace(); Полностью пока не реализовано) 
  flags
	const integer USE_KEY = 1 ;		- Специальный флаг: Сравнивать ключ записи вместо значения записи. 
			
	__construct (Iterator $iterator , string $regex [, int $mode = self::MATCH [, int $flags = 0 [, int $preg_flags = 0 ]]])
	string getRegex ( void )		- Возвращает строку текущего регулярного выражения 
	void setFlags ( int $flags )	- Установка флагов flags		
	int getFlags ( void )			- Получение флагов настройки flags
	void setPregFlags ( int $preg_flags ) - Установка флагов регулярного выражения preg_flags
	int getPregFlags ( void )		- Возвращает флаги регулярного выражения preg_flags				
	void setMode ( int $mode )		- Установка режима работы preg_flags		
	int getMode ( void )			- Возвращает режим работы preg_flags

  Переопрелелённый метод от FilterIterator 			
	abstract bool accept ( void )	- Проверяет, является ли текущий элемент итератора допустимым регулярным выражением
			
  Наследуемые методы от FilterIterator 	
	Iterator getInnerIterator ( void ) - Возвращает внутренний итератор	
	void rewind ( void )
	bool valid ( void )		
	mixed current ( void )	
	mixed key ( void )
	void next ( void )
} 
 
Класс RegexIterator - это реализация(расширение) абстрактного класса FilterIterator.
Этот итератор может быть использован для фильтрации другого одномерного итератора на основе регулярных выражений.

	$array = array();
	$iterator = new ArrayIterator($array);
	$iterator = new RegexIterator($iterator, $regex); 
	foreach (iterator as $key=>$value){ };
	unset ($iterator, $array);
// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach ( new RegexIterator( new ArrayIterator(array()), $regex) as $key=>$value );		
*/
/*
$reflection = new ReflectionClass('RegexIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/

$arrayIterator = new ArrayIterator(array('test1', 'test2', 'test3', 'test2'));

//Замена текущей записи (смотртрите preg_replace()
$regexIterator = new RegexIterator($arrayIterator, '/^(test)(\d+)/', RegexIterator::REPLACE);
$regexIterator->replacement = '$2:$1';  // замена записи
foreach ($regexIterator as $key=>$value) {
	echo "k=$key v=$value <br>";
}

echo '<hr>';

// Только выполнение сравнения (фильтрация) для текущей записи (смотрите preg_match()).
$regexIterator = new RegexIterator($arrayIterator, '/^(test2)/', RegexIterator::MATCH);
foreach ($regexIterator as $key=>$value) {
	echo "k=$key v=$value <br>";
}
