<?php
/*
ReflectionProperty implements Reflector 
{
    const integer IS_STATIC = 1 ;
    const integer IS_PUBLIC = 256 ;
    const integer IS_PROTECTED = 512 ;
    const integer IS_PRIVATE = 1024 ;
    public $name ;
    public $class ;

    final private void __clone ( void ) - закрыто клонирование
    __construct ( mixed $class , string $name ) - $class - класс, $name - свойство
    static string export (mixed $class , string $name [, bool $return]) - экспорт свойства (сводной информации)
    ReflectionClass getDeclaringClass ( void ) - Получение объявляющего класса
    string getDocComment ( void )       - Получение документируемого комментария
    int getModifiers ( void )           - Получение модификаторов
    string getName ( void )             - Получение имени свойства
    mixed getValue ( object $object )   - Получение значения
    bool isDefault ( void )             - Проверяет, является ли значение свойством по умолчанию
    bool isPrivate ( void )             - Проверяет, является ли свойство частным (private)
    bool isProtected ( void )           - Проверяет, является ли свойство защищенным (protected)
    bool isPublic ( void )              - Проверяет, является ли свойство общедоступным (public)
    bool isStatic ( void )              - Проверка, является ли свойство статическим
    void setAccessible ( bool $accessible ) - Задание доступности свойства
    void setValue ( object $object , mixed $value ) - Задание значения свойству
    string __toString ( void )          - Преобразование в строку
}
Класс ReflectionProperty сообщает информацию о свойствах класса. 
*/
class String
{
    public $length  = 5;
}

// Создание экземпляра класса ReflectionProperty
$prop = new ReflectionProperty('String', 'length');

// Вывод основной информации о свойстве класса
printf(
    "===> %s%s%s%s свойство '%s' (которое было %s)\n" .
    "     имеет модификаторы %s\n",
        $prop->isPublic() ? ' public' : '',
        $prop->isPrivate() ? ' private' : '',
        $prop->isProtected() ? ' protected' : '',
        $prop->isStatic() ? ' static' : '',
        $prop->getName(),
        $prop->isDefault() ? 'объявлено во время компиляции' : 'создано во время выполнения',
        var_export(Reflection::getModifierNames($prop->getModifiers()), 1)
);

// Создание экземпляра String
$obj = new String();

// Получение текущего значения
printf("---> Значение: ");
var_dump($prop->getValue($obj));

// Изменение значения
$prop->setValue($obj, 10);
printf("---> Установка значения 10, новое значение равно: ");
var_dump($prop->getValue($obj));

// Дамп объекта
var_dump($obj);

?>


