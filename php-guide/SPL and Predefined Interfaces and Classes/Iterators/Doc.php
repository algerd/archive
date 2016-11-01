<?php
/*
Пример работы итераторов с некоторыми условными упрощениями (для лучшего понимания).
Шаблон Итератора SPL построен по принципу паттернов DI-Aggregation (и его частного расширения Iterator):
	массив помещается в объект $arrayIterator класса ArrayIterator1, который адаптирует массив к работе в итерациях. 
	Объект-итератор $arrayIterator  помещатеся в один из объектов (DI-Aggregation):
	- в объект $iteratorIterator класса IteratorIterator1 для проведения простых итераций
	- в объект $limitIterator класса FilterIterator1 для проведения расширенных итераций
	- в объект $filterIterator класса LimitIterator1 для проведения расширенных итераций
Аналогично и с многомерным массивом:
	- массив помещается в контейнер класса RecursiveArrayIterator, а тот в свою очередь агреггируется в итератор RecursiveIteratorIterator
*/
interface Iterator1 {
	public function rewind ();    
    public function next (); 
}
	// Класс-контейнер массива для его адаптации к итерациям
	class ArrayIterator1 implements Iterator1 
	{
		public function __construct ($array = array()){}
		public function asort(){echo __CLASS__.'сортирует массив<br>';}	
		// переопределённые методы Iterator
		public function rewind (){}    
		public function next (){}
	}

	// Класс-обёртка итератора класса ArrayIterator1 для простых итераций
	class IteratorIterator1 implements Iterator1 
	{
		protected $iterator;
		public function __construct ( Iterator1 $iterator ){$this->iterator = $iterator;}
		public function getInnerIterator(){ 
			echo 'IteratorIterator1 имеет доступ к ArrayIterator1 и может его итерировать <br>';
			return $this->iterator;}
		// переопределённые методы Iterator
		public function rewind (){}    
		public function next (){}
	}
		// Класс-обёртка итератора класса ArrayIterator1, расширяющий класс IteratorIterator1 (для расширенных итераций) 
		class FilterIterator1 extends IteratorIterator1 implements Iterator1	
		{	
			public function __construct ( Iterator1 $iterator ){$this->iterator = $iterator;}		
			public function accept(){echo __CLASS__.'фильтрует объект класса ArrayIterator<br>';}			
		}
		// Класс-обёртка итератора класса ArrayIterator1, расширяющий класс IteratorIterator1 (для расширенных итераций)
		class LimitIterator1 extends IteratorIterator1 implements Iterator1 
		{
			public function __construct ( Iterator1 $iterator ){$this->iterator = $iterator;}
			public function getPosition(){echo __CLASS__.'ограничивает объект класса ArrayIterator<br>';}		
		}
		
		
// контейнер массива		
$arrayIterator = new ArrayIterator1([1,2,3,4,5]);
$arrayIterator->asort();
echo'<hr>';
// помещаем контейнер массива в итератор
$iteratorIterator = new IteratorIterator1($arrayIterator);
$iteratorIterator->getInnerIterator()->asort();
echo'<hr>';
$filterIterator = new FilterIterator1($arrayIterator);
$filterIterator->accept();
$filterIterator->getInnerIterator()->asort();
echo'<hr>';
$limitIterator = new LimitIterator1($arrayIterator);	
$limitIterator->getPosition(); 
$limitIterator->getInnerIterator()->asort();
echo'<hr>';
// помещаем один итератор в другой итератор
$limitIterator = new LimitIterator1($filterIterator);	
$limitIterator->getPosition(); 
// $limitIterator->getInnerIterator()->asort(); - уже нелоступно