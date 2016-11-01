<?php
/*
 * Это одна из разновидностей событийных паттернов. В каталоге отдельно такого паттерна нет
 */


// Электрические объекты
interface Electricity {
    function onElectricity();
}
class Lamp implements Electricity {  
    public function onElectricity() {
        echo 'Лампа включена<br>';
    }
}
class TVset implements Electricity {  
    public function onElectricity() {
        echo 'Телевизор включён<br>';
    }
}
class Radio implements Electricity {
    public function onElectricity() {
        echo 'Радио включено<br>';
    }
}

//////////////////// Event(событие) - включение электрических объектов   
// в данном примере я ипользовал SplObjectStorage вместо array, что не оправдано, потому что каждый ключ-объект хранит избыточную неиспользуемую инфу в хранилище
class Switcher {
    private $storageElectricity;   // хранилище электрических объектов
    
    public function __construct() {
        $this->storageElectricity = new SplObjectStorage();
    }
    // регистрация электрических устройств в объекте включения
    public function addStorageElectricity( Electricity $obj ) {
        if ( !$this->storageElectricity->contains($obj) )
            $this->storageElectricity->attach($obj);
        return $this;
    }
    public function removeStorageElectricity( Electricity $obj ) {
        if ( $this->storageElectricity->contains($obj) )
            $this->storageElectricity->detach($obj);
        return $this;
    }
    // запустить событие у всех объектов хранилища
    public function switchOn() {
        foreach ( $this->storageElectricity as $obj )
            $obj->onElectricity();  
    }
}

// электрические устройства
$lamp = new Lamp();
$tvset = new TVset();
$tvset2 = new TVset();
$radio = new Radio();

// объект события - включения электрич. устройств
$switcher = new Switcher();
// регистрируем электрич. устройства в объекте события
$switcher->addStorageElectricity($lamp)->addStorageElectricity($tvset)->addStorageElectricity($tvset2)->addStorageElectricity($radio);
$switcher->removeStorageElectricity($tvset);
// запускаем событие - включаем электрич. устройства
$switcher->switchOn();
