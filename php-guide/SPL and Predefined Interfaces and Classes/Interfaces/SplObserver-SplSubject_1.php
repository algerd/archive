<?php
/*
interface SplSubject 
{
	abstract public void attach ( SplObserver $observer ) - Присоединяет наблюдателя (объект класса SplObserver), чтобы он мог получать уведомления об обновлениях. 
	abstract public void detach ( SplObserver $observer ) - Отсоединяет наблюдателя
	abstract public void notify ( void )				  - Посылает уведомление всем присоединенным наблюдателям. 
}
interface SplObserver 
{
	abstract public void update ( SplSubject $subject )   - вызывается, когда любой SplSubject, к которому присоединен наблюдатель, вызывает SplSubject::notify(). 
}

Интерфейсы SplSubject и SplObserver предназначены для реализации шаблона проектирования Наблюдатель (Observer).
Один объект SplSubject делает себя наблюдаемым, добавляя метод, который позволяет другому объекту, наблюдателю SplObserver, 
себя зарегистрировать. Когда SplSubject изменяется, он посылает уведомление зарегистрированным наблюдателям SplObserver. 
Что происходит после получения уведомления с наблюдателем SplObserver, не зависит от наблюдаемого объекта SplSubject.		

!!! Великолепный пример реализации паттерна Observer встроенными средствами SPL в SplObserver-SplSubject_2.php !!!
*/

class MyObserver1 implements SplObserver 
{
    public function update(SplSubject $subject) {
        echo __CLASS__ . ' - ' . $subject->getName().'<br>';
    }
}

class MyObserver2 implements SplObserver 
{
    public function update(SplSubject $subject) {
        echo __CLASS__ . ' - ' . $subject->getName().'<br>';
    }
}

class MySubject implements SplSubject 
{
    private $_observers;
    private $_name;

    public function __construct($name) {
        $this->_observers = new SplObjectStorage();
        $this->_name = $name;
    }

    public function attach(SplObserver $observer) {
        $this->_observers->attach($observer);
    }

    public function detach(SplObserver $observer) {
        $this->_observers->detach($observer);
    }

    public function notify() {
        foreach ($this->_observers as $observer) {
            $observer->update($this);
        }
    }

    public function getName() {
        return $this->_name;
    }
}

$subject = new MySubject("test");
// Создаём наблюдателей объекта $subject
$observer1 = new MyObserver1();
$observer2 = new MyObserver2();

// Регистрируем наблюдателей
$subject->attach($observer1);
$subject->attach($observer2);

echo 'Посылаем уведомление наблюдателям: <br>';
$subject->notify();	

$subject->detach($observer2);	// удаляем наблюдателя
echo 'Посылаем уведомление наблюдателям: <br>';
$subject->notify();

echo '<hr>';
