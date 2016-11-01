<?php
/*
Coroutines - это дополнительная возможность передавать генератору какие-то значения. 
Фактически Coroutines осуществляют обратную передачу данных в yield.   
Это делает из привычного монолога генератора полноценный диалог, где вызывающая сторона может также что-то сообщать генератору.
      
 yield - это двунаправленная команда: 
	- подобная return, возвращает объект класса Generator со значением current()
	- отдаёт присваемаемой yield переменной значение в $gen->send(значение). Если ничего не передаётся, то $gen->send() возвращает в переменную null
*/

// Чистый Coroutines с обратной передачей данных в yield (в генератор)
function printer() {
    while (true) {
        echo yield . "<br>";    // yield  отдаёт значение $v из Generanor::send($v) (null усли ничего не передавалось)
    }
}

$printer = printer();
$printer->send('Foo');
$printer->send('Bar');
echo '<hr>'; 


// Двунаправленная передача данных в yield и обратно
function gen() {
    $ret = (yield 'yield1');
    var_dump($ret);
    $ret = (yield 'yield2');
    var_dump($ret);
}

$gen = gen();
var_dump($gen->current());    // string(6) "yield1"
var_dump($gen->send('ret1')); // string(4) "ret1"   (the first var_dump in gen)
                              // string(6) "yield2" (the var_dump of the ->send() return value)
var_dump($gen->send('ret2')); // string(4) "ret2"   (again from within gen)
                              // NULL               (the return value of ->send())
echo '<hr>';


// Generator c Coroutines - с передачей данных в yield и обратно
function getSetYield() {
	$count = 0;
	while ( true ) {
		$count++;
		// для двунаправленной передачи надо yield писать в скобках с передающей переменной
		$input = (yield $count);	// yield передаёт наружу object(Generator)[$count] и возвращает в $input значение $v из $gen->send($v)
		//var_dump($input);		
		echo 'yield_inner: '.$count.'<br>';
		if ($input == -1) {
			echo 'yield_inner: stop';
			return;
		}	
	}	
}

$gen = getSetYield();
foreach ( $gen as $v ) {
	echo 'yield_outer: '.$v.'<br>';
	if ($v == 5)
		$gen->send(-1);		// передаём в yield -1
}
echo '<hr>'; 


