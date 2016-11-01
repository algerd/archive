<?php
/*
RecursiveRegexIterator extends RegexIterator implements RecursiveIterator 
{
	mode, flagы и preg_flags - константы класса RegexIterator
	
	__construct ( RecursiveIterator $iterator , string $regex [, int $mode = self::MATCH [, int $flags = 0 [, int $preg_flags = 0 ]]] )

  Переопределённые методы от RecursiveIterator			
	RecursiveIterator getChildren ( void )	- Возвращает итератор для текущего элемента
	bool hasChildren ( void )	- Определяет, возможна ли навигация по содержимому текущего элемента
			
  Наследуемые методы от FilterIterator			
	bool accept ( void )			- Проверяет, является ли текущий элемент итератора допустимым регулярным выражением
	void setFlags ( int $flags )
	int getFlags ( void )
	void setMode ( int $mode )		
	int getMode ( void )
	void setPregFlags ( int $preg_flags )		
	int getPregFlags ( void )
	string getRegex ( void )	
}

RecursiveRegexIterator - Этот рекурсивный итератор может фильтровать другой рекурсивный итератор RecursiveIterator с помощью регулярных выражений. 
Чтобы применить рекурсивно фильтр ко всем элементам рекурсивного итератора, надо создать класс-наследник от RecursiveRegexIterator
и в нём переопределить метод accept(), в котором сделать проверку на наличие итератора в дочернем элементе и вызвать родительский accept():
	return $this->hasChildren() || parent::accept() (см. пример);
		
Тогда итерация класса-фильтра наследника от RecursiveRegexIterator будет(матрёшка):
	$array = array();
	$iterator = new RecursiveArrayIterator($array);
	$iterator = new MyRecursiveRegexIterator($iterator, $regex); // наследник класса RecursiveRegexIterator
	$iterator = new RecursiveIteratorIterator($iterator);
	foreach (iterator as $key=>$value){ };
	unset ($iterator, $array);
// или одной строкой - не создаёт дополнительные переменные-объекты, не надо их потом удалять
	foreach (new RecursiveIteratorIterator (new MyRecursiveRegexIterator(new RecursiveArrayIterator(array()), $regex)) as $key=>$value){};	
*/
/*
$reflection = new ReflectionClass('RecursiveRegexIterator');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

// Фильтр со встроенным в accept() рекурсивным условием фильтрации if($this->hasChildren())
class RecursiveRegexFilter extends RecursiveRegexIterator 
{
	// переопределяем метод, чтобы можно было рекурсивно применять фильтр в accept() ко всем элементам массива (в том числе дочерним)
    public function accept() {
        // текущий элемент пройдет фильтр, если имеет дочерние элементы или он соответствует регулярному выражению
        return $this->hasChildren() || parent::accept();
    }
}

$array = ['test1', 'tt1',['tet3', 'test4', 'test5']];
$recursiveArrayIterator = new RecursiveArrayIterator($array);

$regexfilter = new RecursiveRegexFilter($recursiveArrayIterator, '/^test/', RecursiveRegexIterator::MATCH);
$iterator = new RecursiveIteratorIterator($regexfilter);
foreach($iterator as $key => $value){
    $depth = $iterator->getDepth();		// показывает степень вложенности текущего элемента итератора (0 это первый уровень)
	echo "depth=$depth k=$key v=$value <br>";
}

