<?php
/*
Паттерн Interpreter определяет грамматику простого языка для проблемной области ( создаёт свой мини-язык программирования для решения определённых задач). 
Он представляет грамматические правила в виде языковых предложений и интерпретирует их для решения задачи. 
Для представления каждого грамматического правила паттерн Interpreter использует отдельный класс. 
А так как грамматика, как правило, имеет иерархическую структуру, то иерархия наследования классов хорошо подходит для ее описания.

Абстрактный базовый класс определяет метод interpret(), принимающий (в качестве аргумента) текущее состояние языкового потока InterpreterContext. 
Каждый конкретный подкласс реализует метод interpret(), добавляя свой вклад в процесс решения проблемы.
 
Пример реализует действия логических выражений, напр :
    - выполнить логическое сравнение переменной $input (её значение) и какого-то литерального значения (строки или числа):
    ($input    ==     'four')    ||     ($input    ==       '4')
    (Variable Equals Literal) BooleanOr (Variable Equals Literal)
  
Context - это хранилище контекста выражения в массиве. Вот так будет разложено сложное выражение в массив контекста выражения      
    ($input          ==          'four')     ||    ($input          ==          '4')
    ([input]=>four [2]=>true  [1]=>four) [5]=>true ([input]=>four [4]=>false [3]=>4)
  
*/   
    

// Хранилище контекста выражения - языкового потока
class InterpreterContext {
    public $expressionstore = array();     // массив контекста выражения
  
    // сохраняет в массиве пару ключ/значение 
    function setExpressionstore( Expression $exp, $value ) {
        $this->expressionstore[$exp->getKey()] = $value;
    }
    
    function getExpressionstore( Expression $exp ) {
        return $this->expressionstore[$exp->getKey()];
    }
    function __destruct() {
        Expression::$keycount = 0;
    }
}

/////////////////// Абстракция интерпретируемых выражений
abstract class Expression {
    public static $keycount=0;  // автоинкремент ключа key
    private $key;               // ключ контекста выражения в хранилище выражений expressionstore класса Context
    // сохраняет пару ключ выражения/значение в массиве выражения expressionstore переданного объекта класса Context
    abstract function interpret( InterpreterContext $context );

    // получить числовой ключ для записи контекста выражения в массив выражения expressionstore класса Context
    function getKey() {
        if ( ! isset( $this->key ) ) {
            self::$keycount++;
            $this->key=self::$keycount;
        }
        return $this->key;
    }
}

// 'Правая' литеральная часть выражения - строка или число: 'четыре' или 4
class LiteralExpression extends Expression {
    private $value;
    function __construct( $value ) { $this->value = $value;}
    
    // сохраняет пару числовой ключ выражения/значение в хранилище expressionstore переданного объекта класса Context
    function interpret( InterpreterContext $context ) {
        $context->setExpressionstore( $this, $this->value );
    }
}

// 'Левая' часть выражения - имя переменной и её содержимое ($input, в которой может быть какое-то значение или null по умолчанию)
class VariableExpression extends Expression {
    private $name;      // имя переменной
    private $value;

    function __construct( $name, $val = null ) {
        $this->name = $name;
        $this->value = $val;
    }

    // сохраняет пару имя переменной/значение в хранилище expressionstore переданного объекта класса Context
    function interpret( InterpreterContext $context ) {
        if ( ! is_null( $this->value ) ) {
            $context->setExpressionstore( $this, $this->value );
            $this->value = null;
        }
    }
    // присвоить значение переменной
    function setValue( $value ) { $this->value = $value; }
    function getKey() { return $this->name; }
}

/////////////////////// Абстракция логических операторов
abstract class OperatorExpression extends Expression {
    protected $l_op;    // левая часть логического выражения. Она может быть простым контекстом (переменная VariableExpression или строка LiteralExpression) или сложным (другое логическое выражение)
    protected $r_op;    // правая часть логического выражения

    function __construct( Expression $l_op, Expression $r_op ) {
        $this->l_op = $l_op;
        $this->r_op = $r_op;
    }

    function interpret( InterpreterContext $context ) {
        // записать левую и правую части выражения в массив выражения объекта $context
        $this->l_op->interpret( $context );
        $this->r_op->interpret( $context );
        // извлечь левую и правую части выражения из массив выражения объекта $context
        $result_l = $context->getExpressionstore( $this->l_op );
        $result_r = $context->getExpressionstore( $this->r_op );
        // выполнить интерпретацию логического выражения над $result_l и $result_r с сохранением результата в массиве выражения класа Context
        $this->doInterpret( $context, $result_l, $result_r );
    }

    protected abstract function doInterpret( InterpreterContext $context, $result_l, $result_r );
}

// ==
class EqualsExpression extends OperatorExpression {
    protected function doInterpret( InterpreterContext $context, $result_l, $result_r ) {
            $context->setExpressionstore( $this, $result_l == $result_r );
    }
}
// ||
class BooleanOrExpression extends OperatorExpression {
    protected function doInterpret( InterpreterContext $context, $result_l, $result_r ) {
        $context->setExpressionstore( $this, $result_l || $result_r );
    }
}
// &&
class BooleanAndExpression extends OperatorExpression {
    protected function doInterpret( InterpreterContext $context, $result_l, $result_r ) {
        $context->setExpressionstore( $this, $result_l && $result_r );
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////////

// Реализовать выражение $statement = ($input == 'four') || ($input == '4')

$context = new InterpreterContext();                       // создаём хранилище контекста выражения
$variable = new VariableExpression( 'input' );  // создаём объект переменной (по умолчанию = null)
$variable->setValue( "4" );                     // присвоить значение переменной 'input'

// создаём объект - выражение ($input == 'four')||($input == '4')
$statement = new BooleanOrExpression( 
    new EqualsExpression( $variable, new LiteralExpression( 'four' ) ),
    new EqualsExpression( $variable, new LiteralExpression( '4' ) ) 
);
             
$statement->interpret( $context );      // сохранить выражение в массиве контекста выражения (разложить его)
print_r($context->expressionstore);     // разложенное выражение в массиве контекста выражения

// получем результат выражения $statement
if ( $context->getExpressionstore( $statement ) ) {
        print "<br>Значение совпало<br>";
    } else {
        print "<br>Значение не совпало<br>";
    }
unset ($context, $variable, $statement);
 


// Реализовать выражение $statement = ($var $$ 'four') == ($var || '4')  
$context = new InterpreterContext();    
$variable = new VariableExpression( 'var' );
$variable->setValue( "5" );
$statement = new EqualsExpression( 
    new BooleanAndExpression( $variable, new LiteralExpression( 'four' ) ),
    new BooleanOrExpression( $variable, new LiteralExpression( '4' ) ) 
); 
$statement->interpret( $context );
print_r($context->expressionstore);
// получем результат выражения $statement
if ( $context->getExpressionstore( $statement ) ) {
        print "<br>Значение совпало<br>";
    } else {
        print "<br>Значение не совпало<br>";
    }
unset ($context, $variable, $statement);
