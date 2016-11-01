<?php
/*
Паттерн Memento хорошо дополняет паттерн Command для восстановления состояния объекта до выполнения команды.
Без паттерна Memento для восствновления (отмены команды) приходилось в каждом классе-команде делать метод отмены действия unExecute(),
а с паттерном Memento создаётся хранилище прежних состояний объекта до выполнения команд и в случае отмены выполненной команды - 	
вызывать прежнее состояние объекта.
			
Совместное использование Memento и Command позволит отделить от действия состояние. 
Вопрос: зачем тогда объекты-действия? Чтобы сохрянять действия как единицы памяти и потом ими манипулировать (удалять, запускать, отменять).
		
Совместное использование Memento и Command целесообразно тогда, когда сохраняемое состояние объекта имеет несущественный объём, как	в примере ниже: сохраняется только значение вычисления.
В примере Command_2 ,если его сделать с использованием Memento по аналогии с примером ниже, то сохраняемым состояением был бы весь документ (массив строк)
и при каждом редактировании документа в массив состояний добавлялся бы весь документ. Это через десяток операций привело бы к десятикратному превышению объёма состяний над объёмом самого документа,
что абсолютно неправильно. В этой ситуации целесообразно сохранять не состояние документа, а объекты-действия  в массиве действий контроллера как в примере Command_2.
Важно контролировать, чтобы сохранение состояний не вело к значительному росту объёма памяти.		
*/
///////////////////////////  Invoker - Originator ///////////////////////// 
interface Originator {
	function saveState();	// возвращает 'снимок' состояния объекта
	function restoreState( CalculatorMemento $memento ); // восстанавливает состояние объекта их передпнного 'снимка' состояния
}	
interface Trigonometric {
	function cosine();
	function sine();
	function tang();
}			
class Calculator implements Trigonometric, Originator {
    private $value = 0;		// Текущий результат выполнения команд
 
	function setValue( $v ) { $this->value = $v; }
	function getValue() { return $this->value; }		
	
	function cosine() { $this->value = cos( $this->value ); }
	function sine()   { $this->value = sin( $this->value ); }
	function tang()   { $this->value = tan( $this->value ); }
	
	function saveState() {
		return new CalculatorMemento($this->value);
	}
	function restoreState( CalculatorMemento $memento ) {
		$this->value = $memento->getState();
	}
}	
///////////////////////// Memento ///////////////////////////////
class CalculatorMemento {
	private $state;
	function __construct( $state ) {
		$this->state = $state;
	}
	function getState() {
		return $this->state;
	}
}	

///////////////////////// Command /////////////////////////////////
abstract class CalculatorCommand {
	protected $calculator;				// class Calculator
	abstract function execute();		// исполнить операцию
	function setCalculator( Calculator $calc ) {
		$this->calculator = $calc;
	}
}
class Cosine extends CalculatorCommand {	
	function execute() {
		$echo = "cos({$this->calculator->getValue()})";
		$this->calculator->cosine();
		return $echo;
	}
}
class Sine extends CalculatorCommand {	
	function execute() {
		$echo = "sin({$this->calculator->getValue()})";
		$this->calculator->sine();
		return $echo;
	}
}
class Tang extends CalculatorCommand {	
	function execute() {
		$echo = "tg({$this->calculator->getValue()})";
		$this->calculator->tang();
		return $echo;
	}
}

//////////////////////// Controller ////////////////////////////////
interface Controller {
	function addCommand( CalculatorCommand $command);  // добавить команду в очередь
	function deleteCommand ();		// удалить команду из очереди
	function executeCommand();		// исполнить команду из очереди
	function unExecuteCommand();	// отменить последнюю исполненную команду
}
class CalculatorController implements Controller {
	private $calculator;			// класс Сalculator
	private $doneCommands;			// очередь команд
	private $mementoQueue;			// очередь состояний CalculatorMemento
	private $echoResult;			// очередь вывода операций
	
	function __construct() {
		$this->calculator = new Calculator();
		$this->doneCommands = new SplQueue();
		$this->mementoQueue = new SplQueue();
		$this->echoResult = new SplQueue();
	}
	function setValue( $value ) {
		$this->calculator->setValue( $value );
	}
	function addCommand( CalculatorCommand $command ) {
		$command->setCalculator( $this->calculator );
		$this->doneCommands->enqueue($command);
		return $this;
	}
	function deleteCommand () {
		if ( !$this->doneCommands->isEmpty() )
			$this->doneCommands->pop();
		return $this;
	}
	function executeCommand() {
		if ( !$this->doneCommands->isEmpty() ) {
			$this->mementoQueue->enqueue( $this->calculator->saveState() );	// добавляем в очередь 'снимок' состояния объекта класса Сalculator
			$result = $this->doneCommands->dequeue()->execute();
			$this->echoResult->enqueue($result); 
			return true;
		}
		return false;		// нет команд для исполнения
	}
	function unExecuteCommand() {
		if ( !$this->mementoQueue->isEmpty() ) {
			$this->calculator->restoreState( $this->mementoQueue->pop() );	// восстанавливаем состояние объекта класса Сalculator из очереди 'снимков' состояния
			$this->echoResult->pop();
			return true;
		}
		return false;		// нет команд для исполнения
	}
	function getResult() {
		foreach ( $this->echoResult as $echo )
			echo $echo;
		echo ' = '.$this->calculator->getValue();
	}
}

$controller = new CalculatorController();

$controller->setValue(M_PI_4);

$controller->addCommand(new Cosine())->addCommand(new Sine());
$controller->deleteCommand();
$controller->addCommand(new Sine());

while($controller->executeCommand());

$controller->unExecuteCommand();

$controller->addCommand(new Sine())->addCommand(new Tang());

while($controller->executeCommand());

$controller->getResult();
