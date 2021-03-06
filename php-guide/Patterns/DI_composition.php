<?php
/*
 * Dependency Injection (DI) - это общее представление агреггации (см. файл aggregation.php) и композиции (composition).
 * Cуть в том, что один объект(контейнер-обработчик) использует внутри себя другой объект(его свойства и методы)
 * При этом один объект может принудительно передаваться в другой (как в файле aggregation.php) через аргумент конструктора, 
 * а может создаваться внутри другого объекта (внутри конструктора) - чистая инъекция или композиция (composition). 
 *  Во втором случае объект-контейнер используется только для одного объекта - самого класса передаваемого объекта.
 * Иными словами. В первом случае Петя и Вася каждый со своими свойствами - объекты класса Человек, могли быть помещены и в Трактор и в Самолёт, 
 * то во втором случае только один абстрактный объект-класс Человек без свойств(т.к. невозможно их передать объекту внутри конструктора контейнера) 
 * может быть помещён и в Трактор и в Самолёт. Это ипользуется, когда надо вынести общие методы нескольких классов в один класс-контроллер, 
 * а потом их использовать в этих классах.
 *  composition аналогичен наследованию, но в отличие от наследования методов через extends композиция осуществляет множественное наследование, этакий костыль.
 *  наследника и одновременно с extends.
 */
//////////////////////////////////////////// Aggregation ///////////////////////////////////////////
// более подробно в aggregation.php

class UserA {
    private $name;
    function __construct($n){$this->name = $n;}
    function getName(){return $this->name;}  
}
// контейнер-обработчик
class CatalogA{
    private $obg;
    function __construct(UserA $var){
        $this->obj = $var;
    }
    public function getUser(){return $this->obj->getName();}
}
// помещаем ОДИН ОБЪЕКТ со свими свойствами В ДРУГОЙ ОБЪЕКТ-контейнер 
$objCat = new CatalogA (new UserA('Alex'));
echo $objCat->getUser().'<br>';

////////////////////////////////////// Composition /////////////////////////////////////////////////
/*
 *  Пример из жизни. Есть 4 класса Гитарист, Вокал, Клавишник, Ударник и есть класс Ансамбль. При создании
 * объекта Ансамбль внутри его создаются 4 объекта Гитарист, Вокал, Клавишник и Ударник. И теперь объекь Ансамбля
 * образно говоря сможет петь - выполнять свои методы с использованием объектов Гитарист, Вокал, Клавишник и Ударник.
 * Классы-контроллеры можно создавать со свойствами и конструктором, 
 * но тогда прийдётся задавать их свойства через конструктор контейнера, что существенно запутает код и
 * нарушит идеологию Composition. В таком случае лучше применять Aggregation и передавать в контейнер извне готовые 
 * объекты: Гитариста, Вокала, Клавишника и Ударника в Ансамбль.
 */
// 
class Contr1 {
    function getSay(){echo 'Hello ';}  
}
class Contr2 {
    function getHello(){echo 'Alex and ';}  
}
class Contr3 {
    function getEnd(){echo '!';}  
}

// контейнер-обработчик
// он может содержать и свои свойства и методы, которые будут использовать методы встраиваемых контроллеров (желательно) 
class Catalog{
    private $obg1;
    private $obg2;
    private $obg3;
    private $name;
    
    function __construct($n){
        // Инициализируем классы-контроллеры
        // В отличие от наследования здесь мы получаем методы сразу от трёх классов - реализуем множественное наследование
        // помещаем объекты-классы в контейнере - делегируем их методы этому классу через обращение к соответствующим свойствам ($this->obg1->..., $this->obg2->..., $this->obg3->...)
        $this->obg1 = new Contr1();
        $this->obg2 = new Contr2();
        $this->obj3 = new Contr3();
        $this->name = $n;
    }
    public function getName(){return $this->name;}
    // используем методы контроллеров и внутренние методы
    public function getWord(){
        $a = $this->obg1->getSay().$this->obg2->getHello().$this->getName().$this->obj3->getEnd();
        return $a;
    }
}

// создаём объект класса-контейнера(или обработчика)
$obj = new Catalog('John');
echo $obj->getWord();


?>