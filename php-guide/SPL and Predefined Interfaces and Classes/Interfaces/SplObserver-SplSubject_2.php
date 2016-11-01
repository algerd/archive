<?php
/*
!!! Великолепный пример реализации паттерна Observer встроенными средствами SPL !!!
Объект наблюдения - газета. Наблюдатели - читатели газеты. Когда в газету добавляется новость, поступает уведомление
читателям и они "читают" новость. 
*/

// Объект, который создаёт новости
class Newspaper implements \SplSubject{
    private $name;
    private $observers = array();
    private $content;
   
    public function __construct($name) {
        $this->name = $name;
    }
    // добавляет наблюдателя объект класса SplObserver
    public function attach(\SplObserver $observer) {
        $this->observers[] = $observer;
    }
    // удаляет наблюдателя класса объект SplObserver
    public function detach(\SplObserver $observer) {       
        $key = array_search($observer,$this->observers, true);
        if($key){
            unset($this->observers[$key]);
        }
    }  
    // добавляет новость
    public function breakOutNews($content) {
        $this->content = $content;
		// послать уведомление наблюдателям о добавлении новости
        $this->notify();
    }
	// возвращает новость
    public function getContent() {
        return $this->content." ({$this->name})";
    }  
    // уведомить наблюдателей класса SplObserver
    public function notify() {
        foreach ($this->observers as $value) {
            $value->update($this);
        }
    }
}

// Наблюдатель-читатель новости
class Reader implements SplObserver{
    private $name;
   
    public function __construct($name) {
        $this->name = $name;
    }
    // выводит сообщение от читателя, когда приходит уведомление от объекта класса SplSubject
    public function update(\SplSubject $subject) {
        echo $this->name.' is reading breakout news <b>'.$subject->getContent().'</b><br>';
    }
}

// объект наблюдения - распространитиель новости
$newspaper = new Newspaper('Newyork Times');

// наблюдатели - читатели новости
$allen = new Reader('Allen');
$jim = new Reader('Jim');
$linda = new Reader('Linda');

// добавляем наблюдателей-читателей новости
$newspaper->attach($allen);
$newspaper->attach($jim);
$newspaper->attach($linda);

// удаляем наблюдателя-читателя новости
$newspaper->detach($linda);

// добавление новости -> увтоматически уведомляет наблюдателей-читателей новости -> выводит сообщение от читателя
$newspaper->breakOutNews('USA break down!');

//=====output======
//Allen is reading breakout news USA break down! (Newyork Times)
//Jim is reading breakout news USA break down! (Newyork Times)

?>