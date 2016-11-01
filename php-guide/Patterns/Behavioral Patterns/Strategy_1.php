<?php
/*
Существуют системы, поведение которых может определяться согласно одному алгоритму из некоторого семейства. 
Все алгоритмы этого семейства являются родственными: предназначены для решения общих задач, имеют одинаковый интерфейс для использования и отличаются только реализацией (поведением). 
Пользователь, предварительно настроив программу на нужный алгоритм (выбрав стратегию), получает ожидаемый результат. 
Как пример, - приложение, предназначенное для компрессии файлов использует один из доступных алгоритмов: zip, arj или rar.
		
Стратегия (англ. Strategy) - предназначен для определения семейства алгоритмов, инкапсуляции каждого из них и обеспечения их взаимозаменяемости. 
Это позволяет выбирать алгоритм путем определения соответствующего класса. 
Шаблон Strategy позволяет менять выбранный алгоритм независимо от объектов-клиентов, которые его используют.			
Strategy похож на Factory, но в отличие от него Strategy не создаёт объект а совершает действие.

Шаблон стратегия позволяет отделить отделить семейство функционала от абстракции(представление) и тем самым предоставляет
возможность использовать этот функционал для разных классов - реализаций абстракции. Это дальнейшее развитие структурного паттерна Bridge.

В примере семейство функционала - вычисление вынесено из представлений и теперь в зависимости какую стратегию 
будут передавать в представление, такой и будет применяться функционал.
*/

//////////////// Интерфейс стратегии функционала - вычисление
interface StrategyCalculate {
	function calculate( $a, $b ); 
}
class Add implements StrategyCalculate {
	public function calculate( $a, $b ) { return $a + $b; }
}
class Subtract implements StrategyCalculate {
	public function calculate( $a, $b ) { return $a - $b; }
}
class Multiply implements StrategyCalculate {
	public function calculate( $a, $b ) { return $a * $b; }
}


//////////////// какая-то банковая абстракция (счета, депозиты, карточки и т.д.)
abstract class Banking {
	protected $strategyCalculate;		// объект стратегии вычисления
	
    public function setStrategyCalculate( StrategyCalculate $strategy ) {
        $this->strategyCalculate = $strategy;
		return $this;
    }
}

class Deposit extends Banking {
	private $deposit;
	public function __construct( $deposit ) { $this->deposit = $deposit; }	
	public function getDeposit() { return $this->deposit; }
	
	public function calculateDeposit( $value ) {
		$this->deposit = $this->strategyCalculate->calculate( $this->deposit, $value );
		return $this;
	}
}
class Account extends Banking {
	private $account;
	public function __construct( $account ) { $this->account = $account; }
	public function getAccount() { return $this->account; }
	
	public function calculateAccount( $value ) {
		$this->account = $this->strategyCalculate->calculate( $this->account, $value );
		return $this;
	}
}

$deposit = new Deposit( 1000 );
$deposit->setStrategyCalculate( new Add )->calculateDeposit( 300 );
$deposit->setStrategyCalculate( new Subtract )->calculateDeposit( 150 );
echo 'Депозит: '.$deposit->getDeposit().'<br>';

$account = new Account( 2000 );
$account->setStrategyCalculate( new Multiply )->calculateAccount( 3.5 )->calculateAccount( 11 );
echo 'Счёт: '.$account->getAccount().'<br>';

