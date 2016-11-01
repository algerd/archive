<?php
/*
SplObjectStorage implements Countable , Iterator , Traversable , Serializable , ArrayAccess 
{
    void attach (object $object [, mixed $data = NULL ])- Добавляет объект в контейнер
    void detach ( object $object )              - Удаляет объект object из контейнера
    void addAll ( SplObjectStorage $storage )   - Добавляет все объекты из другого контейнера
    bool contains ( object $object )            - Проверяет, содержит ли контейнер заданный объект
    void setInfo ( mixed $data )                - Ассоциирует данные с текущим объектом контейнера
    mixed getInfo ( void )                      - Возвращает данные ассоциированные с текущим объектом
    void removeAll ( SplObjectStorage $storage )- Удаляет из текущего контейнера объекты, которые есть в другом контейнере
    void removeAllExcept ( SplObjectStorage $storage ) - Удаляет из текущего контейнера все объекты, которых нет в другом контейнере
    string getHash ( object $object )           - Вычисляет уникальный идентификатор для объектов контейнера (привязан к сессии)
       
  Переопределённые методы интерфейса ArrayAccess - позволяют работать как с массивом     
    void offsetSet (object $object [, mixed $data = NULL ])- Ассоциирует данные с объектом в контейнере
    mixed offsetGet ( object $object )          - Возвращает данные ассоциированные с объектом в контейнере   
    void offsetUnset ( object $object )         - Удаляет объект из контейнера при вызове функции unset()  
    bool offsetExists ( object $object )        - Проверяет, существует ли объект в контейнере
  Переопределённые методы интерфейса Iterator - позволяют итерировать       
    void rewind ( void )                        - Переводит итератор на первый элемент контейнера  
    bool valid ( void )                         - Определяет, допустимо ли текущее значение итератора   
    int key ( void )                            - Возвращает индекс текущего положения итератора
    object current ( void )                     - Возвращает текущий объект    
    void next ( void )                          - Переход к следующему объекту 
  Переопределённые методы интерфейса Serializable - позволяют сериализовать   
    string serialize ( void )                   - Сериализует контейнер
    void unserialize ( string $serialized )     - Восстанавливает сериализованый контейнер из строки      
  Переопределённые методы интерфейса Countable
    int count ( void )                          - Возвращает количество объектов в контейнере
}

Карта - это структура данных, содержащая пары ключ-значение.
Массивы PHP можно рассматривать как карты, отображающие целые/строковые(ключи) данные в их значения. 
SPL предоставляет карту, отображающую объекты(ключи) к данным. Эта карта также может быть использована как множество объектов. 
Класс SplObjectStorage предоставляет соответствие объекты-данные или набор объектов, игнорируя данные. 
Эта двойная цель может быть полезна во многих случаях, включая необходимость уникальной идентификации объектов.

Простыми словами:
    SplObjectStorage (карта объект/данные) - это хранилище-итератор объектов и c ним можно работать как с массивом, ключами которого 
    являются объекты. Значением элементов могут быть любые данные. В простых массивах ключами могут быть только строки и числа.
 *	Структура данных в хранилище storage[$odject] = $values.
*/
/*
$reflection = new ReflectionClass('SplObjectStorage');
var_dump($reflection->getProperties());
var_dump($reflection->getConstants());
var_dump($reflection->getMethods());
echo '<hr>';
*/
  
// Набор объектов
$s = new SplObjectStorage();

$o1 = new StdClass;
$s->attach($o1);
var_dump($s->contains($o1)); // true

$s->detach($o1);
var_dump($s->contains($o1)); // false

$o2 = new StdClass;
$s[$o1] = "данные для объекта 1";
$s[$o2] = array(1,2,3);

if (isset($s[$o2])) {
    echo $s[$o1];
    var_dump($s[$o2]);
}
$s->rewind();
while($s->valid()) {
    echo 'key: '.$s->key().' ';
    print_r($s->current()); // аналогично current($s)
    echo ' = ';
    print_r($s->getInfo());
    echo '<br>';
    $s->next();
}

echo'<hr>';

$storage = new SplObjectStorage();

class User{
    public $name;
    private $age;
    public $num;
    public static $count = 0;
    public function __construct($n, $a) {
        $this->name = $n;
        $this->age = $a;
        self::$count++;
        $this->num = self::$count;
    }
}
$person1 = new User("John", "25");
$person2 = new User("Alex", "30");
$person3 = new User("Kate", "15");

//Добавляем объекты в хранилище и ассоциируем с ними дополнительные данные
$storage[$person2] = 'Пользователь'.$person2->num.' id_hash: '.spl_object_hash ($person2);
$storage[$person1] = 'Пользователь'.$person1->num.' id_hash: '.spl_object_hash ($person1);
$storage[$person3] = 'Пользователь'.$person3->num.' id_hash: '.spl_object_hash ($person3);
// извлекаем объекты и данные из хранилища
foreach ($storage as $object){
	print_r($object);           // объект со всеми своими данными
    echo $storage->getInfo();   // данные, ассоциированные в хранилище с текущим объектом-$os->current()
	echo "<br>";
}