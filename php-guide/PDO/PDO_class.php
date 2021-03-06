<?php
/*
Класс PDO - Представляет соединение между PHP и сервером базы данных.

PDO 
{
	static array getAvailableDrivers ( void ) - Возвращает массив доступных драйверов PDO
 
	// Методы инициализации соединения с бд (см. PDO_connect.php)
	__construct ( string $dsn [, string $username [, string $password [, array $driver_options ]]] )
		string $dsn				- состоит из имени драйвера PDO, за которым следует двоеточие и специфический синтаксис подключения драйвера PDO.
		array $driver_options	- массив ['PDO::аттрибут'=>'PDO::константа', ...]  (задаются также с помощью setAttribute())
	bool setAttribute ( int $attribute , mixed $value ) - Присвоение атрибута соеденения с базой данных
	mixed getAttribute ( int $attribute )				- Получить атрибут соеденения с базой данных
		
	// Методы диагностики состояния соединения (см. PDO_error.php)	 	
	mixed errorCode ( void )	- Возвращает код SQLSTATE результата последней операции с базой данных
	array errorInfo ( void )	- Получает расширенную информацию об ошибке, произошедшей в ходе последнего обращения к базе данных		
	
	// Методы выполнения транзакций	(см. PDO_transaction.php)	
	bool inTransaction ( void )	- Проверяет, есть ли внутри транзакция	
	bool beginTransaction ( void ) - Инициализация транзакции		
	bool commit ( void )		- Фиксирует транзакцию
	bool rollBack ( void )		- Откат транзакции		
	
	// Методы выполнения запросов		
	int exec ( string $statement ) - Запускает SQL запрос на выполнение и возвращает количество строк, задействованых в ходе его выполнения
	string lastInsertId ([ string $name = NULL ] ) - Возвращает ID последней вставленной строки или последовательное значение
	string quote ( string $string [, int $parameter_type = PDO::PARAM_STR ] ) - Заключает строку в кавычки для использования в запросе	
	
	//	Методы подготовленных запросов	
	PDOStatement prepare ( string $statement [, array $driver_options = array() ] ) - Подготавливает запрос к выполнению и возвращает ассоциированный с этим запросом объект
	PDOStatement query ( string $statement ) - Выполняет SQL запрос и возвращает результирующий набор в виде объекта PDOStatement			
}
*/

$reflection = new ReflectionClass('PDO');
var_dump($reflection->getConstants());
var_dump($reflection->getProperties());
var_dump($reflection->getMethods());
echo '<hr>';

if ($db->getAttribute(PDO::ATTR_DRIVER_NAME) == 'mysql') {
  echo "Работаем с mysql; делаем что-то специфичное для mysql\n";
}

/* 
// Список предопределённых констант 

PDO::PARAM_BOOL (integer) Представляет булевый тип данных.
PDO::PARAM_NULL (integer) Представляет тип данных SQL NULL.
PDO::PARAM_INT (integer) Представляет тип данных SQL INTEGER.
PDO::PARAM_STR (integer) Представляет типы данных SQL CHAR, VARCHAR и другие строковые типы.
PDO::PARAM_LOB (integer) Представляет тип данных больших объектов SQL.
PDO::PARAM_STMT (integer) Представляет тип recordset. На данных момент не поддерживается драйверами.
PDO::PARAM_INPUT_OUTPUT (integer) Указывает, что параметр является INOUT параметром для хранимой процедуры. Для задания типа данных необходимо применить побитовое ИЛИ этой константы с константой типа PDO::PARAM_*.

PDO::FETCH_LAZY (integer) Указывает, что метод, осуществляющий выборку данных, должен возвращать каждую строку выборки в виде объекта, в котором имена переменных соответствуют именам столбцов результирующего набора. PDO::FETCH_LAZY создает объект с такими же именами переменных, с которыми осуществлялась выборка. Константа недействительна для функции PDOStatement::fetchAll().
PDO::FETCH_ASSOC (integer) Указывает, что метод, осуществляющий выборку данных, должен возвращать каждую строку результирующего набора в виде ассоциативного массива, индексы которого соответствуют именам столбцов результата выборки. Если в результирующем наборе несколько столбцов с одинаковыми именами, PDO::FETCH_ASSOC будет возвращать по одному значению для каждого столбца. Значения дублирующихся столбцов будут утеряны.
PDO::FETCH_NAMED (integer) Указывает, что метод, осуществляющий выборку данных, должен возвращать каждую строку результирующего набора в виде ассоциативного массива, индексы которого соответствуют именам столбцов результата выборки. Если в результирующем наборе несколько столбцов с одинаковыми именами, PDO::FETCH_NAMED возвращает массив значений для каждого имени столбца.
PDO::FETCH_NUM (integer) Указывает, что метод, осуществляющий выборку данных, должен возвращать каждую строку результирующего набора в виде массива, индексы которого соответствуют порядковым номерам столбцов результата выборки. Нумерация начинается с 0.
PDO::FETCH_BOTH (integer) Указывает, что метод, осуществляющий выборку данных, должен возвращать каждую строку результирующего набора в виде массива. Индексация массива производится и по именам столбцов и по их порядковым номерам в результирующей таблице. Нумерация начинается с 0.
PDO::FETCH_OBJ (integer) Указывает, что метод, осуществляющий выборку данных, должен возвращать каждую строку результирующего набора в виде объекта, имена свойств которого соответствуют именам столбцов результирующей таблицы.
PDO::FETCH_BOUND (integer) Указывает, что метод, осуществляющий выборку данных, должен возвращать TRUE и присваивать значения столбцов таблицы переменным PHP, которые были привязаны методами PDOStatement::bindParam() или PDOStatement::bindColumn().
PDO::FETCH_COLUMN (integer) Указывает, что метод, осуществляющий выборку данных, должен возвращать значение только одного столбца из следующей строки результирующего набора.
PDO::FETCH_CLASS (integer) Указывает, что метод, осуществляющий выборку данных, должен возвращать новый объект запрашиваемого класса, заполняя именованные свойства класса значениями столбцов результирующей таблицы.Замечание: Если в классе нет свойства с необходимым именем, будет вызван магический метод __set().
PDO::FETCH_INTO (integer) Указывает, что метод, осуществляющий выборку данных, должен обновлять существующий объект запрашиваемого класса, заполняя именованные свойства класса значениями столбцов результирующей таблицы.
PDO::FETCH_FUNC (integer) Позволяет настроить обработку данных налету во время выборки (константа действительна только для функции PDOStatement::fetchAll()).
PDO::FETCH_GROUP (integer) Группировка возвращаемых значений. Обычно комбинируется с константами PDO::FETCH_COLUMN или PDO::FETCH_KEY_PAIR.
PDO::FETCH_UNIQUE (integer) Выбирать только уникальные значения, исключать дубли из результата.
PDO::FETCH_KEY_PAIR (integer) Выборка из двух столбцов будет помещена в массив, в котором значения первого столбца принимаются за ключи, а значения второго - за значения. Константа доступна с версии PHP 5.2.3.
PDO::FETCH_CLASSTYPE (integer) Определение имени класса по значению первого столбца.
PDO::FETCH_SERIALIZE (integer) Аналогична PDO::FETCH_INTO, но объект представлен в виде сериализованной строки. Константа доступна с версии PHP 5.1.0.
PDO::FETCH_PROPS_LATE (integer)

PDO::ATTR_AUTOCOMMIT (integer) Если значение FALSE, PDO попытается отключить автоматическую фиксацию изменений в базе данных, таким образом началом транзакции будет установление соединения.
PDO::ATTR_PREFETCH (integer) Изменение размера буфера предвыборки позволяет регулировать баланс между затрачиваемой памятью и скоростью работы с базой данных. Не все связки база/драйвер поддерживают изменение размера этого буфера. Чем больше этот размер, тем выше быстродействие, но и выше затраты памяти.
PDO::ATTR_TIMEOUT (integer) Задает время в секундах, в течение которого должен быть завершен обмен с базой данных.
PDO::ATTR_ERRMODE (integer) Подробно об этом атрибуте см. раздел Ошибки и их обработка.
PDO::ATTR_SERVER_VERSION (integer) Этот атрибут предназначен только для чтения; он содержит информацию о версии сервера баз данных, к которому подключен PDO.
PDO::ATTR_CLIENT_VERSION (integer) Этот атрибут предназначен только для чтения; он содержит информацию о версии клиентских библиотек, которые использует PDO драйвер.
PDO::ATTR_SERVER_INFO (integer) Этот атрибут предназначен только для чтения; он содержит информацию о сервере баз данных, к которому подключен PDO.
PDO::ATTR_CONNECTION_STATUS (integer)
PDO::ATTR_CASE (integer) Приведение имен столбцов к нужному регистру, который задается константами PDO::CASE_*.
PDO::ATTR_CURSOR_NAME (integer) Получение или задание имени курсора. Особенно полезно при использовании прокручиваемых курсоров или обновления данных в конкретных позициях.
PDO::ATTR_CURSOR (integer) Выбор типа курсора. PDO на данный момент поддерживает PDO::CURSOR_FWDONLY и PDO::CURSOR_SCROLL. Если вы не уверены, что вам нужен именно прокручиваемый курсор, выбирайте PDO::CURSOR_FWDONLY.
PDO::ATTR_DRIVER_NAME (string) Возвращает имя драйвера.
PDO::ATTR_ORACLE_NULLS (integer) Преобразование пустых строк в SQL NULL в выборках.
PDO::ATTR_PERSISTENT (integer) Запрашивать постоянное соединение вместо создания нового подключения. Подробнее об этом атрибуте см. раздел Подключения и управление подключениями.
PDO::ATTR_STATEMENT_CLASS (integer)
PDO::ATTR_FETCH_CATALOG_NAMES (integer) Предписание предварять имена столбцов именами каталогов в результирующем наборе. Имя столбца отделяется от имени каталога десятичной точкой (.). Поддержка этого атрибута заложена на уровне драйвера; этот функционал поддерживается не всеми драйверами.
PDO::ATTR_FETCH_TABLE_NAMES (integer) Предписание предварять имена столбцов именами исходных таблиц в результирующем наборе. Имя столбца отделяется от имени таблицы десятичной точкой (.). Поддержка этого атрибута заложена на уровне драйвера; этот функционал поддерживается не всеми драйверами.
PDO::ATTR_STRINGIFY_FETCHES (integer)
PDO::ATTR_MAX_COLUMN_LEN (integer)
PDO::ATTR_DEFAULT_FETCH_MODE (integer)
PDO::ATTR_EMULATE_PREPARES (integer)
		
PDO::ERRMODE_SILENT (integer) Предписание не выбрасывать исключений в случае ошибок. Предполагается, что разработчик скрипта явно проверяет все исключительные ситуации. Это режим по умолчанию. Подробнее об этом атрибуте см. в разделе Ошибки и их обработка.
PDO::ERRMODE_WARNING (integer) Предписание выдавать сообщение об ошибке PHP уровня E_WARNING. Подробнее об этом атрибуте см. в разделе Ошибки и их обработка.
PDO::ERRMODE_EXCEPTION (integer) Предписание выбрасывать исключение PDOException в случае ошибки. Подробнее об этом атрибуте см. в разделе Ошибки и их обработка.

PDO::CASE_NATURAL (integer) Предписание оставлять имена столбцов такими, какие выдал драйвер базы данных.
PDO::CASE_LOWER (integer) Приводить имена столбцов к нижнему регистру.
PDO::CASE_UPPER (integer) Приводить имена столбцов к верхнему регистру.
PDO::NULL_NATURAL (integer)
PDO::NULL_EMPTY_STRING (integer)
PDO::NULL_TO_STRING (integer)
PDO::FETCH_ORI_NEXT (integer) Предписание выбрать следующую строку из результирующего набора. Константа действительна только для прокручиваемых курсоров.
PDO::FETCH_ORI_PRIOR (integer) Предписание выбрать предыдущую строку из результирующего набора. Константа действительна только для прокручиваемых курсоров.
PDO::FETCH_ORI_FIRST (integer) Предписание выбрать первую строку из результирующего набора. Константа действительна только для прокручиваемых курсоров.
PDO::FETCH_ORI_LAST (integer) Предписание выбрать последнюю строку из результирующего набора. Константа действительна только для прокручиваемых курсоров.
PDO::FETCH_ORI_ABS (integer) Предписание выбрать строку с указанным номером из результирующего набора. Константа действительна только для прокручиваемых курсоров.
PDO::FETCH_ORI_REL (integer) Предписание выбрать строку из результирующего набора относительно текущего положения курсора. Константа действительна только для прокручиваемых курсоров.
PDO::CURSOR_FWDONLY (integer) Предписание создать объект PDOStatement с последовательным курсором. Последовательные курсоры выбираются по умолчанию, так как это наиболее общий и быстрый способ доступа к данным в БД из PHP.
PDO::CURSOR_SCROLL (integer) Предписание создать объект PDOStatement с прокручиваемым курсором. Передача констант PDO::FETCH_ORI_* позволяет задать режим работы курсора, то, как будут выбираться строки из результирующего набора запроса.
PDO::ERR_NONE (string) Соответствует коду SQLSTATE '00000'. Это означает, что SQL запрос выполнен успешно без ошибок или предупреждений. Эта константа внедрена для удобства при проверке, есть ли ошибки, функциями PDO::errorCode() или PDOStatement::errorCode(). Также можно проверять, есть ли ошибка внутри метода, сравнивая его код возврата с этой константой.

PDO::PARAM_EVT_ALLOC (integer) Событие, возникающее при выделении памяти под объект
PDO::PARAM_EVT_FREE (integer) Событие, возникающее при освобождении занимаемой объектом памяти
PDO::PARAM_EVT_EXEC_PRE (integer) Событие, возникающее перед запуском подготовленного запроса.
PDO::PARAM_EVT_EXEC_POST (integer) Событие, возникающее после запуска подготовленного запроса.
PDO::PARAM_EVT_FETCH_PRE (integer) Событие, возникающее перед выборкой данных из результирующего набора.
PDO::PARAM_EVT_FETCH_POST (integer) Событие, возникающее после выборки данных из результирующего набора.
PDO::PARAM_EVT_NORMALIZE (integer) Событие, возникающее во время регистрации параметров, позволяющее драйверу нормализовать имена параметров.
		
*/

