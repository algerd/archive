<?php
/*
 * Простейший пример реализации загрузки классов из модулей Controller, Model, View, Library в MVC
 */
class Loader
{
    private $_controllerDirectoryPath;// Controller Directory Path
    private $_modelDirectoryPath;     // Model Directory Path
    private $_viewDirectoryPath;      // View Directory Path
    private $_libraryDirectoryPath;   // Library Directory Path
   
    public function __construct ($controllerPath, $modelPath, $viewPath, $libraryPath) {
        // Инициализация путей загрузки классов из Controller, Model, View, Library
        $this->modelDirectoryPath = $modelPath;
        $this->viewDirectoryPath = $viewPath;
        $this->controllerDirectoryPath = $controllerPath;
        $this->libraryDirectoryPath  = $libraryPath;
       
        // регистрируем методы автозагрузки классов
        spl_autoload_register(array($this,'load_controller_class'));
        spl_autoload_register(array($this,'load_model_class'));
        spl_autoload_register(array($this,'load_view_class'));
        spl_autoload_register(array($this,'load_library_class'));
    }   
    //Autoload Controller class
    public function load_controller ($class) {
        if ($class) {
            set_include_path($this->controllerDirectoryPath);
            spl_autoload_extensions('.contr.php');
            spl_autoload($class);
        }
    }   
    //Autoload Model class
    public function load_model ($class) {
        if ($class) {
            set_include_path($this->modelDirectoryPath);
            spl_autoload_extensions('.model.php');
            spl_autoload($class);
        }
    }   
    //Autoload Model class
    public function load_view ($class) {
        if ($class) {
            set_include_path($this->viewDirectoryPath);
            spl_autoload_extensions('.view.php');
            spl_autoload($class);
        }
    }  
    // Autoload Library class
    public function load_library($class){
        if ($class) {
            set_include_path($this->libraryDirectoryPath);
            spl_autoload_extensions('.lib.php');
            spl_autoload($class);
        }
    }  
}

// В файлах запускаем загрузчик, передавая ему директории загрузки классов из модулей
$loader = new Loader($controllerPath, $modelPath, $viewPath, $libraryPath);