<?php
/*
Шаблон фасад (англ. Facade) — шаблон, позволяющий скрыть сложность системы путем сведения всех возможных внешних вызовов к одному объекту, 
делегирующему их соответствующим объектам системы.
Простыми словами, Facade - это клиентский код работы с группой объектов, упакованный в методы одного класса-фасада. 
Для доступа к объектам из класса-фасада их делегируют(агреггируют) в соответствующие свойства класса-фасада, используя DI-Composition(Aggregation)
Класс-фасад - это контроллер или клиент-сервис, который позволяет объединить части взаимосвязанного процедурного кода в единую струтуру на основе ООП.
Пример: Framework, в котором роль фасада выполняет контроллер, который работает с моделью, видом, библиотеками.
Фасады позволяют полностью уйти от процедурного кода и в случае повторного вызова в других местах программы, фасад значтельно сокращает длину вызываемого кода,
уходя от повторения кода подобно простым функциям.
*/	

///////////////////// Части системы /////////////////////////////////////
class CPU {
    public function freeze() { /* ... */ }
    public function jump( $position ) { /* ... */ }
    public function execute() { /* ... */ }
 
} 
class Memory {
    public function load( $position, $data ) { /* ... */ }
}
 
class HardDrive {
    public function read( $lba, $size ) { /* ... */ }
}
 
////////////////////// Facade ///////////////////////////////////////////
class Computer {
    protected $cpu;
    protected $memory;
    protected $hardDrive;
 
    public function __construct(){
        $this->cpu = new CPU();
        $this->memory = new Memory();
        $this->hardDrive = new HardDrive();
    }
    public function startComputer(){
        $this->cpu->freeze();
        $this->memory->load( BOOT_ADDRESS, $this->hardDrive->read( BOOT_SECTOR, SECTOR_SIZE ) );
        $this->cpu->jump( BOOT_ADDRESS );
        $this->cpu->execute();
    }
}
 
///////////////////////// Client /////////////////////////////////////////
(new Computer())->startComputer();

/* Так выглядел бы код без фасада (процедурный подход):
С первого взгляда его длина меньше длины класса, но если его вызывать в других местах, то сразу ощутится перегруженность однообразным кодом.
	$cpu = new CPU();
	$memory = new Memory();
	$hardDrive = new HardDrive();
	$cpu->freeze();
	$memory->load( BOOT_ADDRESS, $this->hardDrive->read( BOOT_SECTOR, SECTOR_SIZE ) );
	$cpu->jump( BOOT_ADDRESS );
	$cpu->execute();
	unset( $cpu, $memory, $hardDrive );
*/
