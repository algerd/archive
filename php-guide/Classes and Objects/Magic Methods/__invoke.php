<?php

/*
   __invoke() - используется, когда надо обращаться к объекту как функции.
	mixed __invoke ([ $... ] )
	Метод __invoke() вызывается, когда скрипт пытается выполнить объект как функцию.
 */

class User
{
    private $name;
    private $email;
    private $age;

    public function __construct($n, $e, $a)
    {
       // инициализация свойств
       $this->name = $n; 
       $this->email = $e;    
       $this->age = $a;   
    }
    // метод, который будет вызван при обращении к объекту этого класса как к функции
    public function __invoke($arg)
    {
        switch ($arg)
        {
            case 'name': return $this->name;
            case 'email': return $this->email;    
            case 'age': return $this->age;    
        }      
    }
}

$user = new User('Alex', 'yyt@hjj.jk', '30');
// вызываем объект $user как функцию $user('') и передаём ей аргумент
echo "имя={$user('name')} почта={$user('email')} возраст={$user('age')}";
echo'<hr>';
// это аналогично вызову $user->__invoke('name')
echo $user->__invoke('name');
?>