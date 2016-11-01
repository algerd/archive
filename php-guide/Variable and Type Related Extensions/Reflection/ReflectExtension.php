<?php
/*
 ReflectionExtension implements Reflector 
{
    public $name ;

    __construct ( string $name )
    final private void __clone ( void ) - клонирование закрыто  
    static string export (string $name [, string $return = false]) - экспорт расширения (сводной информации)
    array getClasses ( void )       - Возвращает массив объектов класса ReflectionClass
    array getClassNames ( void )    - Получение имен классов
    array getConstants ( void )     - Получение массива констант
    array getDependencies ( void )  - Получение зависимостей
    array getFunctions ( void )     - Возвращает массив объектов класса ReflectionFunction
    array getINIEntries ( void )    - Получение ini-настроек расширения
    string getName ( void )         - Получение имени расширения
    string getVersion ( void )      - Получение версии расширения
    void info ( void )              - Вывод информации о расширении
    void isPersistent ( void )      - Определяет, является ли расширение постоянным
    void isTemporary ( void )       - Определяет, является ли расширение временным
    string __toString ( void )      -  Преобразование в строку
}
Класс ReflectionExtension сообщает информацию о модулях. 
*/
echo '<pre>';
$ext = new ReflectionExtension('spl');

//var_dump($ext->getClasses());   // массив объектов класса ReflectionClass, к которым в foreach можно применить его методы
//var_dump($ext->getFunctions()); // массив объектов класса ReflectionFunctions, к которым в foreach можно применить его методы

// Вывод основной информации
printf(
    "Имя           : %s\n" .
    "Версия        : %s\n" .
    "Функции       : [%d] %s\n" .
    "Константы     : [%d] %s\n" .
    "Директивы INI : [%d] %s\n" .
    "Классы        : [%d] %s\n",
        $ext->getName(),
        $ext->getVersion() ? $ext->getVersion() : 'NO_VERSION',
        sizeof($ext->getFunctions()),
        var_export($ext->getFunctions(), 1),

        sizeof($ext->getConstants()),
        var_export($ext->getConstants(), 1),

        sizeof($ext->getINIEntries()),
        var_export($ext->getINIEntries(), 1),

        sizeof($ext->getClassNames()),
        var_export($ext->getClassNames(), 1)
);

