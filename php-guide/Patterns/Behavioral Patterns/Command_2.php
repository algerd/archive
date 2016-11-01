<?php
/*
Паттерн Command применяется, когда необходимо действию передать состояние. Простой метод класса не имеет состояния,
его нельзя сохранить, его нельзя куда-то записать или отложить выполнение, ему нельзя передать свойства.
Если действие выразить в виде отдельного класса (Action-class), то объекты этого класса, выполняющие наше действие, будут 
сохрвняться в памяти со всеми вытекающими отсюда плюсами и тогда с действием можно работать как с единицей памяти.

Кдасс Reciever работает с состояниями объектов-действий. По запросам клиента, в этом классе сохраняются/откладываются, запускаются или
отменяются ранее выполненные действия. Без представления действия в виде единицы памяти (объекта) невозможно было бы проделывать эти операции над действием.
*/
////////////////////////// "Invoker" : вызывающий ////////////////////////////
interface Editable {
	// методы, на основе которых будут созданы Action-классы
	function addStrLine();		// добавление строки и задание номера редактируемой строки
	function insertStrLine();	// вставка редактируемой строки
	function updateStrLine();	// обновление редактируемой строки
	function removeStrLine();	// удаление редактируемой строки	
}
class Document implements Editable {
	private $data;				// список строк
	private $line;				// номер редактируемой строки
	private $str;				// вводимый текст
	function __construct() {
		$this->data = new SplQueue();
	}
	function setLine( $line ) {
		$res = (int)$line;
		if ( $res < 0 or $res > $this->data->count()-1 ) 
			throw new OutOfRangeException ("Error: $line line is not exist!<br>");		
		$this->line = $res;
	}
	function setStr( $str ) {
		$this->str = $str;
	}
	function getLine() {
		return $this->line;
	}
	function getStrLine() {
		return $this->data[$this->line];
	}
	function getDoc() {
		return $this->data;
	}
	// методы, на основе которых будут созданы Action-классы
    function addStrLine() {
		$this->data->enqueue($this->str);
		$this->line = $this->data->count()-1;
	}		
    function insertStrLine() {
		$this->data->add( $this->line, $this->str );
	}	
    function updateStrLine() {		
		$this->data[$this->line] = $this->str;	
	}
    function removeStrLine() {
		$this->data->offsetUnset( $this->line );
	}	
}

////////////////////////// Command ///////////////////////////////
abstract class Command {
	protected $document;			// объект класса Document
	protected $line;
	protected $str;
	protected $flag = false;		// execute() -> true
	abstract function execute();	// выполнить команду
	abstract function unExecute();	// отменить команду
	function setDocument( Document $doc ) {
		$this->document = $doc;
	}
} 
class AddCommand extends Command {
	function __construct( $str ) {
		$this->str = $str;
	}
    function execute() {
		$this->document->setStr($this->str);
	    $this->document->addStrLine();
		$this->line = $this->document->getLine();
	    $this->flag = true;
    }
	function unExecute(){
	    if ( $this->flag ) {
		    $this->document->setLine($this->line);
		    $this->document->removeStrLine();
		    $this->flag = false;
	    }
	}
}
class InsertCommand extends Command {
	function __construct( $line, $str ) {
		$this->str = $str;
		$this->line = $line - 1;		
	}
    function execute() {
		$this->document->setLine($this->line);
		$this->document->setStr($this->str);
	    $this->document->insertStrLine();
	    $this->flag = true;
    }
	function unExecute(){
		if ( $this->flag ) {
			$this->document->setLine($this->line);
			$this->document->removeStrLine();
			$this->flag = false;
		}
	}
}
class UpdateCommand extends Command {
	private $str_old;
	function __construct( $line, $str ) {
		$this->str = $str;
		$this->line = $line - 1;	
	}
    function execute() {
		$this->document->setLine($this->line);
		$this->document->setStr($this->str);
		$this->str_old = $this->document->getStrLine();
	    $this->document->updateStrLine();
		$this->flag = true;
    }
	function unExecute(){
	    if ( $this->flag ) {
			$this->document->setLine($this->line);
			$this->document->setStr($this->str_old);
			$this->document->updateStrLine();
			$this->flag = false;
		}
	}
} 
class RemoveCommand extends Command {
	private $str_old;
	function __construct( $line ) {
		$this->line = $line - 1;			
	}
    function execute() {
		$this->document->setLine($this->line);
		$this->str_old = $this->document->getStrLine();
	    $this->document->removeStrLine();
		$this->flag = true;
    }
	function unExecute(){
	    if ( $this->flag ) {
			$this->document->setStr($this->str_old);
			$this->document->setLine($this->line);
			$this->document->insertStrLine();
			$this->flag = false;
		}	
	}
} 
/////////////////////////// "Receiver - Controller"  /////////////////////////
// Инициализирует команды в список и запускает их выполнение или отмену по требованию
class Receiver {
	private $document;				// класс Document
	private $doneCommands;			// очередь команд
	private $current = 0;			// индекс текущей команды на иполнение	
	
	function __construct() {	
		$this->document = new Document;
		$this->doneCommands = new SplQueue();
	}
	// добавить команду в очередь
	function addCommand ( Command $command ) {
		$command->setDocument($this->document);
		$this->doneCommands->enqueue($command);
	}
	// удалить команду из очереди
	function deleteCommand () {
		if ( !$this->doneCommands->isEmpty() )
			$this->doneCommands->pop();
	}
	// исполнить команду из очереди
	function executeCommand() {
		if ( $this->doneCommands->offsetExists($this->current) ) {
			$this->doneCommands[$this->current]->execute();
			$this->current++;
			return true;
		}
		return false;		// нет команд для исполнения
	}
	// отменить исполненную команду
	function unExecuteCommand() {
		if ( $this->doneCommands->offsetExists($this->current - 1) ) {
			$this->current--;
			$this->doneCommands[$this->current]->unExecute();	
			$this->doneCommands->offsetUnset($this->current);
			return true;
		}
		return false;		// нет команд для восстановления
	}
	function show() {
		foreach ($this->document->getDoc() as $line => $str) {
			echo ($line + 1).". $str<br>";
		}	
	}
}	

try {		
	$rec = new Receiver();
	
	for ( $i = 1; $i <= 10; $i++ ) {
		$rec->addCommand( new AddCommand( "Cтрока №$i" ) );	// добавить команду в список - добавить строку
		if ( $i == 1 ) $rec->deleteCommand();				// удалить из списка команд AddCommand($doc, "Cтрока №1")
		$rec->executeCommand();								// исполнить команду из списка
		if ( $i == 5 ) $rec->unExecuteCommand();			// отменить $rec->executeCommand() - AddCommand($doc, "Cтрока №1")
	}	
	$rec->addCommand( new RemoveCommand( 4 ));
	//$rec->addCommand( new RemoveCommand( 28 ));			// выбросит исключение неправильной строки	
	$rec->addCommand( new InsertCommand( 3, 'Insert' ));	
	$rec->addCommand( new UpdateCommand( 5, 'Update' ));

	while($rec->executeCommand());							// запустить все команды
	//$rec->unExecuteCommand();	
	//$rec->unExecuteCommand();
	//$rec->unExecuteCommand();
} 
catch (Exception $e) {
	echo $e->getMessage();
}
 finally {
	$rec->show();
}