<?php
/*
 ReflectionMethod extends ReflectionFunctionAbstract implements Reflector 
{
    const integer IS_STATIC = 1 ;
    const integer IS_PUBLIC = 256 ;
    const integer IS_PROTECTED = 512 ;
    const integer IS_PRIVATE = 1024 ;
    const integer IS_ABSTRACT = 2 ;
    const integer IS_FINAL = 4 ;
    public $name ;
    public $class ;

    __construct ( mixed $class , string $name )
    static string export (string $class , string $name [, bool $return = false]) - Экспорт отраженного метода (сводной информации о методе)
    Closure getClosure ( string $object ) - Возвращает динамически созданное замыкание для метода  
    ReflectionClass getDeclaringClass ( void ) - Получает класс, объявляющий отображенный метод
    int getModifiers ( void )       - Получает модификаторы метода (abstract, static и т.д.)
    ReflectionMethod getPrototype ( void ) - Получает прототип метода (если такой есть)
    mixed invoke ( object $object [, mixed $parameter [, mixed $... ]]) - Вызов (запуск) метода
    mixed invokeArgs ( object $object , array $args ) - Вызов метода с передачей аргументов массивом
    bool isAbstract ( void )        - Проверяет, является ли метод абстрактным
    bool isConstructor ( void )     - Проверяет, является ли метод конструктором
    bool isDestructor ( void )      - Проверяет, является ли метод деструктором
    bool isFinal ( void )           - Проверяет, может ли метод иметь наследников (final)
    bool isPrivate ( void )         - Проверяет, является ли метод частным (private)
    bool isProtected ( void )       - Проверяет, является ли метод защищенным (protected)
    bool isPublic ( void )          - Проверяет, является ли метод общедоступным (public)
    bool isStatic ( void )          - Проверяет, является ли метод статическим
    void setAccessible ( bool $accessible ) - Делает метод доступным
    string __toString ( void )      - Возвращает строковое представление объекта Reflection method
        
  Унаследованные от ReflectionFunctionAbstract методы
    final private void __clone ( void )     - Копирует функцию - закрыто. 
    ReflectionClass getClosureScopeClass (void) - Returns the scope associated to the closure
    object getClosureThis ( void )   - Возвращает указатель this замыкания
    string getDocComment ( void )    - Получает документируемый комментарий из описания функции. 
    int getEndLine ( void )          - Получает номер строки завершения описания функции
    ReflectionExtension getExtension ( void ) - Получение информации о расширении, в состав которого входит функция
    string getExtensionName ( void ) - Получение имени расширения, к которому относится эта функция
    string getFileName ( void )      - Получает имя файла из определенной пользователем функции. 
    string getName ( void )          - Получение имени функции. 
    string getNamespaceName ( void ) - Получение имени пространства имен, в котором определен класс. 
    int getNumberOfParameters ( void ) - Получение количества аргументов в определении функции как обязательных, так и опциональных. 
    int getNumberOfRequiredParameters ( void ) - Получение числа обязательных аргументов в определении функции. 
    array getParameters ( void )     - Получение аргументов в виде массива объектов ReflectionParameter. 
    string getShortName ( void )     - Получение короткого имени функции (без указания пространства имен). 
    int getStartLine ( void )        - Получает начальный номер строки
    array getStaticVariables ( void )- Получение статических переменных.
    bool inNamespace ( void )        - Проверяет, находится ли функция в пространстве имен
    bool isClosure ( void )          - Проверяет, является ли функция замыканием
    bool isDeprecated ( void )       - Проверяет, является ли функция устаревшей
    bool isGenerator ( void )        - Returns whether this function is a generator
    bool isInternal ( void )         - Проверяет, является ли функция внутренней, то есть не определенной пользователем. 
    bool isUserDefined ( void )      - Проверяет, является ли функция определенной пользователем, то есть это не внутренняя функция. 
    bool returnsReference ( void )   - Проверяет, что функция возвращает ссылку  
}
Класс ReflectionMethod сообщает информацию о методах. 
*/
echo '<pre>';
class Counter{
    private static $c = 0;
    final public static function increment(){
        return ++self::$c;
    }
    protected function get($a){
        return $a;
    }
}
ReflectionMethod::export('Counter', 'increment');
echo '<hr>';

// вызов объектов методов ReflectionMethod из класса
$reflect = new ReflectionClass('Counter');
// вызов массива объектов - методов класса
foreach ($reflect->getMethods() as $method) {
    // Вывод основной информации
    printf(
        "===> %s%s%s%s%s%s%s метод '%s' (который является %s)\n" .
        "     объявлен в %s\n" .
        "     строки с %d по %d\n" .
        "     имеет модификаторы %d[%s]\n",
            $method->isInternal() ? 'Встроенный' : 'Пользовательский',
            $method->isAbstract() ? ' абстрактный' : '',
            $method->isFinal() ? ' финальный' : '',
            $method->isPublic() ? ' public' : '',
            $method->isPrivate() ? ' private' : '',
            $method->isProtected() ? ' protected' : '',
            $method->isStatic() ? ' статический' : '',
            $method->getName(),
            $method->isConstructor() ? 'конструктором' : 'обычным методом',
            $method->getFileName(),
            $method->getStartLine(),
            $method->getEndline(),
            $method->getModifiers(),
            implode(' ', Reflection::getModifierNames($method->getModifiers()))
    );
    echo '<br>';
}

// Создание экземпляра класса ReflectionMethod
$method = new ReflectionMethod('Counter', 'increment');

// Вывод статических переменных, если они есть
if ($statics= $method->getStaticVariables()) {
    printf("---> Статическая переменная: %s\n", var_export($statics, 1));
}

// Вызов метода
printf("---> Результат вызова: ");
$result = $method->invoke(null);
echo $result;






