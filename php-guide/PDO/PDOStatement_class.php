<?php
/*
Представляет подготовленный запрос к базе данных, а после выполнения запроса соответствующий результирующий набор.

PDOStatement implements Traversable 
{
    // Свойства
    readonly string $queryString;
    
    // Служебные методы
    bool setAttribute ( int $attribute , mixed $value ) - для mysql-драйвера нет аттрибутов
    mixed getAttribute ( int $attribute )  - для mysql-драйвера нет аттрибутов  
    string errorCode ( void )
    array errorInfo ( void ) 
    bool debugDumpParams ( void )    
    
    // Методы запроса 
    bool execute ([ array $input_parameters ] )  
    bool bindColumn ( mixed $column , mixed &$param [, int $type [, int $maxlen [, mixed $driverdata ]]] )
    bool bindParam ( mixed $parameter , mixed &$variable [, int $data_type = PDO::PARAM_STR [, int $length [, mixed $driver_options ]]] )
    bool bindValue ( mixed $parameter , mixed $value [, int $data_type = PDO::PARAM_STR ] ) 
    mixed fetch ([ int $fetch_style [, int $cursor_orientation = PDO::FETCH_ORI_NEXT [, int $cursor_offset = 0 ]]] )
    array fetchAll ([ int $fetch_style [, mixed $fetch_argument [, array $ctor_args = array() ]]] )
    string fetchColumn ([ int $column_number = 0 ] )
    mixed fetchObject ([ string $class_name = "stdClass" [, array $ctor_args ]] ) 
    bool setFetchMode ( int $mode )
    bool closeCursor ( void ) - освобождает память от результата предыдущего SQL-запроса. Использовать после выборок из хранимых процедур???
  
    // Дополнительные плюшки
    int columnCount ( void ) - Возвращает количество столбцов в результирующем наборе
    array getColumnMeta ( int $column ) - Возвращает ассоциативный массив, содержащий следующие значения метаданных столбца
    bool nextRowset ( void ) - Извлечение данных из нескольких наборов строк, возвращенных хранимой процедурой
    int rowCount ( void )  - Возвращает количество строк, модифицированных последним SQL запросом  
}

errorCode() - Определяет SQLSTATE код соответствующий последней операции объекта PDOStatement.
    Вместо него использовать PDOException (см. PDO_error)
errorInfo() - Получение расширенной информации об ошибке, произошедшей в результате работы объекта PDOStatement.
    Вместо него использовать PDOException (см. PDO_error)
debugDumpParams() - Вывод информации в виде массива параметров о подготовленной SQL команде в целях отладки
setAttribute() - Задает имя курсора (PDO::ATTR_CURSOR_NAME (Firebird и ODBC))
getAttribute() - Получение имени курсора (PDO::ATTR_CURSOR_NAME (Firebird и ODBC))   
*/ 
$reflection = new ReflectionClass('PDOStatement');
var_dump($reflection->getConstants());
var_dump($reflection->getProperties());
var_dump($reflection->getMethods());

    
    