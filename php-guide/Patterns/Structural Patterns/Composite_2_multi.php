<?php
/*
В этом примере древовидная структура содержит неоднородные композиты:
- примитивные объекты : Infantryman, Archer, Cavalry, Elephant
- композитные объекты AllUnit, которые могут содержать любые объекты
- композитные объекты HeavyUnit, которые могут содержать Cavalry, Elephant и самого себя
- композитные объекты LightUnit, которые могут содержать Infantryman, Archer и самого себя    
Такая структура создаётся с помощью интерфейсов, которые указывают на связь определённых примитивов с 
композитами. Эта связь проверяется в методах, где нужно определить конкретную принадлежность объекта к композиту.   
*/   

class UnitException extends Exception { }

// Задаём структуру армии (дерева) с помощью интерфейсов
interface IArmy {}
interface IHeavyUnit extends IArmy { }
interface ILightUnit extends IArmy { }

abstract class Army implements IArmy {
    // добавить отряд к примитивному объекту - создаётся общий отряд и в него добавляются 2 других 
	function addUnit( Army $unit ) {    
        return  (new AllUnit())->addUnit( $this )->addUnit( $unit );
	}   
    abstract function getForceUnit();   // сила отряда
}

/////////////////// Композитные объекты /////////////////////////////
abstract class CompositeUnit extends Army {
    
    private $arrUnit = array();       //массив объектов - отрядов
	// добавление объектов армии в общий отряд
    public function addUnit( Army $unit ) {
        if ( !in_array( $unit, $this->arrUnit, true ) )
            $this->arrUnit[] = $unit;  
        return $this;
    }
    // удаление объектов из отряда   
    public function deleteUnit( Army $unit ) {  
        foreach ($this->arrUnit as $key => $object) {
            if ($object === $unit) unset($this->arrUnit[$key]);
        }          
        return $this;
    }
    public function getArrUnit() {
        return $this->arrUnit;
    }
    // сила отряда - рекурсивный подсчёт
    public function getForceUnit() {
        $force = 0;
        foreach( $this->arrUnit as $object ) {
            $force += $object->getForceUnit();
        }
        return $force;
    }
}
// отряд (любые единицы: тяжёлые отряды, лёгкие отряды или примитивные юниты)
class AllUnit extends CompositeUnit {}

// тяжёлый отряд
class HeavyUnit extends CompositeUnit implements IHeavyUnit {
	// добавление объектов армии в тяжёлый отряд
    public function addUnit( Army $unit ) {
        if ( $unit instanceof IHeavyUnit ) parent::addUnit( $unit );
		else throw new UnitException ('Нельзя помещать это подразделение в HeavyUnit-отряд');
        return $this;
    }   
		
}
// лёгкий отряд
class LightUnit extends CompositeUnit implements ILightUnit {
	// добавление объектов армии в лёгкий отряд
    public function addUnit( Army $unit ) {
        if ( $unit instanceof ILightUnit ) parent::addUnit( $unit ); 
		else throw new UnitException ('Нельзя помещать это подразделение в LightUnit-отряд');
        return $this;
    } 
}

/////////////// Примитивные объекты ///////////////////////////////////////////
class Infantryman extends Army implements ILightUnit { function getForceUnit(){ return 10; } }
class Archer extends Army implements ILightUnit { function getForceUnit(){ return 8; } }
class Cavalry extends Army implements IHeavyUnit { function getForceUnit(){ return 15; } }
class Elephant extends Army  implements IHeavyUnit { function getForceUnit(){ return 25; } }

///////////////////////////// Client //////////////////////////////////////////
echo '<pre>';
try {

    $unit = new Infantryman();
    $composLight = ( new LightUnit() )->addUnit( new Archer )->addUnit( $unit )->addUnit( $unit );
    $composHeavy = ( new HeavyUnit() )->addUnit( new Cavalry() )->addUnit( new Elephant());
    //$composHeavy->addUnit( new Archer() );  // выдаст исключение - Нельзя помещать это подразделение в HeavyUnit-отряд

    $compos = ( new AllUnit() )->addUnit( new Archer() )->addUnit( $composHeavy )->addUnit( $composLight ); 
    //$compos->deleteUnit( $composLight );
    $addPrimitive = $unit->addUnit( $composLight );
   
    echo 'getForceUnit: '.$compos->getForceUnit().'<br>';
    print_r( $compos );
    echo 'getForceUnit: '.$addPrimitive->getForceUnit().'<br>';
    print_r( $addPrimitive );
    
} catch( UnitException $e ) {
    echo $e->getMessage().'<br>';
}
?>

