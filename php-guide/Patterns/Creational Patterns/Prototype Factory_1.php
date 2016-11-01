<?php
/*
 * Abstract Factory создаёт набор объектов какого-то определёенного семейства. Напр, для нашего следуюнго
 * примера Abstract Factory создавала бы для семейства Earth объекты классов EarthSea, EarthPlains, EarthForest,
 * а для семейства Mars - MarsSea, MarsPlains, MarsForest. И нельзя создавать в Abstract Factory новые смешанные семейства, напр NewTerra - c EarthSea и MarsPlains
 * (конечно и в Abstract Factory можно гибко варьировать фабриками для создания новых семейств, но это нарушит идеологию шаблона - фабрика объектов конкретного семейства).
 * Чтобы гибко работать с классами различных семейств, используют шаблон  Prototype Factory - шаблон создания какого-то нового семейства объектов или
 * заполнение объектами уже существующего семейства.
 * Prototype Factory по сравнению с Abstract Factory даёт гибкость, но при этом добавляются проблемы с клонированием свойств-объектов создаваемых объектов.
 * Кроме того, благодаря клонированию сохраняются в клонах значения свойств клонируемых объектов, что добавляет гибкость, но и накладывает ограничения.
 */

interface Terrain{}
abstract class Sea implements Terrain {}
abstract class Plains implements Terrain {}
abstract class Forest implements Terrain {}                                                 

// семейство классов Earth
interface Earth {}
class EarthSea extends Sea implements Earth{}
class EarthPlains extends Plains implements Earth{}
class EarthForest extends Forest implements Earth{}

// семейство классов Mars
interface Mars {}
class MarsSea extends Sea implements Mars {}
class MarsPlains extends Plains implements Mars {}
class MarsForest extends Forest implements Mars {}

// семейство классов Venus
interface Venus {}
class VenusSea extends Sea implements Venus {}
class VenusPlains extends Plains implements Venus {}
class VenusForest extends Forest implements Venus {}

/*
 * Если бы стояла задача создавать объекты только для существующих семейств Earth, Mars или Venus, то мы бы воспользовались Abstract Factory
 * Но нам требуется создать новое семейство:
 *   - NewTerra, в котором будут объекты классов разных семейств EarthSea - из Earth, MarsPlains - из Mars, VenusForest - из Venus
 */
// Фабрика прототипов для создания семейства объектов
class TerrainFactory {
    private $sea;
    private $forest;
    private $plains;

    function __construct( Sea $sea, Plains $plains, Forest $forest ) {
        $this->sea = $sea;
        $this->plains = $plains;
        $this->forest = $forest;
    }
	// возвращает прототип (клон) объекта $sea
    function getSea () {
        return clone $this->sea;
    }
	// возвращает прототип (клон) объекта $plains
    function getPlains () {
        return clone $this->plains;
    }// возвращает прототип (клон) объекта $forest
    function getForest () {
        return clone $this->forest;
    }
}

// создаём набор объектов для нашего нового виртуального семейства NewTerra;
$newTerra = new TerrainFactory( new EarthSea(), new MarsPlains(), new VenusForest() );
/* Что представляет собой объект фабрики $newTerra?
 * Это контейнер(или семейство newTerra) неизменяемых образцов объектов нового семейства объектов newTerra. К внутренним объектам 
 * $newTerra доступа нет и их изменить нельзя. Поэтому, если понадобится какой-то образец объекта этого семейства, 
 * не надо его создавать с нуля через фабрики или new, а достаточно через методы getОбразец() скопировать нужный объект.
 * При этом любые изменения скопированных объектов никак не изменят образцы объектов в контейнере семейства. 
 * Это хранилище образцов-прототипов объектов семейства newTerra!
 * Для строготипизированной коллекции семейств образцов (у нас это Earth, Mars и Venus) хранилище прототипов наполняют из
 * абстрактной фабрики семейств объектов: см. Abstract Factory - Factory of Prototypes.php
 */
echo 'Планета NewTerra: ';
print_r($newTerra->getSea());
print_r($newTerra->getPlains());
print_r($newTerra->getForest());
echo '<hr>';
// создаём набор объектов для уже существующего семейства Earth;
$earth = new TerrainFactory( new EarthSea(), new EarthPlains(), new EarthForest() );
echo 'Планета Earth: ';
print_r($earth->getSea());
print_r($earth->getPlains());
print_r($earth->getForest());

?>
