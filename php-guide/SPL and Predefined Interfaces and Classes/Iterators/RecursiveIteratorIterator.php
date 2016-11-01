<?php
/*
RecursiveIteratorIterator implements OuterIterator , Traversable , Iterator 
{
	const integer LEAVES_ONLY = 0 ;		- флаги для __construct
	const integer SELF_FIRST = 1 ;
	const integer CHILD_FIRST = 2 ;
	const integer CATCH_GET_CHILD = 16 ;
	
	 ( Traversable $iterator [, int $mode = RecursiveIteratorIterator::LEAVES_ONLY [, int $flags = 0 ]] )

	void beginChildren ( void )				- Переход к первому дочернему элементу
	void beginIteration ( void )				- Начало навигации
	void endChildren ( void )				- Вызывается, когда закончились элементы находящиеся на одном уровне родства. 
	void endIteration ( void )				- Окончание навигации
!	RecursiveIterator callGetChildren ( void )	- Получает дочерние элементы текущего элемента итератора. 
!	bool callHasChildren ( void )			- Вызывается для каждого элемента, чтобы проверить, есть ли у него дочерние. 
!	int getDepth ( void )					- Определяет текущую глубину рекурсии
!	mixed getMaxDepth ( void )				- Определяет максимальную допустимую глубину вложенности элементов (рекурсии). 
!	void setMaxDepth ([ string $max_depth = -1 ] ) - Задает максимальную глубину вложенности элементов(рекурсии). 
	void nextElement ( void )				- Вызывается, когда следующий элемент становится доступен
	RecursiveIterator getSubIterator ( void )- Получение активного вложенного итератора 
			
  переопределённый метод интерфейса OuterIterator
	iterator getInnerIterator ( void )	- Возвращает внутренний итератор для текущего элемента. 

  переопределённые методы интерфейса Iterator 		
	void rewind ( void )	- Перемещает указатель в начало массива
	bool valid ( void )		- Проверяет, содержит ли массив еще записи
	mixed key ( void )		- Возвращает ключ текущего элемента массива
	mixed current ( void )	- Возвращает текущий элемент в массиве	
	void next ( void )		- Перемещает указатель за следующую запись			
} 
 
Класс RecursiveIteratorIterator - (многомерный итерирующий итератор) может быть использован для перебора рекурсивных итераторов класса RecursiveArrayIterator().		
Даёт полный набор инструментов для работы с многомерными итераторами через циклы foreach. 		
В связке new RecursiveIteratorIterator(new RecursiveArrayIterator($array)) предоставляет абсолютную гибкость работы с многомерным массивом $array
Работает с RecursiveArrayIterator по принципу матрёшки:
	$array = array();
	$iterator = new RecursiveArrayIterator($array);
	$iterator = new RecursiveIteratorIterator($iterator);
	foreach (iterator as $key=>$value);
	unset ($iterator, $array);
// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach ( new RecursiveIteratorIterator( new RecursiveArrayIterator(array()) ) as $key=>$value ); 
*/
/*
$reflection = new ReflectionClass('RecursiveIteratorIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
$tree = array();
$tree[1][2][3] = 'lemon';
$tree[1][4] = 'melon';
$tree[2][3] = 'orange';
$tree[2][5] = 'grape';
$tree[3] = 'pineapple';

print_r($tree);

// классическая связка для работы с многмерными массивами
$arrayiter = new RecursiveArrayIterator($tree);
$iterator = new RecursiveIteratorIterator($arrayiter);
foreach ($iterator as $key => $value) {
  $d = $iterator->getDepth();			// показывает степень вложенности текущего элемента итератора (0 это первый уровень)
  echo "depth=$d k=$key v=$value\n";
}

/*
 * Рекурсивный проход по директории
 */
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator("../")) as $key=>$val)
{
	echo'<pre>';
    echo $key," => ",$val,"\n";
}
?>