<?php
/*
    Такой способо получения свойств объектов предотвращает несанкционированные их изменения. 
    Как правило свойства таких классов создаются 1 раз через конструктор и не меняются. Это будут уникальные
    свойства объекта, их можно только просматривать, но не менять
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
    // вызов свойства по имени метода
    public function getName(){return $this->name;}
    // вызов свойства по имени метода
    public function getEmail(){return $this->email;}
    // вызов свойства по названию свойства в аргументе метода
    public function getAge(){return $this->age;}
    // вызов свойства по названию свойства в аргументе метода    
}

$obj = new User('Alex','qqq@mail.ru',30);
echo "User: {$obj->getName()}<br>";
echo "User: {$obj->getEmail()}<br>";
echo "User: {$obj->getAge()}";

?>