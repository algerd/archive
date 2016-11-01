<?php
/*
Пример организации устройства датчик->дисплей датчика->общий дисплей (напр. термодатчик-цифровой индикатор)
Есть 3 датчика - Observable :температурный, влажности и давления. Они представлены отдельными классами, потому что их реализация может отличаться.
Есть 3 дисплея - Observer :температурный, влажности и давления. Они тоже представлены отдельными классами, потому вывод информации с соответствующих датчиков 
    может отличаться (в нашем примере в выводе добавляются маркеры соответствующих измеряемых величин).
    Датчики и дисплеи разделены, потому что возможна их различная независимая реализация (напр. дисплеи могут быть цифровыми, стрелочными, индикаторными и т.д.)
    Кроме того, датчик может быть один, а дисплеев одного типа несколько.
Дисплеи подключают к соответствующим датчикам (attach()) и при изменении значения датчиков изменяются показания соответствующих дисплеев.
Такое разделение удобно, потому что легко добавлять новые датчики и дисплеи, а затем встраивая дисплеи в общие дисплеи, можно легко получать вывод любых комбинаций показаний датчиков.    
Соблюдён главный принцип ООП - Разделение и независимомть!!!

!!! Из этого кода и кода в Observer_bad.php делаем главный вывод:
    - Класс от Observable должен предоставлять ТОЛЬКО ОДНО!!! наблюдение за одним свойством или за одной группой свойств.
       Если возникает необходимость реализовать ещё одно наблюдение в классе за каким-то свойством или группой свойств,
       то надо провести рефакторинг класса с выделением второго наблюдения в отдельный класс.   
*/    
////////////////////////// Интерфейсы наблюдаемых объектов
interface TemperatureObservable { 
    function attach( TemperatureObserver $observer );   // подключить дисплей к датчику
    function detach( TemperatureObserver $observer);    // отключить дисплей от датчика
}
interface HumadityObservable { 
    function attach( HumadityObserver $observer );
    function detach( HumadityObserver $observer);
}
interface PressureObservable { 
    function attach( PressureObserver $observer );
    function detach( PressureObserver $observer);
}

/////// Атмосферный датчик
abstract class WeatherSensor { 
    abstract protected function notify();   // оповестить дисплей о изменениях показаний датчика
    abstract public function set($v);
    abstract public function get();
}

class TemperatureSensor extends WeatherSensor implements TemperatureObservable {
    private $temperature = 0;
    private $observersTemp = [];     // массив объектов наблюдателей за temperature
    
    public function set( $temperature ) {
        if ( $this->temperature != $temperature ) {
            $this->temperature = $temperature;
            $this->notify();
        }
    }
    public function get() {
        return $this->temperature;
    }
    public function attach( TemperatureObserver $observer ) {
        $this->observersTemp[] = $observer;
    }
    public function detach( TemperatureObserver $observer ) {
        if ( $index = array_search( $observer, $this->observersTemp, true ) ) 
            unset( $this->observersTemp[$index] );
    }
    protected function notify() {
        foreach ($this->observersTemp as $observer) {
            $observer->update($this);
        }
    }
}
class HumiditySensor extends WeatherSensor implements HumadityObservable {
    private $humidity = 0;
    private $observersHum = [];      // массив объектов наблюдателей humidity
   
    public function set( $humadity ) {
        if ( $this->humidity != $humadity) {
            $this->humidity = $humadity;
            $this->notify();
        }
    }
    public function get() {
        return $this->humidity;
    }
    public function attach( HumadityObserver $observer ) {
        $this->observersHum[] = $observer;
    }       
    public function detach( HumadityObserver $observer ) {
        if ( $index = array_search( $observer, $this->observersHum, true ) ) 
            unset( $this->observersHum[$index] );
    }
    protected function notify() {
        foreach ($this->observersHum as $observer) {
            $observer->update($this);
        } 
    }
}
class PressureSensor extends WeatherSensor implements PressureObservable {
    private $pressure = 0;
    private $observersPress = [];    // массив объектов наблюдателей pressure

    public function set( $pressure ) {
        if ( $this->pressure != $pressure ) {
            $this->pressure = $pressure;
            $this->notify();
        }
    }
    public function get() {
        return $this->pressure;
    }
    public function attach( PressureObserver $observer ) {
        $this->observersPress[] = $observer;
    }
    public function detach( PressureObserver $observer ) {
        if ( $index = array_search( $observer, $this->observersPress, true ) ) 
            unset( $this->observersPress[$index] );
    }
    protected function notify() {
        foreach ($this->observersPress as $observer) {
            $observer->update($this);
        }
    }
}

//////////////////////////// Интерфейсы наблюдателей
interface TemperatureObserver { 
    function update( TemperatureObservable $obj );  // обновить показания дисплея
}
interface HumadityObserver { 
    function update( HumadityObservable $obj );
}
interface PressureObserver { 
    function update( PressureObservable $obj );
}

///////// Диплей датчика
abstract class Display {
    protected $display = 0;
    
    public function setDisplay ($sensor) {
        $this->display = $sensor;
    }
    abstract public function getDisplay();  // вывести показания дисплея
}

class TemperatureDisplay extends Display implements TemperatureObserver {
    public function update ( TemperatureObservable $observable ) {
            $this->setDisplay( $observable->get() );  
    }   
    public function getDisplay () {
        echo 't = '.$this->display.' C ';
    }
}
class HumidityDisplay extends Display implements HumadityObserver { 
    public function update ( HumadityObservable  $observable ) {
            $this->setDisplay( $observable->get() );  
    }
    public function getDisplay () {
        echo 'f = '.$this->display.' % ';
    }
}
class PressureDisplay extends Display implements PressureObserver {
    public function update (PressureObservable  $observable ) {
            $this->setDisplay( $observable->get() );  
    }
    public function getDisplay () {
        echo 'p = '.$this->display.' Pa ';
    }
}

// Общий дисплей 
// Встроенные дисплеи - это ссыдки на внешние дисплеи!!! Поэтому при изменении вешних дисплеев одновременно будут меняться и встроенные.
class MainDisplay {
    private $tempDisplay;   // дисплей температуры
    private $humDisplay;    // дисплей влажности
    private $pressDisplay;  // дисплей давления
    
    public function __construct( TemperatureDisplay $t, HumidityDisplay $h, PressureDisplay $p) {
        $this->tempDisplay = $t;
        $this->humDisplay = $h;
        $this->pressDisplay = $p;    
    }
    // вывести показания общего дисплея
    public function getDisplay() {    
        $this->tempDisplay->getDisplay();
        $this->humDisplay->getDisplay();
        $this->pressDisplay->getDisplay();   
    }   
}

// Датчики
$termometr = new TemperatureSensor();
$humidity = new HumiditySensor();
$barometr = new PressureSensor();

// Дисплеи
$termDisplay1 = new TemperatureDisplay();
$termDisplay2 = new TemperatureDisplay();
$humDisplay = new HumidityDisplay();
$pressDisplay = new PressureDisplay();
// встраиваем дисплеи в общий дисплей
$mainDisplay = new MainDisplay( $termDisplay1, $humDisplay, $pressDisplay );

// Подключаем дисплеи к датчикам
$termometr->attach( $termDisplay1 );
$termometr->attach( $termDisplay2 );
$humidity->attach( $humDisplay );
$barometr->attach($pressDisplay);

for ( $i = 0; $i < 5; $i++ ) {
    // Изменяем значения датчиков
    $termometr->set(rand(10, 30));
    $humidity->set(rand(50, 100));
    $barometr->set(rand(740,770));
    // выводим показания дисплеев
    $termDisplay1->getDisplay();
    //$termDisplay2->getDisplay();
    $humDisplay->getDisplay();
    $pressDisplay->getDisplay(); 
    echo '<br>';
    $mainDisplay->getDisplay();
    echo '<hr>';
}













/*
class WeatherStation implements Observer {
    private $temperatureSensor; // термометр
    private $humiditySensor;    // датчик влажности
    private $pressureSensor;    // барометр

    public function __construct() {
        // устанавливаем датчик на метеостанции
        $this->temperatureSensor = new TemperatureSenser();
        $this->humiditySensor = new HumiditySensor();
        $this->pressureSensor = new PressureSensor();
        // подключаем метеостанцию к датчикам
        $this->temperatureSensor->attach($this);    // подключаем метеостанцию к датчикам       
        $this->humiditySensor->attach($this);
        $this->pressureSensor->attach($this);    
    }
    // приём изменений с датчиков
    public function update() {
      
        $this->reportWeather();
    }   
    public function reportWeather() {
        echo 'Перeдаём данный о погоде: <br>';
        echo 'Температура воздуха: '.$this->temperatureSensor.'C<br>';
        echo 'Влажность воздуха: '.$this->humiditySensor.'%<br>';
        echo 'Давление воздуха: '.$this->pressureSensor.'Pa<br>';
    }
}
*/




