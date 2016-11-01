<?php
/*
Как же оно работает? 
Когда мы вызываем функцию fun() c yield, PHP выполнит код до первой встречи ключевого слова yield, на котором он запомнит это значение и вернет генератор - объект класса Generator. 
Затем, будет вызов метода next() у генератора (который описан нами или итератором), PHP снова выполнит код, только начнет его не с самого начала,
а начиная с прошлого значения, которое мы благополучно выбросили и забыли о нем, и опять, до следующего yield или же конца функции, или return
(yield как бы ставит на паузу выполнение функции, полностью сохраняя её состояние - асинхронный режим работы) : 
*/
function getCount() {
	$count = 1;
	yield $count;			// при первом проходе doStuff() в цикле возвращает object(Generator)[1], переводит внутренний итератора объекта на следующую позицю и при следующем вызове doStuff() в цикле продолжает код c этого yield до следующнго yield (или return)                                     
	while ( $count < 10 ) { // при невыполнении условия происходит простой выход с функции без передачи генератора - внешний цикл итератора (foreach) завершается                                     
		$count++;
		yield $count;		// возвращает object(Generator)[$count], переводит внутренний итератора объекта на следующую позицю и при следующем вызове doStuff() в цикле продолжает код c этого yield до следующнго yield (или return)
	}
}

var_dump( getCount() );		// объект класса Generator: object(Generator)[1]
var_dump( getCount() );		// такой же объект класса Generator: object(Generator)[1], потому что не было перевода позиции внутреннего итератора объекта в цикле

$generator = getCount();		// объект класса Generator (он же Iterator)
// теперь в foreach запустим итератор (можно было напрямую: count() as $v)
foreach ( $generator as $v ) {
	var_dump( $v );			// возвращает 1,2,3,4,5,6,7,8,9,10
}

//////////// Проверка на закрытие генератора
function get() {
	for ($i=1; $i<5; $i++)
		yield $i;
}

$itgenerator = get();

while ($itgenerator->valid()) {
	echo $itgenerator->current().' /';
	$itgenerator->next();
}

// пытаемся повторно итерировать генератор
try {	
	foreach ($itgenerator as $v) // throw ( Exception $exception ) выбрасывает исключение
		echo $v.' /';
} catch(Exception $e) {
	echo $e->getMessage();	// Cannot traverse an already closed generator
}
	

