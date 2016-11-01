<?php
/*
Паттерн поведения объектов Command, известен также под именем Action (действие).
Обеспечивает обработку команды в виде объекта, что позволяет сохранять её, передавать в качестве параметра методам, 
а также возвращать её в виде результата, как и любой другой объект. 
Объект команды заключает в себе само действие и его параметры.	
		
Паттерн Command преобразовывает запрос на выполнение действия в отдельный объект-команду. 
Такая инкапсуляция позволяет передавать эти действия другим функциям и объектам в качестве параметра, приказывая им выполнить запрошенную операцию. 
Команда – это объект, поэтому над ней допустимы любые операции, что и над объектом.
Интерфейс командного объекта определяется абстрактным базовым классом Command и в самом простом случае имеет единственный метод execute(). 
Производные классы определяют получателя запроса (указатель на объект-получатель) и необходимую для выполнения операцию (метод этого объекта). 
Метод execute() подклассов Command просто вызывает нужную операцию получателя.

 В паттерне Command может быть до трех участников:
    - Reciever или Controller - приниамет команды от клиента, обрабатывает их и вызывает соответствующие команды из Command
 		В контроллере команды клиента могут сохраняться, удаляться, выполняться и отменяться.
    - Command или Action - объекты-действия. В зависимотси от запроса клиента контроллер Controller обращается 
 		к соответствующему объекту-действию класса Command. Объект-действие вызывает действие над объектом Invoker
    - Invoker - объект, над которым производит действие объект-действие Action в соответствии с запросом клиента	
		
Паттерн Command отделяет объект, инициирующий операцию, от объекта, который знает, как ее выполнить. 
Единственное, что должен знать инициатор, это как отправить команду. 
Это придает системе гибкость: позволяет осуществлять динамическую замену команд, использовать сложные составные команды, осуществлять отмену операций.	
		
Применяется в системах управления событиями в фронтконтроллерах MVC и событийно-управляемых системах: 

Данный пример противоречит логике ООП и потому он несовсем соответствует паттерну Command.
Следовало бы :
	- из switch вынести все case операторы в отдельные методы класса Calculator
	- создать классы-действия [nameoperator]Command, соответствующие каждой операции калькулятора с методами execute и unExecute
	- из контроллера в зависимости от переданной команды клиента вызывать объекты-действия [nameoperator]Command	
	- сделать в контроллере методы на добавление команды, отмену добавленной команды и исполнение команды (сейчас добавление команды и запуск делает один метод compute)
	Это сильно раздует код, но сделает его логичным. В данном примере из-за простоты математ. операций лучше было применить switch,
	но для более сложных действий надо разделять все операции. Кроме того, в данном примере отмена команды выполнена на основе обратных вычислений в Undo(),
    что для некоторых мат. операций невозможно и в случае их добавления данных код рухнет.
	В соответствии с принципами разделения ООП и согласно логике паттерна Command сделан пример Command_2.php
*/			
///////////////////////////  "Invoker" : вызывающий ///////////////////////// 
// Класс получатель и исполнитель "команд"
class Calculator {
    private $curr = 0;	// Текущий результат выполнения команд
 
    public function Operation( $operator, $operand ) {
		//выбрать оператора для вычисления результата
		switch($operator) {
			case '+': $this->curr+=$operand; break;
			case '-': $this->curr-=$operand; break;
			case '*': $this->curr*=$operand; break;
			case '/': $this->curr/=$operand; break;
		}
		print("Текущий результат = $this->curr (после выполнения $operator c $operand)<br>");
    }
}

/////////////////////////// Command ///////////////////////////////
abstract class Command {
    public abstract function Execute();		// исполнить операцию
    public abstract function UnExecute();	// отменить операцию
}
 
class CalculatorCommand extends Command {
    public $operator;	// Текущая операция команды 
    public $operand;	// Текущий операнд
    public $calculator;	// объект класса Calculator, для которого предназначенна команда
 
    public function __construct( Calculator $calculator, $operator, $operand ) {
		$this->calculator = $calculator;
		$this->operator = $operator;
		$this->operand = $operand;
    }
    public function Execute() {
		$this->calculator->Operation( $this->operator, $this->operand );
    }
    public function UnExecute() {
		$this->calculator->Operation( $this->Undo($this->operator), $this->operand );
    } 
    // Какое действие нужно отменить
    private function Undo($operator) {
		//каждому произведенному действию найти обратное
		switch($operator) {
			case '+': $undo = '-'; break;
			case '-': $undo = '+'; break;
			case '*': $undo = '/'; break;
			case '/': $undo = '*'; break;
			default : $undo = ' '; break;
		}
		return $undo;
    }
}

////////////////////////// "Receiver" : получатель    ////////////////////////////
// Класс, вызывающий команды - Этот класс будет получать команды на исполнение
class Controller {
    private $calculator;		 // объект класса Calculator, который будет исполнять команды
    private $commands = array(); // Массив операций
    private $current = 0;		 // Текущая команда в массиве операций
 
    function __construct() {
    	$this->calculator = new Calculator();
    }
	// Функция выполнения команд
    function Compute( $operator, $operand ) {
		// Создаем команду операции и выполняем её
		$command = new CalculatorCommand( $this->calculator, $operator, $operand );
		$command->Execute();
		// Добавляем операцию к массиву операций и увеличиваем счетчик текущей операции
		$this->commands[]=$command;
		$this->current++;
	}
    // Функция отмены команд,  $levels количество отменяемых операций
    function Undo( $levels ) {
		print("\n---- Отменить $levels операций ");
		// Делаем отмену операций
		for ($i = 0; $i < $levels; $i++) {
			if ($this->current > 0)
				$this->commands[--$this->current]->UnExecute();
		}
    }
    // Функция возврата отмененных команд, $levels количество возвращаемых операций
    function Redo( $levels ) {
		print("\n---- Повторить $levels операций ");
		// Делаем возврат операций
		for ($i = 0; $i < $levels; $i++) {
			if ( $this->current < count($this->commands) - 1 )
				$this->commands[$this->current++]->Execute();
		}
    }	
}
 
$controller = new Controller();
 
// Произвольные команды
$controller->Compute('+', 100);
$controller->Compute('-', 50);
$controller->Compute('*', 10);
$controller->Compute('/', 2);

// Отменяем 4 команды
$controller->Undo(4);

// Вернём 3 отменённые команды.
$controller->Redo(3);
?>