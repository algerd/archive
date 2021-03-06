<?php
/*
 * Decorator - используется если есть необходимость внести какие-то изменения в методе какого-то класса (декорируемого) с целью расширения или изменения его функциональности.
 * Это как-бы обёртка или модификатор метода класса.
 */
////////////////////////////////////////////////////////////////////////////////////////////////////
// Эскиз класса (как декорируемого, так и декоратора)
abstract class AbstrPage{
    abstract function viewPage();
}
// Декорируемый класс - главный или рабочий класс
class Page extends AbstrPage {
    function viewPage(){
        /* -> сюда должен внести изменение декоратор */
        echo 'страница';
        /* -> или сюда должен внести изменение декоратор */
    }
}

// эскиз декоратора - служит для загрузки объекта декорируемого класса чтобы облегчить код декоратора (сделать его идентичным коду декорируемого класса)
// эскиз можно не делать и сразу в декоратор загружать декорируемый объект
abstract class AbstrPageDecorator extends AbstrPage{
    private $_page;
    // помещаем объект в декоратор
    function __construct(AbstrPage $page){
        $this->_page = $page;
    }
    // перегружаем декорируемый метод
    function viewPage(){$this->_page->viewPage();}
}

// Декоратор - вспомогательный класс, дёргается только тогда, когда надо внести изменения в метод основного декорируемого класса
class PageDecorator extends AbstrPageDecorator{
    // перегружаем декорируемый метод
    function viewPage(){
        echo ' вспомогательная ';        /* -> сюда должен внести изменение декоратор */
        parent::viewPage();              //echo 'страница'; - здесь декорируемый метод
        echo ' декоратора. <br>';        /* -> или сюда должен внести изменение декоратор */
    }
}
////////////////////////////////////////////////////////////////////////////////////////////////////

// Используем главный класс
$obj = new Page();
echo '<br>Загружена главная ';
$obj->viewPage();

// Используем декоратор
$dec = new PageDecorator(new Page);
echo '<br>Загружена';
$dec->viewPage();

?>

