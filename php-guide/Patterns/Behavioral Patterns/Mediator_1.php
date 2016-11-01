<?php
/*
Паттерн Mediator определяет объект, инкапсулирующий взаимодействие множества объектов. 
Mediator делает систему слабо связанной, избавляя объекты от необходимости ссылаться друг на друга, что позволяет изменять взаимодействие между ними независимо.
Паттерн Mediator вводит посредника для развязывания множества взаимодействующих объектов.
Заменяет взаимодействие "все со всеми" взаимодействием "один со всеми".

Действуя как центр связи, объект-посредник Mediator контролирует и координирует взаимодействие группы объектов. 
При этом объект-посредник делает взаимодействующие объекты слабо связанными, так как им больше не нужно хранить ссылки друг на друга – все взаимодействие идет через этого посредника. 
Расширить или изменить это взаимодействие можно через его подклассы.

Слабая связанность достигается благодаря тому, что вместо непосредственного взаимодействия друг с другом коллеги общаются через объект-посредник. 
Башня управления полетами в аэропорту хорошо демонстрирует этот паттерн. Пилоты взлетающих или идущих на посадку самолетов в районе аэропорта общаются с башней вместо непосредственного общения друг с другом. 
Башня определяет, кто и в каком порядке будет садиться или взлетать. Важно отметить, что башня контролирует самолеты только в районе аэродрома, а не на протяжении всего полета.		
				
Отправка сообщений через посредника:
	- объекты классов Colleague1 и Colleague2 общаются между собой путём отправки (send) и приёма сообщений notify() 
	через посредника объект класса Mediator
Объекты классов Colleague1 и Colleague2 не видят друг друга.
Все связи между ними сделаны в одном месте - в посреднике Mediator, а не вкаждом классе Colleague. 
Благодаря этому, при необходимости добавления новых классов Colleague , новые связи между объектами будут добавляться 
только в одном месте - в посреднике Mediator.		
Посредник Mediator - это диспетчер связей между объектами по.
*/

//абстрактный класс посредник
abstract class Mediator {
    // Отправка сообщения {message} указанному получателю {colleague}
    public abstract function send( $message, Colleague $colleague );
}

class ConcreteMediator extends Mediator {
    private $colleague1;
    private $colleague2;
    public function setColleague1( ConcreteColleague1 $colleague ){
        $this->colleague1 = $colleague;
    }
 
    public function setColleague2(ConcreteColleague2 $colleague){
        $this->colleague2 = $colleague;
    }
 
    public function send($message, Colleague $colleague) {
        if ($colleague == $this->colleague1) {
            $this->colleague2->notify($message);
        } else {
            $this->colleague1->notify($message);
        }
    }
}
 
abstract class Colleague {
    protected $mediator; 
    function __construct( Mediator $mediator ) {
        $this->mediator = $mediator;
    }
    // Отправка сообщения посредством посредника
    function send( $message ){
        $this->mediator->send( $message, $this );
    }
    // Обработка полученного сообщения реализуется каждым конкретным наследником
    abstract function notify( $message );
} 

class ConcreteColleague1 extends Colleague {
    public function notify( $message ) {
        echo sprintf( "Collegue1 gets message: %s\n", $message );
    }
}
class ConcreteColleague2 extends Colleague {
    public function notify( $message ) {
        echo sprintf( "Collegue2 gets message: %s\n", $message );
    }
}
 
$mediator = new ConcreteMediator();
 
$collegue1 = new ConcreteColleague1($mediator);
$collegue2 = new ConcreteColleague2($mediator);
 
$mediator->setColleague1($collegue1);
$mediator->setColleague2($collegue2);
 
$collegue1->send('How are you ?');
$collegue2->send('Fine, thanks!');