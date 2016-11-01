<?php
/*
Вообще генераторы можно применять во многих задачах. Одна из них — симуляция потоков. 
Сначала мы определяем каждый поток как генератор. Затем выбрасываем сигнал управления родителю, чтобы тот смог передать сигнал для работы следующему потоку.
Построим такую систему, которая работает с разными источниками данных (работаем с неблокирующим вводом-выводом). Вот пример такой системы:
*/
function step1() {
    $f = fopen("file.txt", 'r');
    while ($line = fgets($f)) {
        processLine($line);
        yield true;
    }
}
function step2() {
    $f = fopen("file2.txt", 'r');
    while ($line = fgets($f)) {
        processLine($line);
        yield true;
    }
}
function step3() {
    $f = fsockopen("www.example.com", 80);
    stream_set_blocking($f, false);
    $headers = "GET / HTTP/1.1\r\n";
    $headers .= "Host: www.example.com\r\n";
    $headers .= "Connection: Close\r\n\r\n";
    fwrite($f, $headers);
    $body = '';
    while (!feof($f)) {
        $body .= fread($f, 8192);
        yield true;
    }
    processBody($body);
}

// 3 потока (step) имеют схожий функционал - выбрасывают true, тем самым давая сигнал, что он еще занят

function runner(array $steps) {                    
    while (true) {                                      # снова бесконечный цикл, в котором перебираем потоки
        foreach ($steps as $key => $step) {  
             $step->next();                             # возобновляем работу потока с с момента последнего yield
             if (!$step->valid()) {                     # проверяем, завершился ли поток и завершаем (удаляем) его
                 unset($steps[$key]);
             }
        }
        if (empty($steps)) return;                      # если потоков нет - завершаем работу
    }
}
runner(array(step1(), step2(), step3()));
