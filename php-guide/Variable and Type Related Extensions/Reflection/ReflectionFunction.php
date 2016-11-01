<?php
/*
ReflectionFunction extends ReflectionFunctionAbstract implements Reflector 
{
    const integer IS_DEPRECATED = 262144 ;
    public $name ;

    __construct ( mixed $name )     - name - Имя функции
    static string export (string $name [, string $return]) - форматирует и создаёт дамп данных (подобно var_dump()) функции $name 
    Closure getClosure ( void )     - Возвращает динамически созданное замыкание для функции
    mixed invoke ([ mixed $parameter [, mixed $... ]]) - Вызывает функцию (запускает) с параметрами 
    mixed invokeArgs ( array $args )- Вызывает функцию и передает ей аргументы в виде массива
    bool isDisabled ( void )        - Проверяет, отключена ли функция,
    string __toString ( void )      - Представление в виде строки
        
  Унаследованные от ReflectionFunctionAbstract методы
    final private void __clone ( void )     - Копирует функцию. 
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
Класс ReflectionFunction сообщает информацию о функциях.
*/
//Reflection::export(new ReflectionClass('ReflectionFunction'));
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function sayHello($name, $h){
	static $count = 0;
	return "<h$h>Hello, $name</h$h>";
}
echo'<pre>';
// Обзор функции
ReflectionFunction::export('sayHello');
echo'<hr>';

// Создание экземпляра класса ReflectionFunction
$fun = new ReflectionFunction('sayHello');
var_dump($fun->getParameters());    // массив аргументов класса ReflectParameter, к которым можно применить его методы для вывода доп информации в foreach

// Вызов функции
printf("<p> Результат вызова: ");
$result = $fun->invoke("John","1");
echo $result;

$arr['isDisabled']		= $fun->isDisabled();
$arr['__toString']		= $fun->__toString();
$arr['getDocComment']	= $fun->getDocComment();
$arr['getStartLine']	= $fun->getStartLine();
$arr['getEndLine']		= $fun->getEndLine();
$arr['getExtension']	= $fun->getExtension();
$arr['getExtensionName']= $fun->getExtensionName();
$arr['getFileName']		= $fun->getFileName();
$arr['getName']			= $fun->getName();
$arr['getNamespaceName']= $fun->getNamespaceName();
$arr['getNumberOfParameters'] = $fun->getNumberOfParameters();
$arr['getParameters']	= $fun->getParameters();
$arr['getShortName']	= $fun->getShortName();
$arr['getStaticVariables'] = $fun->getStaticVariables();
$arr['inNamespace']		= $fun->inNamespace();
$arr['isClosure']		= $fun->isClosure();
$arr['isDeprecated']	= $fun->isDeprecated();
$arr['isGenerator']		= $fun->isGenerator();
$arr['isInternal']		= $fun->isInternal();
$arr['isUserDefined']	= $fun->isUserDefined();
$arr['returnsReference']= $fun->returnsReference();

var_dump($arr);

?>

