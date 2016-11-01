<?php
/*
 * Часто в системе могут существовать сущности только в единственном экземпляре, например, система ведения системного журнала сообщений или драйвер дисплея.
 * В таких случаях необходимо уметь создавать единственный экземпляр некоторого типа, предоставлять к нему доступ извне и запрещать создание нескольких экземпляров того же типа.
 * Паттерн Singleton предоставляет такие возможности.
 * Главные особенности синглтона:
 *  - объект должен быть доступен для любого объекта в системе (реализовано через static)
 *  - объект не должен сохраняться в глобальной переменной (он хранится закрытом static свойстве класса)
 *  - в системе не должно быть больше одного объекта данного класса 
 */
class Preferences {
    private $props = array();
    private static $instance;

	private function __construct(){}  	// Защищаем от создания через new Singleton
    private function __clone(){}  		// Защищаем от создания через клонирование
    private function __wakeup(){}		// Защищаем от создания через unserialize

    public static function getInstance() {
        if ( empty( self::$instance ) ) {
            self::$instance = new Preferences();
        }
        return self::$instance;
    }

    public function setProperty( $key, $val ) {
        $this->props[$key] = $val;
    }

    public function getProperty( $key ) {
        return $this->props[$key];
    }
}


$pref = Preferences::getInstance();
$pref->setProperty( "name", "matt" );

unset( $pref ); // remove the reference

$pref2 = Preferences::getInstance();
print $pref2->getProperty( "name" ) ."\n"; // demonstrate value is not lost
?>
