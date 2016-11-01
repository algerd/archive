<?php
/*
В случае создания своего дерева исключений, унаследованных от Exception, порядок расположения блоков
catch с объектом исключения должен быть от самого младшего наследника к самому старшему, чтобы родители 
не перехватывали исключения детей.
*/

// дерево исключений
class FirstException extends Exception {}
class SecondException extends FirstException {}

try {
    $x = 10;
    if ( !$x ) throw new SecondException( '- try SecondException' );
    if ( $x > 0 ) throw new FirstException( '- try FirstException' );
    if ( $x < 0 ) throw new Exception( '- try Exception' ); 
}
// самый младший SecondException    
catch ( SecondException  $ex ) {    
    echo 'catch SecondException'.$ex->getMessage().'<br>';
}    
// следующий по старшинству FirstException    
catch ( FirstException  $ex ) {    
    echo 'catch FirstException'.$ex->getMessage().'<br>';
}    
// самый старший Exception    
catch ( Exception  $ex ) {    
    echo 'catch Exception'.$ex->getMessage().'<br>';
}
echo '<hr>';


//////////////// Влженные исключения
class MyException extends Exception { }

class Test {
    public function testing() {
        try {
            try {
                throw new MyException('foo!');
            } catch (MyException $e) {
                // повторный выброс исключения
                throw $e;
            }
        } catch (Exception $e) {
            echo 'Вывод вложенного исключения: ';
            var_dump($e->getMessage());
        }
    }
}

$foo = new Test;
$foo->testing();