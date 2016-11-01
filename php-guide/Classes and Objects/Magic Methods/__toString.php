<?php
/*
public string __toString ( void )

Метод __toString() позволяет классу решать самостоятельно, как он должен реагировать при преобразовании в строку. 
вызывается, когда к объекту обращаются как к строке (в том числе в echo, print,  printf() с модификатором %s).
*/

class TestClass
{
    public $foo;

    public function __construct ($foo) {
        $this->foo = $foo;
    }

    public function __toString () {
        return $this->foo;
    }
}

$class = new TestClass('Hello');

echo $class.' world!<br>';	// склеивает объект как строку с другой строкой	
		
?>