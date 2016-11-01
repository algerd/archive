<?php
/*
Объекты-логгеры связываем между собой в цепь и передаём каждому свой приоритет. 
И в зависимости от приоритета сообшения, оно будет обработано определённым логгером после перебора в цепи.
*/
abstract class Logger {
    const ERR = 3;
	const NOTICE = 5;
	const DEBUG = 7;
	protected $priority;	
	protected $next;	// The next element in the chain of responsibility

	function __construct( $priority ) {
		$this->priority = $priority;
	}
	function setNext( Logger $log ) {
		$this->next = $log;
		return $log;
	}
	// если передавемый приоритет <= приоритета объекта - вывод сообщения, в противном случае - переход к следующеиу звену цепи
	function message( $msg, $priority ) {
		if ( $priority <= $this->priority ) $this->writeMessage( $msg );
		elseif ( $this->next != null ) $this->next->message( $msg, $priority );
	}	
	protected abstract function writeMessage($msg);
}

class StdoutLogger extends Logger {
	protected function writeMessage( $msg ) {
		echo sprintf("Writing to stdout: %s<br>", $msg);
	}
}
class EmailLogger extends Logger {
	protected function writeMessage( $msg ) {
		echo sprintf("Sending via email: %s<br>", $msg);
	}
}
class StderrLogger extends Logger {
	protected function writeMessage( $msg ) {
		echo sprintf("Sending to stderr: %s<br>", $msg);
	}
}

//цепочка обязанностей
class ChainOfResponsibilityExample {
	function run() {
		// строим цепочку обязанностей
		$logger = new StdoutLogger(Logger::DEBUG);
		$logger1 = $logger->setNext(new EmailLogger(Logger::NOTICE));
		$logger2 = $logger1->setNext(new StderrLogger(Logger::ERR));

		// вывод сообщения объектом, которому соответствует передаваемый приоритет
		$logger->message("Entering function y.", Logger::DEBUG);	// Handled by StdoutLogger
		$logger->message("Step1 completed.", Logger::NOTICE);		// Handled by StdoutLogger and EmailLogger
		$logger->message("An error has occurred.", Logger::ERR);	// Handled by all three loggers
	}
}

$chain = new ChainOfResponsibilityExample();
$chain->run();