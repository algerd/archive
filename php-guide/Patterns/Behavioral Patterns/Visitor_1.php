<?php
/*
Паттерн Visitor определяет операцию, выполняемую на каждом элементе из некоторой структуры без изменения классов этих объектов. 
Таксомоторная компания использует этот паттерн в своей работе. Когда клиент звонит в такую компанию, диспетчер отправляет к нему свободное такси. 
После того как клиент садится в такси, его доставляют до места.

Паттерн Visitor Позволяет, не изменяя классы объектов, добавлять в них новые функции.
Для этого вся обрабатывающая функциональность переносится из самих классов (эти классы становятся "легковесными") в иерархию наследования Visitor.
При этом паттерн Visitor использует технику "двойной диспетчеризации". 
Обычно при передаче запросов используется "одинарная диспетчеризация" – то, какая операция будет выполнена для обработки запроса, зависит от имени запроса и типа получателя. 
В "двойной диспетчеризации" вызываемая операция зависит от имени запроса и типов двух получателей (типа Visitor и типа посещаемого элемента Element).		
Иногда приводятся возражения по поводу использования паттерна Visitor, поскольку он разделяет данные и алгоритмы, что противоречит концепции ООП.		
		
Реализация паттерна Visitor по шагам:
    1.Добавьте метод accept(Visitor) иерархию "элемент".
    2.Создайте базовый класс Visitor и определите методы visit() для каждого типа "элемента".
    3.Создайте производные классы Visitor для каждой "операции", исполняемой над "элементами".
    4.Клиент создает объект Visitor и передает его в вызываемый метод accept().

Суть:
	Есть какая-то библиотека классов (напр древовидная Composite.php) и надо добавить в них новый функционал, не изменяя библиотеку.
    Это может понадобиться, если этабиблиотека используется много раз и в разных проектах и переделывать её каждый раз накладно.
    Для этого используют паттерн Visitor, который добавляет функционал в отдельную библиотеку классов Visitor, которая потом связывается
    с основной библиотекой с помощью метода accept(Visitor). Это распространённая практика при использовании сторонних библиотек.
    Если структура классов создаётся с нуля или её изменит не представляет сложностей, то лучше новый функционал внедрять напрямую в классы 
    без ипользования паттерна Visitor.
	Пример: какой-то ORM баз данных можно дополнить своим функционалом с помощью Visitor(даже несколькими вариантами), при этом сама библиотека ORM останется неизменной. 
    Это добавит гибкость (можно использовать родную ORM или какое-то её расширение в виде Visitor) и надёжность (в случае багов расширения ничего не мешает использовать неизменённую ORM)
*/

// 1. Добавьте метод accept(Visitor) в иерархию абстрактного класса Element		
abstract class Element {
	public function accept( Visitor $v ) {
		$v->visit($this);
	}
}				
		
class This extends Element {
	public function thiss() {
        return "This";
    }		
}

class That extends Element {
	public function that() {
        return "That";
    }	
}

class Other extends Element{
    public function other() {
        return "Other";
    }
}				
// 2. Создайте абстрактный класс Visitor и определите методы visit()для каждого типа "элемента"
// PHP не поддерживает перегрузку методов и нельзя создать 3 метода  visit() с разными параметрами,
// поэтому метод visit будет передавать управление в зависимости от передаваемого аргумента в именованные от классов мeтоды visit
abstract class Visitor {
	public function visit( Element $element ) {
		if ( $element instanceof This ) $this->visitThis( $element );
		elseif ( $element instanceof That ) $this->visitThat( $element );
		elseif ( $element instanceof Other ) $this->visitOther( $element );
	}	
	// методы для каждого типа Element
	abstract public function visitThis( This $element );
	abstract public function visitThat( That $element );
	abstract public function visitOther( Other $element );		
}

// 3. Создайте производные классы Visitor для каждой "операции", исполняемой над "элементами"
class UpVisitor extends Visitor {
	public function visitThis( This $element ) {
		echo 'do Up on '.$element->thiss().'<br>';
	}
	public function visitThat( That $element ) {
		echo 'do Up on '.$element->that().'<br>';	
	}
	public function visitOther( Other $element ) {
		echo 'do Up on '.$element->other().'<br>';	
	}	
}

class DownVisitor extends Visitor {
	public function visitThis( This $element ) {
		echo 'do Down on '.$element->thiss().'<br>';
	}
	public function visitThat( That $element ) {
		echo 'do Down on '.$element->that().'<br>';	
	}
	public function visitOther( Other $element ) {
		echo 'do Down on '.$element->other().'<br>';	
	}	
} 

// 4.Клиент создает объект Visitor и передает его в вызываемый метод accept() объекта Element.
// Это значит: объектам Element динамически добавляем функционал из Visitor
$list = [ new This(), new That(), new Other() ];  // объекты Element
$up = new UpVisitor();			// объект Visitor
$down = new DownVisitor();		// объект Visitor

for ( $i = 0; $i < 3; $i++ )
    $list[$i]->accept( $up );   // добавляем функционал класса UpVisitor 
for ( $i = 0; $i < 3; $i++ )
    $list[$i]->accept( $down ); // добавляем функционал класса DownVisitor

/*
Вывод программы:
do Up on This
do Up on That
do Up on Other
do Down on This
do Down on That
do Down on Other	
*/
