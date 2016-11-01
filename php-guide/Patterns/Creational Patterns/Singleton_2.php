<?php
/*
 * Создаётся когда допустим только один экземпляр класса (ссылка самого класса). При этом на прямое создание объекта
 * этого класса через new Object() накладывается запрет путём private function __construct(). А создание единственного
 * экземпляра класса осуществляется через статич. метод класса Object::getInstance(). 
 * 
 */ 
//////////////////////////////// Класс-синглтон ////////////////////////////////////////////////////
class Config
{    
    static private $_instance = null;
    public $login;
	public $password;
	// закрываем конструктор
    // тело конструктора может быт непустым, но оно будет вызвано только 1 раз при создании единственного экземпляра класса в getInstance()
    private function __construct(){}    // возможность вызова только из getInstance 
    private function __clone(){}        // запрещаем клонирование 
    // инициализация объекта
    static function getInstance(){
        // проверка наличия экземпляра класса и при необходимости создание и возврат объекта
        if(self::$_instance == null){
            self::$_instance = new Config();
        }
        return self::$_instance;
    }
    // различные методы синглтона
    public function setLogin($login){ 
        $this->login = $login;
	}
	public function setPassword($password){
		$this->password = $password;
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////////
// пример использования свойств и методов синглтона
class Logon{
	private $config;
	private $user_login;
	private $user_password;
	function __construct($user_login, $user_password){
        //вызов singleton
        $this->config = Config::getInstance(); 
        $this->user_login = $user_login;
		$this->user_password = $user_password;
    }
    function Validate(){
        // в проверке вызываем свойства синглтона
        if( $this->config->login === $this->user_login and 
            $this->config->password === $this->user_password)
            print "Пользователь.<br>";
        else
            print "Мошенник!<br>";                                             
    }

}
// $obj = new Config(); //ошибка! из-за private function __construct()

// создаём экземпляр-синглтон - возвращется объект синглтона
$config = Config::getInstance();
// обращаемся к методам синглтона
$config->setLogin('root');
$config->setPassword('1234');

// пытаемся создать второй экземпляр-синглтон - возвращается тот же объект (singleton), что и в $config
$config2 = Config::getInstance(); 
echo 'свойства объекта $config: '.$config->login.'<br>';
echo 'свойства объекта $config2: '.$config2->login.'<br>'; // вернёт то же свойство что и в $config

// используем синглтон в других классах
$user1 = new Logon('root','1234');
$user1->Validate();

$user2=new Logon('admin','1234');
$user2->Validate();

?> 