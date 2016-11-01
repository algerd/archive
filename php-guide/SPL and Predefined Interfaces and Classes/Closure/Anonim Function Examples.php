<?php

// 1. Пример присвоения анонимной функции переменной
 
    $adder = function($a, $b){
        return $a + $b;
    };
    $r1 = $adder(1, 2); // Вызываем безымянную функцию через переменную. r1=1+2;
    echo "1.Пример присвоения анонимной функции переменной r1=$r1<br>";


 // 2. Пример передачи анонимной функции в тело стандартной функции через аргумент функции
 
    function performAction($action, $a, $b) 
    {
            $result = $action($a,$b); //предполагается что параметр action - это функция
            return $result;
    }
    $r2 = performAction($adder,6,4); //Передаем функцию в другую функцию. r2=6+4;
    echo "2.Пример передачи анонимной функции в тело стандартной функции через аргумент функции r2=$r2";
    $r3 = performAction(function($a,$b){return $a*$b;} ,5,6); //То же самое прямо на лету. r3=5*6;
    echo " r3=$r3<br>";


 // 3. Пример встраивания анонимной функции в return стандартной

    function makeDivider() 
    {   
        //Возвращает безымянную функцию
        return function ($a,$b){ return $a/$b;};
    }
    $divider = makeDivider();   //Вызываем функцию, которая возвращает анонимную функцию в переменную
    $r4 = $divider(16,4);       //Вызываем встроенную анонимную функцию через переменную: r4=16/4;
    echo "3.Пример встраивания анонимной функции в return стандартной r4=$r4<br>";
    /* Этот вызов двойной вызов аналогичен js-вызову
    $r4 = makeDivider()(16,4);
    */

 // 4. Передача анонимной функции в качестве аргумента простой функции

    echo "4. Передача анонимной функции в качестве аргумента простой функции<br>";
    function say(Closure $a){
        $a();               // !!! вызов аргумента как функцию
        echo'John<br>';
    };
    
    $fun = function(){echo'Hello';};    // передаём анонимную функцию переменной $fun
    say($fun);                          // передаём в качестве аргумента анонимную функцию через переменную

    say(function(){echo'Привет';});      // передаём в качестве аргумента анонимную функцию напрямую  


 // 5. Замыкание - передача переменной из области видимости родителя в анонимную функцию через USE
 
    function get($x)
    {
        return function($a, $b) use($x) // use(&$x) - если надо использовать переменную значение переменной $x родителя, которая изменится далее, то делаем ссылку на переменную 
        {
           return $a + $b + $x;
        };
    }
    $tmp = get(5);      // Вызываем функцию с аргументом $x, которая возвращает анонимную функцию в переменную
    $a = $tmp(10,20);   // вызываем анонимную функцию с аргументами $a, $b
    echo "5. Замыкание - передача переменной из области видимости родителя в анонимную функцию через USE tmp=$a <br>";   

    // Замыкание через значение переменной
    $x = 51;
    $a = function() use($x){return $x;};
    echo "Первый вывод a()=".$a().'<br>';
    $x = 555;    
    echo "Второй вывод a()=".$a().'<br>';

    // Замыкание через ссылку переменной

    $x = 510;
    $a = function() use(&$x){return $x;};
    echo "Первый вывод a()=".$a().'<br>';
    $x = 5555;    
    echo "Второй вывод a()=".$a().'<br>';

//   6. Анонимные функции в методах классов

    class User
    {
        private $name;
        public function __construct($n){ $this->name = $n;}
        // вывод с использованием анонимной функции
        public function get($word)
        {
            $x = $word.$this->name;
            return function ($a) use($x){echo $x.$a;};
        }    
    }
    $user = new User('Alex'); 
    $tmp = $user->get('Привет');    // отрабатываем метод и записываем анонимную функцию в переменную   
    $tmp('!!!');                    // Вызываем анонимную фунццию
    echo '<br>';

 //  7. Вызов анонимной функции через магич. функцию __invoke()
 
    function getClosure()
    {
        $g = 'test';
        $c = function($a, $b) use($g){ echo $a . $b . $g;};
        $g = 'test2';       
        return $c;
    }
    echo 'Вызов анонимной функции через магич. функцию __invoke()<br>';
    $closure = getClosure();
    $closure(1, 3); //13test

    // Для того, чтобы вызывать объект Closure как функцию используют __invoke()
    // Доступ к анонимной функции(это объект Сlosure) будет осушествляться через __invoke()
    getClosure()->__invoke(1, 3); //13test
    echo '<br>';

//    8. Использование анонимных функций в callback-функциях - функциях обратного вызова 
/*
 * array_map - применяет callback-функцию ко всем элементам указанных массивов
 * array array_map ( callable $callback , array $arr1 [, array $... ] )
 * Функция array_map() возвращает массив, содержащий элементы arr1 после их обработки callback-функцией
 */
    echo 'Преобразование массива через array_map():<br>';
    $double = function($a){return $a * 2;};
    $numbers = array(1,2,3,4,5);
    // пропускаем каждый элемент массива $numbers в качестве аргумента $a через callback-функцию $double
    $new_numbers = array_map($double, $numbers);
    print_r($new_numbers);

    // Эта же запись короче
    $new_numbers = array_map(function($a){return $a * 2;}, [6,7,8,9,10]);
    print_r($new_numbers);
    echo '<br>';

/*
 * array_filter — Фильтрует элементы массива с помощью callback-функции
 * array array_filter ( array $input [, callable $callback = "" ] )
 * Обходит каждое значение массива input, передавая его в callback-функцию. Если callback-функция возвращает true, данное значение возвращается в массив input. Ключи массива сохраняются. 
 */
    echo 'Филтрация через array_filter():<br>';
    $filter = function($a){return $a > 2;};
    $arr = array(1,2,3,4,5);
    // фильтруем каждый элемент массива $arr в качестве аргумента $a через callback-функцию $filtr и возвращаем новый массив
    $new_arr = array_filter($arr, $filter);
    print_r($new_arr);

    // Эта же запись короче
    $new_arr = array_filter([6,7,8,9,10], function($a){return $a > 8;});
    print_r($new_arr);
    echo '<br>';

/*
 *  array_walk — Применяет пользовательскую функцию к каждому элементу массива
 *  bool array_walk ( array &$array , callable $funcname [, mixed $userdata = NULL ] )
 *  Применяет пользовательскую функцию funcname к каждому элементу массива array.
 *  В отличие от array_map он не возвращает массив а только выполняет действия над его элементами. 
 *  Если задать & ссылку на массив, то будет изменяться переданный массив
 */
    echo 'Действия над массивом через array_walk():<br>';
    $fruits = array("d" => "lemon", "a" => "orange", "b" => "banana", "c" => "apple");
    $fun = function(&$item, $key){ 
            echo "$key. $item<br />\n";
            $item = $item.'-new';
        };
    // пропускаем каждый элемент массива $fruits через callback-функцию $fun
    array_walk($fruits, $fun);
    print_r($fruits);
    
    // Эта же запись короче
    array_walk($fruits,  function($item, $key){ echo "$key. $item<br />\n";});
    echo '<br>';
/*
 * array_reduce — Итеративно уменьшает массив к единственному значению, используя callback-функцию
 * mixed array_reduce ( array $input , callable $function [, mixed $initial = NULL ] )
 * array_reduce() итеративно применяет callback-функцию function к элементам массива input и, таким образом, сводит массив к единственному значению.
 */
    echo 'array_reduce():<br>'; 
    $arr = array(1,2,3,4,5);

    $fun = function($v, $w)
    {
        $v += $w;
        return $v;
    };
    $b = array_reduce($arr, $fun);
    echo "$b<br>";       
        
/*
 *  В других встроенных функциях с аргументом - callback-функцией
 */     
    echo 'В других встроенных функциях с аргументом - callback-функцией<br>';
    $array = [1,2,3,4,5];
    //По старинке:
    function cmp($a, $b){ return($a > $b);}
    uasort($array, 'cmp'); //А тут использование этой функции
    //через анонимную функцию:
    uasort($array, function($a, $b) { return($a > $b);});
    print_r($array);
?>



