<?php
/*
 * Шаблон Iterator позволяет перебрать все элементы коллекции (списка объектов) без учёта их реализации.
 * В итераторе можно прописать не только правила простого перебора (как в нашем примере), но и совершать 
 * определённые действия над элементами перебираемого списка.
 *  Для удобства подобный шаблон реализован в SPL с помощью интерфейсов Traversable - Iterator и IteratorAggregate 
 */

// Основной Класс, описывающий какое-то состояние системы
class Module 
{
	private $_status;
	function __construct($status) { 
        $this->_status = $status ;      
    }
    public function getStatus() {
		return $this->_status;
    }
}

// Вспомогательный Класс-список (или массив) объектов класса Module
class ModsList 
{
	public $mods_list;          //массив объктов класса Module
    public $mods_count=0;       //число объектов в массиве
	
	function __construct() {}
	// функция добавления объектов класса Module в массив $this->_mods_list. 
	public function add(Module $mod) {
		$this->mods_list[$this->mods_count+1] = $mod;
        $this->mods_count ++;   // счетаем объекты
		return $this;           //Возвращает сам объект класса ModsList для удобного последовательного вызова добавления объектов
	}
}

// Класс-итератор, перебирающий массив объектов Класса-списка ModsList
class ModsIterator 
{
	private $_mods;          // объект-список Класса ModsList
	private $_cur_mod = 0;   // позиция итерации
	// инициализируем объект-список Класса ModsList
	function __construct(ModsList $mods) {
		$this->_mods = $mods;
	}
	// подсчёт и проверка конца итерации (перебора)
	public function hasModule() {
        $this->_cur_mod += 1;
		if ($this->_cur_mod <= $this->_mods->mods_count) return true;
		else return false;
	}
	// возвращаем объект класса Module из списка в соответствии с позицией итерации
	public function getCurMod() {
		return $this->_mods->mods_list[$this->_cur_mod];	
	}
    // возвращаем позицию итерации
    public function getPos() {
        return $this->_cur_mod;
    }
}

////////////////////////////////////////////////////////////////////////////////////////////////////

// есть некоторые объекты класса Module
$mod1 = new Module('OK');
$mod2 = new Module('Error');
$mod3 = new Module('Warning');
$mod4 = new Module('Error');

// создаём объект-список объектов класса Module()
$mods = new ModsList();
$mods->add($mod1)->add($mod2)->add($mod3)->add($mod4); // добавляем в список объекты

// создаём итератор и помещаем в него объект-список
$itmod = new ModsIterator($mods);

// запускаем итерацию - проверка окончания итерации через метод hasModule()
while ($itmod->hasModule()) {
	$module = $itmod->getCurMod();    // получаем объект Module в ссответствие с позицией итерации 
    echo 'Состояние модуля №'.$itmod->getPos().' статус: '.$module->getStatus().'<br>';     
}
//var_dump($itmod);
?>