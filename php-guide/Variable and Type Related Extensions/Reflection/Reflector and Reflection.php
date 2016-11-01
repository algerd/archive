<?php
/*
Reflector 
{
    abstract static string export ( void ) - экспорт сводной информации о переданном в метод элементе (подобно var_dump())
    abstract string __toString ( void )    - возвращает строковое представление объекта
}
Интерфейс Reflector - на его основе реализуются все экспортируемые Reflection-классы: 
    ReflectionClass, ReflectionMethod, ReflectionObject, ReflectionParameter, ReflectionProperty, ReflectionFunction, ReflectionExtension, ReflectionZendExtension
 
----------------------------------------------------------------------------------------------------
    
Reflection  
{
    static string export (Reflector $reflector [, bool $return = false ]) - форматирует и создаёт дамп данных (подобно var_dump()), содержащихся в объекте класса-наследника интерфейса Reflector(см. выше)
        Если параметр return установлен в TRUE, тогда экспортируемый объект будет возвращен как string, иначе будет возвращен NULL. 
    static array getModifierNames ( int $modifiers ) - Получение имен модификаторов (abstract, static, final)
}
Класс Reflection - класс, предоставляющий обзор (сводную информацию) по переданному ему объекту Reflector-классов.
*/
class User{
    private $name;
    public $age;
    public function __construct($n, $a){
        $this->name = $n;
        $this->age = $a;
    }
    public function getName(){
        return $this->name;
    }
}
echo '<pre>';
$reflection = new ReflectionClass('User');
Reflection::export($reflection);
//print_r(Reflection::getModifierNames(2));