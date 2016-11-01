<?php
/*
Паттерн Composite используется для создания древовидных структур данных.
Пример древовидной структуры - директории Windows, в которых папка может содержать папки и файлы по 
аналогии с деревом: от ветки могут ответвляться ветки и листья.
И если примитивные объекты - файлы или листья, можно получить простым наследованием от папки или ветки, 
то композитные объекты (составные) - папки или ветви , можно определить только с помощью Dependency Injection (Comosition)-
путём встраивания внутрь объекта объекта того же класса.
 */
 // Пример файловой системы:

abstract class Dir {
    abstract function getSizeDir();           // размер объекта директории (файла или папки)

    // добавить объект директории к примитивному объекту (файлу) и вернуть композитный объект (папку)
    public function addDirObject ( Dir $dirObject ) {
        return ( new Folder() )->addDirObject( $this )->addDirObject( $dirObject );
    }   
}

////////////////////////////// Класс композитных объектов (папок) /////////////////////////
class Folder extends Dir {    
    private $arrDirObject = array();		// массив объектов - элементов директории (папок и файлов)
     
    // переопределение метода - добавление объектов директории в папку
    public function addDirObject( Dir $dirObject ) {
        if ( !in_array( $dirObject, $this->arrDirObject, true ) )
            $this->arrDirObject[] = $dirObject;  
        return $this;
    }    
    // удаление объектов директории из папки   
    public function deleteUnitDir( Dir $dirObject ) {
        foreach ($this->arrDirObject as $key => $object) {
            if ($object === $dirObject) unset($this->arrDirObject[$key]);
        }
        return $this;
    }
    //
    public function getArrDirObject() {
        return $this->arrDirObject;
    }
	// рекурсивный подсчёт размера файлов в папке
    public function getSizeDir() {
        $size = 0;
        foreach( $this->arrDirObject as $object ) {
            $size += $object->getSizeDir();
        }
        return $size;
    }
}
    
//////////////////////// Классы примитивных объектов (файлов) ////////////////////////
class FileTXT extends Dir {
    public function getSizeDir() { return 5; }
}
class FilePDF extends Dir {
    public function getSizeDir() { return 20; }
}
class FileJPG extends Dir {
    public function getSizeDir() { return 100; }
}

////////////////////////////////////// Client //////////////////////////////////
echo '<pre>';
$obj = new FileTXT();
$folder1 = (new Folder())->addDirObject( new FileJPG() )->addDirObject( $obj )->addDirObject( $obj );
$folder2 = (new Folder())->addDirObject( new FilePDF() )->addDirObject( new FileJPG() );

// присоединяем к композитному объекту-папке другой объект
$folder1->addDirObject( $folder2 );
//$folder1->deleteUnitDir( $folder2 );
echo 'getSizeDir: '.$folder1->getSizeDir().'<br>';
print_r( $folder1 );

// присоединяем к примитивному объекту-файлу другой объект
$file1 = new FileTXT();
$folder3 = $file1->addDirObject( $folder1 );
echo 'getSizeDir: '.$folder3->getSizeDir().'<br>';
print_r( $folder3 );

// создаём новую папку из примитивного элемента папки и папки (соединяем их)
$folder4 = $folder1->getArrDirObject()[1]->addDirObject( $folder2 );
echo 'getSizeDir: '.$folder4->getSizeDir().'<br>';
print_r( $folder4 );