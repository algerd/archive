<?php
/*
 ReflectionClass (ReflectionObject) implements Reflector 
{
    const integer IS_IMPLICIT_ABSTRACT = 16 ;
    const integer IS_EXPLICIT_ABSTRACT = 32 ;
    const integer IS_FINAL = 64 ;
    public $name ;

    __construct ( mixed $argument )         - argument может принимать строку (string), содержащую имя исследуемого класса, либо объект (object). Для ReflectionObject только объект.
    string __toString ( void )              - Возвращает строковое представление объекта класса ReflectionClass
    static string export (mixed $argument [, bool $return = false]) - Экспортирует класс (сводную информацию)
    mixed getConstant ( string $name )      - Возвращает определенную константу
    array getConstants ( void )             - Возвращает константы
    ReflectionMethod getConstructor ( void )- Возвращает конструктор класса
    array getDefaultProperties ( void )     - Возвращает свойства по умолчанию
    string getDocComment ( void )           - Возвращает doc-блоки комментариев
    int getEndLine ( void )                 - Возвращает номер последней строки
    ReflectionExtension getExtension ( void ) - Возвращает объект класса ReflectionExtension для расширения, определенного в классе
    string getExtensionName ( void )        - Возвращает имя расширения, определенного в классе
    string getFileName ( void )             - Возвращает имя файла, в котором объявлен класс
    array getInterfaceNames ( void )        - Возвращает имена интерфейсов
    array getInterfaces ( void )            - Возвращает интерфейсы
    ReflectionMethod getMethod ( string $name ) - Возвращает экземпляр ReflectionMethod для метода класса
    array getMethods ([ string $filter ] )  - Возвращает список методов в виде массива объектов класса ReflectionMethod
    int getModifiers ( void )               - Возвращает информацию о модификаторах класса
    string getName ( void )                 - Возвращает имя класса 
    string getNamespaceName ( void )        - Возвращает название пространства имён
    object getParentClass ( void )          - Возвращает родительский класс
    array getProperties ([ int $filter ] )  - Возвращает свойства в виде массива объектов класса ReflectionProperty
    getProperty ( string $name )            - Возвращает экземпляр ReflectionProperty для свойства класса
    string getShortName ( void )            - Возвращает короткое имя
    int getStartLine ( void )               - Возвращает номер начальной строки
    array getStaticProperties ( void )      - Возвращает static свойства
    mixed getStaticPropertyValue ( string $name ) - Возвращает значение static свойства
    array getTraitAliases ( void )          - Возвращает массив trait-псевдонимов
    array getTraitNames ( void )            - Возвращает массив trait-имён, задействованных в этом классе
    array getTraits ( void )                - Возвращает массив traits, задействованных в этом классе
    bool hasConstant ( string $name )       - Проверяет, задана ли константа
    bool hasMethod ( string $name )         - Проверяет, задан ли метод
    bool hasProperty ( string $name )       - Проверяет, задано ли свойство
    bool implementsInterface ( string $interface ) - Проверяет, реализуется ли интерфейс
    bool inNamespace ( void )               - Проверяет, определён ли класс в пространстве имён
    bool isAbstract ( void )                - Проверяет, является ли класс абстрактным
    bool isCloneable ( void )               - Проверяет, можно ли клонировать этот класс
    bool isFinal ( void )                   - Проверяет, является ли класс окончательным (final)
    bool isInstance ( object $object )      - Проверяет, принадлежит ли объект классу
    bool isInstantiable ( void )            - Проверяет, можно ли создать экземпляр класса
    bool isInterface ( void )               - Проверяет, является ли класс интерфейсом
    bool isInternal ( void )                - Проверяет, является ли класс встроенным в расширение или в ядро
    bool isIterateable ( void )             - Проверяет, является ли класс итерируемым
    bool isSubclassOf ( string $class )     - Проверяет, является ли класс подклассом
    bool isTrait ( void )                   - Проверяет, является ли класс trait
    bool isUserDefined ( void )             - Проверяет, является ли класс пользовательским
    object newInstance ( mixed $args [, mixed $... ] ) -  Создаёт экземпляр класса с переданными аргументами
    object newInstanceArgs ([ array $args ] ) - Создаёт экземпляр класса с переданными параметрами
    object newInstanceWithoutConstructor ( void ) - Создаёт новый экземпляр класса без вызова конструктора
    void setStaticPropertyValue (string $name , string $value) - Устанавливает значение static-свойства
}
Класс ReflectionClass сообщает информацию о классе. 
Класс ReflectionObject сообщает информацию о объекте (о классе, которому принадлежит объект).
*/
//Reflection::export(new ReflectionClass('ReflectionClass'));
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
echo '<pre>';

trait A {}
trait B {}

interface MyInterface{}
class Object{}
class Counter extends Object implements MyInterface{
    use A, B;
    const START = 0;
    private static $c = Counter::START;
    public function count() {
        return self::$c++;
    }
}
ReflectionClass::export('Counter');
echo '<hr>';

// Создание экземпляра класса ReflectionClass
$class = new ReflectionClass('Counter');

// массивы объектов, к которым можно применять методы соответствующих классов в foreach
var_dump($class->getConstants());   // ассоциативный массив констант
var_dump($class->getProperties());  // массив объектов класса ReflectionProperty
var_dump($class->getMethods());     // массив объектов класса ReflectionMethod
var_dump($class->getTraits());      // массив объектов класса ReflectionClass
echo '<hr>';

// Вывод основной информации
printf(
    "===> %s%s%s %s '%s' [экземпляр класса %s]\n" .
    "     объявлен в %s\n" .
    "     строки с %d по %d\n" .
    "     имеет модификаторы %d [%s]\n",
        $class->isInternal() ? 'Встроенный' : 'Пользовательский',
        $class->isAbstract() ? ' абстрактный' : '',
        $class->isFinal() ? ' финальный' : '',
        $class->isInterface() ? 'интерфейс' : 'класс',
        $class->getName(),
        var_export($class->getParentClass(), 1),
        $class->getFileName(),
        $class->getStartLine(),
        $class->getEndline(),
        $class->getModifiers(),
        implode(' ', Reflection::getModifierNames($class->getModifiers()))
);

// Вывод тех интерфейсов, которые реализует этот класс
printf("---> Интерфейсы:\n %s\n", var_export($class->getInterfaces(), 1));

// Вывод констант класса
printf("---> Константы: %s\n", var_export($class->getConstants(), 1));

// Вывод свойств класса
printf("---> Свойства: %s\n", var_export($class->getProperties(), 1));

// Вывод методов класса
printf("---> Методы: %s\n", var_export($class->getMethods(), 1));

// Если есть возможность создать экземпляр класса, то создаем его
if ($class->isInstantiable()) {
    $counter = $class->newInstance();

	echo '---> Создан ли экземпляр класса '.$class->getName().'? ';
    echo $class->isInstance($counter) ? 'Да' : 'Нет';

    echo "\n---> Создан ли экземпляр класса Object()? ";
    echo $class->isInstance(new Object()) ? 'Да' : 'Нет';
}
?>