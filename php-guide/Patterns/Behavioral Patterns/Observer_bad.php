<?php
/*
Данный пример показывает неправильность реализации Observer, когда из одного класса ведётся сразу
несколько независимых наблюдений, что усложняет применение и модификацию кода.
    Рефакторинг данного класса был проведён в Observer_2.php, в результате которого получили разделение, независимость классов,
    удобство применения, модификации и добавления новых элементов. 
*/
interface Observable { }

// Класс предоставляет наблюдение за 3 своими свойствами - temperature, humidity, pressure, что нарушает сразу несколько принципов ООП!!!
class MeteoStation implements Observable{
    private $observersTemp = [];     // массив объектов наблюдателей за temperature
    private $observersHum = [];      // массив объектов наблюдателей humidity
    private $observersPress = [];    // массив объектов наблюдателей pressure
    private $temperature = 0;
    private $humidity = 0;
    private $pressure = 0;
    
    public function __construct () {}
    public function getTemperature() { return $this->temperature; }
    public function getHumidity() { return $this->humidity; }
    public function getPressure() { return $this->pressure; } 
    
    // методы изменения свойств и вызова метода передачи информации об изменении свойств наблюдателям
    public function setTemperature ($temp) { 
        if ($this->temperature != $temp){
            $this->temperature = $temp;
            $this->notifyTemp();
        }
    }
    public function setHumidity ($hum) { 
        if ($this->humidity != $hum){
            $this->humidity = $hum;
            $this->notifyHum();
        }
    }
    public function setPressure ($press) { 
        if ($this->pressure != $press){
            $this->pressure = $press;
            $this->notifyPress();
        }
    }

    // Методы добавления и удаления наблюдателей за определёнными свойствами 
    public function attachObserversTemp( Observer $observer ) {
        $this->observersTemp[] = $observer;
    }
    public function attachObserversHum(Observer $observer) {
        $this->observersHum[] = $observer;
    }
    public function attachObserversPress(Observer $observer) {
        $this->observersPress[] = $observer;
    }
    public function detachObserversTemp( Observer $observer ) {
        if ( $index = array_search( $observer, $this->observersTemp ) ) unset( $this->observersTemp[$index] );
    }
    public function detachObserversHum(Observer $observer) {
        if ( $index = array_search( $observer, $this->observersHum ) ) unset( $this->observersHum[$index] );
    }
    public function detachObserversPress(Observer $observer) {
        if ( $index = array_search( $observer, $this->observersPress ) ) unset( $this->observersPress[$index] );
    }
    
    // методы оповещения наблюдателей об изменениях в свойствах
    public function notifyTemp() {
        foreach ($this->observersTemp as $observer) {
            $observer->update($this);
        }
    }
    public function notifyHum() {
        foreach ($this->observersHum as $observer) {
            $observer->update($this);
        }     
    }
    public function notifyPress() {
        foreach ($this->observersPress as $observer) {
            $observer->update($this);
        }
    }
}

////////// Наблюдатели 
interface Observer { 
    function update( Observable $obj );
}

abstract class Display implements Observer {
    //abstract function display();  // ??? FATAL ERROR
}
class CurrentTemperatureDisplay extends Display {
    private $temperature = 0;
    public function setTemperature($temp) {
        $this->temperature = $temp;
    }
    // реакция наблюдателя на оповещение о изменениии температуры
    public function update ( Observable $observable ) {
        $this->setTemperature( $observable->getTemperature() );
        $this->display();
    }
    public function display () {
        echo 'Текущая температура воздуха: '.$this->temperature.'<br>';
    }
}
class CurrentHumidityDisplay extends Display  {
    private $humidity = 0;
    public function setHumidity($hum) {
        $this->humidity = $hum;
    }
    // реакция наблюдателя на оповещение о изменениии влажности 
    public function update ( Observable $observable ) {
        $this->setHumidity($observable->getHumidity());
        $this->display();
    }
    public function display () {
        echo 'Текущая влажность воздуха: '.$this->humidity.'<br>';
    }
}
class CurrentPressureDisplay extends Display {
    private $pressure = 0;
    public function setPressure($press) {
        $this->pressure = $press;
    }
    // реакция наблюдателя на оповещение о изменениии давления
    public function update ( Observable $observable ) {
        $this->setPressure($observable->getPressure());
        $this->display();
    }
    public function display () {
        echo 'Текущее давление воздуха: '.$this->pressure.'<br>';
    }
}
class MainDisplay extends Display{
    private $tempDisplay;
    private $humDisplay;
    private $pressDisplay;
    
    public function __construct() {
        $this->tempDisplay = new CurrentTemperatureDisplay();
        $this->humDisplay = new CurrentHumidityDisplay();
        $this->pressDisplay = new CurrentPressureDisplay();    
    }
    // реакция наблюдателя на оповещение о изменениии температуры, влажности и давления
    public function update ( Observable $observable ) {
        $this->tempDisplay->setTemperature($observable->getTemperature());
        $this->humDisplay->setHumidity($observable->getHumidity());
        $this->pressDisplay->setPressure($observable->getPressure());
        $this->display();
    }
    public function display () {
        echo 'Данные метеостанции: <br>';
        $this->tempDisplay->display();
        $this->humDisplay->display();       
        $this->pressDisplay->display();
    }   
}

// метеостанция
$meteoStation = new MeteoStation();
// дисплеи метеоданных
$temperatureDisplay1 = new CurrentTemperatureDisplay();
$temperatureDisplay2 = new CurrentTemperatureDisplay();
$humidityDisplay = new CurrentHumidityDisplay();
$pressureDisplay = new CurrentPressureDisplay();
$mainDisplay = new MainDisplay();
// подключаем дисплеи к получению апределённых данных с метеостанции
$meteoStation->attachObserversTemp( $temperatureDisplay1 );
$meteoStation->attachObserversTemp( $temperatureDisplay2 );
$meteoStation->attachObserversTemp( $mainDisplay );
$meteoStation->attachObserversHum( $humidityDisplay );
$meteoStation->attachObserversHum( $mainDisplay );
$meteoStation->attachObserversPress( $mainDisplay );
// меняются данные метеостанции
$meteoStation->setTemperature(25);
echo '<hr>';
$meteoStation->setHumidity(50);
echo '<hr>';
$meteoStation->setPressure(75);

