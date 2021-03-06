<?php
/*		
Логические исключения возникают в случае ошибки, которая была определена во время компиляции кода:
Это ошибки, которые подлежат исправлению в коде. Эти исключения генерятся вручную (с помощью throw) с использованием соответствующих функций проверки. 
Их нельзя отловить с помощью try-catch и они выдают одновременно 2 ошибки:
	- Fatal error: Uncaught exception...
	- Само исключение: InvalidArgumentException:...

    LogicException (extends Exception) - Исключение, которое представляет ошибку в логике программы.
        BadFunctionCallException	- Создается исключение, если обратный вызов относится к неопределенной функции или если некоторые аргументы отсутствуют. Часто применяется вместе с is_callable()
            BadMethodCallException	- Создается исключение, если обратный вызов относится к неопределенному методу или если некоторые аргументы отсутствуют. Часто применяется вместе с is_callable()
        DomainException				- Создается исключение, если значение не придерживается определенных действительных данных домена. 
        InvalidArgumentException	- Создается исключение, если аргумент не совпадает с ожидаемым значением. Часто применяется вместе с функциями проверки типа (is_int(), is_array() и т.д.)
        LengthException				- Создается исключение, если длина является недопустимой. 
        OutOfRangeException			- Создается исключение при запросе несуществующего индекса
		

Исключения Runtime возникают в случае ошибки, которая может быть найдена только во время исполнения кода: 
Исключения Runtime создаются автоматически (без throw, но можно и вручную с помощью throw) при возникновении каких-то ошибочных ситуаций в коде и их можно отлавливать с помощью try-catch
 
    RuntimeException (extends Exception) - Создается исключение в случае ошибки, которая может быть найдена только во время исполнения. 
        OutOfBoundsException	- Создается исключение, если значение не является действительным ключем. Это соответствует ошибкам, которые не могут быть обнаружены во время компиляции. 
        OverflowException		- Создается исключение при добавлении элемента в полный контейнер. 
        RangeException			- Генерируется исключение, чтобы указать ошибки диапазона во время исполнения программы. Как правило, это означает, что была арифметическая ошибка, отличная от потери значимости и переполнения. Это версия класса DomainException, доступная на этапе исполнения. 
        UnderflowException		- Создается исключение при попытке произвести недопустимую операцию над пустым контейнером. Например такую, как удаление элемента пустого контейнера. 
        UnexpectedValueException- Создается исключение, если значение не совпадает с набором значений. 
								  Обычно это происходит, когда функция вызывает другую функцию и ожидает, что возвращаемое значение будет определенного типа, или значение, не включая арифметические ошибки, или ошибки, связанные с буфером. 
*/

////////////////////////////////////// Логические исключения ///////////////////////////////////////
function tripleInteger($int) {
  if ( !is_int($int ))
    throw new InvalidArgumentException('tripleInteger function only accepts integers. Input was: '.$int);
  return $int * 3;
}

$x = tripleInteger(4); //$x == 12
//$x = tripleInteger('foo'); //exception will be thrown as 'foo' is a string

////////////////////////////////////////// Исключения Runtime //////////////////////////////////////

$array = new SplFixedArray(5);
try {

	$array[1] = 10;
	$array[10] = 'aaa';
} 
catch (RuntimeException $ex) {
	echo 'Cообшение исключения: '.$ex->getMessage().'<br>';
    echo 'Код исключения: '.$ex->getCode().'<br>';
    echo 'Файл, где выброшено исключение: '.$ex->getFile().'<br>';
    echo 'Строка, выбросившая исключениe: '.$ex->getLine().'<br>'; 
} 
echo'<hr>'; 

try {
	$a = 100;
	if ($a < 0 or $a > 10) 
		throw new RangeException ('Ошибка диапозона');
} 
catch (RangeException $ex) {
	echo 'Cообшение исключения: '.$ex->getMessage().'<br>';
    echo 'Код исключения: '.$ex->getCode().'<br>';
    echo 'Файл, где выброшено исключение: '.$ex->getFile().'<br>';
    echo 'Строка, выбросившая исключениe: '.$ex->getLine().'<br>'; 
} 
