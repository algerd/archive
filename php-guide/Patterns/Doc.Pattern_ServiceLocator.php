<?php
	// класс, который умеет отправлять GET запрос на определенный URI и возвращать HTML из ответа сервера
	class Grabber {
		function get($url) {/** returns HTML code or throws an exception */}
	}

	// класс, объекты которого будут отвечать за фильтрацию полученного HTML. Метод filter принимает в качестве аргументов HTML код и CSS селектор, а возвращает он пусть массив найденных элементов по заданному селектору
	class HtmlExtractor {
		function filter($html, $selector) {/** returns array of filtered elements */}
	}

////////////////////////////////////////// Прямое внедрение объектов в свойства ///////////////////////////////////////////////////////////
	// класс, который будет использовать класс Grabber для отправки запроса, а для извлечения необходимого контента класс HtmlExtractor.
	// Так же он будет содержать логику построения URI, селектор для фильтрации полученного HTML и обработку полученных результатов
	class GoogleFinder {
		private $grabber;	// Grabber
		private $filter;	// HtmlExtractor

		function __construct() {
			$this->grabber = new Grabber();
			$this->filter = new HtmlExtractor();
		}
		function find($searchString) { /** returns array of founded results */}
	}
/*
Хардкодить создание объектов в конструкторе не лучшая идея. И вот почему:
	1. Мы не сможем легко подменить класс Grabber в тестовой среде, чтобы избежать отправки реального запроса.
	2. Та же проблема возникнет, если мы захотим повторно использовать логику GoogleFinder c другими реализациями Grabber и HtmlExtractor.
		Создание зависимостей жестко прописано в конструкторе класса. И в самом лучшем случае у нас получится унаследовать GoogleFinder и переопределить его конструктор.
		Да и то, только если область видимости свойств grabber и filter будет protected или public.
	3. Каждый раз при создании нового объекта GoogleFinder в памяти будет создаваться новая пара объектов-зависимостей,
		хотя мы вполне можем использовать один объект типа Grabber и один объект типа HtmlExtractor в нескольких объектах типа GoogleFinder.
*/
	// инициализацию зависимостей нужно вынести за пределы класса
	class GoogleFinder {
		private $grabber;	// Grabber
		private $filter;	// HtmlExtractor

		function __construct( Grabber $grabber, HtmlExtractor $filter ) {
			$this->grabber = $grabber;
			$this->filter = $filter;
		}
		function find($searchString) { /** returns array of founded results */}
	}

	// Теперь мы можем использовать класс GoogleFinder в контроллере.
	class Controller {
		function action() {
			/* Some stuff */
			$finder = new GoogleFinder(new Grabber(), new HtmlExtractor());
			$results = $finder->find('search string');
			/* Do something with results */
		}
	}
/*
А что если нам понадобится использовать объект типа GoogleFinder в другом месте?
Нам придется продублировать его создание. В нашем примере это всего одна строка и проблема не так заметна.
На практике же инициализация объектов может быть достаточно сложной и может занимать до 10 строк, а то и более.
Так же возникают другие проблемы типичные для дублирования кода.
Если в процессе рефакторинга понадобится изменить имя используемого класса или логику инициализации объектов, то придется вручную поменять все места.
Обычно с хардкодом поступают просто. Дублирующиеся значения, как правило, выносятся в конфигурацию.
*/
/////////////////////////////////////// Registry //////////////////////////////////////////////////
//  Вынесим создание объектов в конфигурацию с помощью шаблона Registry:

	// Регистрируем объекты в хранилище
	$registry = new ArrayObject();
	$registry['grabber'] = new Grabber();
	$registry['filter'] = new HtmlExtractor();
	$registry['google_finder'] = new GoogleFinder($registry['grabber'], $registry['filter']);


// Нам остается только передать наш ArrayObject в контроллер и проблема решена.

	class Controller {
		private $registry;		// ArrayObject - хранилище зарегистрированных объектов

		function __construct( ArrayObject $registry ) {
			$this->registry = $registry;
		}
		function action() {
			/* Some stuff */
			$results = $this->registry['google_finder']->find('search string');
			/* Do something with results */
		}
	}
/*
Этот шаблон не относится к порождающим, но он в некоторой степени позволяет решить наши проблемы.
Registry – это всего лишь контейнер, в котором мы можем хранить объекты и передавать их внутри приложения.
Чтобы объекты стали доступными, нам необходимо их предварительно создать и зарегистрировать в этом контейнере.
Достоинства:
	Мы перестали хардкодить имена классов и создаем объекты в одном месте.
	Мы создаем объекты в единственном экземпляре, что гарантирует их повторное использование.
	Если изменится логика создания объектов, то отредактировать нужно будет только одно место в приложении.
	Как бонус мы получили, возможность централизованно управлять объектами в Registry.
	Мы легко можем получить список всех доступных объектов, и провести с ними какие-нибудь манипуляции.
Недостатки:
	Во-первых, мы должны создать объект перед тем как зарегистрировать его в Registry.
	  Соответственно, высока вероятность создания «ненужных объектов», т.е. тех которые будут создаваться в памяти, но не будут использоваться в приложении.
	  Да, мы можем добавлять объекты в Registry динамически, т.е. создавать только те объекты, которые нужны для обработки конкретного запроса.
	  Так или иначе контролировать это нам придется вручную. Соответственно, со временем поддерживать это станет очень тяжело.
	Во-вторых, у нас появилась новая зависимость у контроллера.
	  Да, мы можем получать объекты через статический метод в Registry, чтобы не передавать Registry в конструктор.
	  Но не стоит этого делать. Статические методы, это даже более жесткая связь, чем создание зависимостей внутри объекта, и сложности в тестировании
	В-третьих, интерфейс контроллера ничего не говорит нам о том, какие объекты в нем используются.
	  Мы можем получить в контроллере любой объект доступный в Registry. Нам тяжело будет сказать, какие именно объекты использует контроллер, пока мы не проверим весь его исходный код.
*/
//////////////////////////////////////// Factory Method ////////////////////////////////////////////////////
/*
В Registry нас больше всего не устраивает то, что объект необходимо предварительно инициализировать, чтобы он стал доступным.
Вместо инициализации объекта в конфигурации, мы можем выделить логику создания объектов в другой класс, у которого можно будет «попросить» построить необходимый нам объект.
Классы, которые отвечают за создание объектов называют фабриками. А шаблон проектирования называется Factory Method.
*/
	class Factory {
		function getGoogleFinder() {
			return new GoogleFinder( $this->getGrabber(), $this->getHtmlExtractor() );
		}
		private function getGrabber() { return new Grabber(); }
		private function getHtmlExtractor() { return new HtmlFiletr(); }
	}

// Мы можем использовать кэширование в свойство (сохранение объекта в свойстве при его создании), чтобы избежать повторного создания объектов.

	class Factory {
		private $finder;

		function getGoogleFinder() {
			if ( null === $this->finder ) {
				$this->finder = new GoogleFinder($this->getGrabber(), $this->getHtmlExtractor());
			}
			return $this->finder;
		}
	}

// Используем фабрику в контроллере.

	class Controller{
		private $factory;	// Factory

		function __construct( Factory $factory ) {
			$this->factory = $factory;
		}

		function action(){
			/* Some stuff */
			// при обращении к фабрике она создаёт требуемй объект и возвращает его
			$results = $this->factory->getGoogleFinder()->find('search string');
			/* Do something with results */
		}
	}
/*
К преимуществам данного подхода, отнесем его простоту. Наши объекты создаются явно, и Ваша IDE легко приведет Вас к месту, в котором это происходит.
Мы также решили проблему Registry и объекты в памяти будут создаваться только тогда, когда мы «попросим» фабрику об этом.
Но мы пока не решили, как поставлять контроллерам нужные фабрики. Тут есть несколько вариантов:
	Можно использовать статические методы.
	Можно предоставить контроллерам самим создавать нужные фабрики и свести на нет все наши попытки избавиться от копипаста.
	Можно создать фабрику фабрик и передавать в контроллер только ее. Но получение объектов в контроллере станет немного сложнее, да и нужно будет управлять зависимостями между фабриками.
Кроме того не совсем понятно, что делать, если мы хотим использовать модули в нашем приложении, как регистрировать фабрики модулей, как управлять связями между фабриками из разных модулей.
В общем, мы лишились главного преимущества фабрики – явного создания объектов. И пока все еще не решили проблему «неявного» интерфейса контроллера.
*/
/////////////////////////////////////// Service Locator ///////////////////////////////////////////////////////
/*
Шаблон Service Locator позволяет решить недостаток разрозненности фабрик и управлять созданием объектов автоматически и централизованно.
Mы можем ввести дополнительный слой абстракции, который будет отвечать за создание объектов в нашем приложении и управлять связями между этими объектами.
Для того чтобы этот слой смог создавать объекты для нас, мы должны будем наделить его знаниями, как это делать.
Термины шаблона Service Locator:
	Сервис (Service) — готовый объект, который можно получить из контейнера.
	Описание сервиса (Service Definition) – логика инициализации сервиса.
	Контейнер (Service Container) – центральный объект который хранит все описания и умеет по ним создавать сервисы.
	Service Container в ZF2 это ServiceManager

Любой модуль может зарегистрировать свои описания сервисов. Чтобы получить какой-то сервис из конейнера мы должны будем запросить его по ключу.
Существует масса вариантов реализации Service Locator, в простейшем варианте мы можем использовать ArrayObject в качестве контейнера и замыкания, в качестве описания сервисов.
*/
	class ServiceContainer extends ArrayObject {

		// если обращаться к $this[$key] как к callback-функции
		function getCallback( $key, $param = null ) {
			if ( is_callable($this[$key]) ) {
				// вызываес callback-функцию $this[$key] и передаём ей парметры $param
				return call_user_func($this[$key], $param);
			}
			throw new \RuntimeException("Can not find service definition under the key [ $key ]");
		}

		// если обращаться к $this[$key] как к объекту Closure, что более конкретно, точно и понятно
		function getClosure( $key, $param = null ) {
			if ( $this[$key] instanceof Closure ) {
				// вызвать внутренний метод объекта Closure через __invoke() и передать ему параметры $param
				return $this[$key]->__invoke($param);
			}
			throw new \RuntimeException("Can not find service definition under the key [ $key ]");
		}
	}

// Тогда регистрация Definitions будет выглядеть так (помещаем в ArrayObject[key] объекты Closure или, как многие любят по аналогии с js говорить, - callback-функции):

	$container = new ServiceContainer();

	$container['grabber'] = function () { return new Grabber(); };
	$container['html_filter'] = function () { return new HtmlExtractor(); };
	$container['google_finder'] = function($param) use ($container) {
		return new GoogleFinder( $container->getCallback('grabber'), $container->getClosure('html_filter') );
	};

// А использование, в контроллере так:

	class Controller {
		private $container;		// ServiceContainer - он жe ServiceManager

		function __construct( ServiceContainer $container ) {
			$this->container = $container;
		}

		function action() {
			/* Some stuff */
			$results = $this->container->getClosure('google_finder')->find('search string');
			/* Do something with results */
		}
	}

/*
Service Locator не только решает все те проблемы, что и предыдущие шаблоны, но и позволяет легко использовать модули с собственными определениями сервисов (каждый модуль будет иметь свой ServiceContainer?).
Кроме того, на уровне фреймворка мы получили дополнительный уровень абстракции. А именно, изменяя метод ServiceContainer::getClosure мы сможем, например, подменить объект на прокси.
А область применения прокси-объектов ограниченна лишь фантазией разработчика. Тут можно и AOP парадигму реализовать, и LazyLoading и т.д.
Но, большинство разработчиков, все таки считают Service Locator анти-паттерном. Потому что, в теории, мы можем иметь сколько угодно т.н. Container Aware классов (т.е. таких классов, которые содержат в себе ссылку на контейнер).
Например, наш Controller, внутри которого мы можем получить любой сервис.
Давайте, посмотрим, почему это плохо.
	Во-первых, опять же тестирование.
		Вместо того, чтобы создавать моки только для используемых классов в тестах придется делать мок всему контейнеру или использовать реальный контейнер.
		Первый вариант не устраивает, т.к. приходится писать много ненужного кода в тестах, второй, т.к. он противоречит принципам модульного тестирования, и может привести к дополнительным издержкам на поддержку тестов.
	Во-вторых, нам будет трудно рефакторить.
		Изменив любой сервис (или ServiceDefinition) в контейнере, мы будем вынуждены проверить также все зависимые сервисы. И эта задача не решается при помощи IDE.
		Отыскать такие места по всему приложению будет не так-то и просто. Кроме зависимых сервисов, нужно будет еще проверить все места, где отрефакторенный сервис получается из контейнера.
	В-третьих, бесконтрольное дергание сервисов из контейнера рано или поздно приведет к каше в коде и излишней путанице.
		Это сложно объяснить, просто Вам нужно будет тратить все больше и больше времени, чтобы понять как работает тот или иной сервис,
		иными словами полностью понять что делает или как работает класс можно будет только прочитав весь его исходный код.
*/
//////////////////////////////////////////// Dependency Injection Container (DIC)///////////////////////////////////////////////////
/*
Что же можно еще предпринять, чтобы ограничить использование контейнера в приложении?
Можно передать в фреймворк управление созданием всех пользовательских объектов, включая контроллеры.
Иными словами, пользовательский код не должен вызывать метод get у контейнера.
В нашем примере мы cможем добавить в контейнер Definition для контроллера:
*/

	$container['Controller'] = function() use ($container) {
		return new Controller( $container->getClosure('google_finder') );
	};

// И избавиться от контейнера в контроллере:
	class Controller {
		private $googleFinder;		// GoogleFinder

		function __construct( GoogleFinder $finder ) {
			$this->googleFinder = $finder;
		}

		function action() {
			/* Some stuff */
			$results = $this->googleFinder->find('search string');
			/* Do something with results */
		}
	}
/*
По сути мы будем использовать тот же Service Container, что и для предыдущего паттерна.
Разница в том, что теперь внутри Контроллера  нет связм с контейнером, а она вынесена наружу (в данном случае в контейнер).
В предыдущем паттерне DIC уже был реализован в $container['google_finder'], в который внедрялись объекты классов HtmlExtractor и Grabber из контейнера.
DIC как дальнейшее развитие Service Locator взял от него все преимущества плюс:
	Мы избавились от контейнера в клиентских классах, благодаря чему их код стал намного понятнее и проще.
	Мы легко можем протестировать контроллер, подменив необходимые зависимости.
	Мы можем создавать и тестировать каждый класс независимо от других (в том числе и классы контроллеров) используя TDD или BDD подход.
	При создании тестов мы сможем абстрагироваться от контейнера, и позже добавить Definition, когда нам понадобится использовать конкретные экземпляры.
	Все это сделает наш код проще и понятнее, а тестирование прозрачнее.

Но, Дело в том, что контроллеры – это весьма специфичные классы.
Начнем с того, что контроллер, как правило, содержит в себе набор экшенов, значит, нарушает принцип единственной ответственности.
В результате у класса контроллера может появиться намного больше зависимостей, чем необходимо для выполнения конкретного экшена.
Использование отложенной инициализации (объект инстанцианируется в момент первого использования, а до этого используется легковесный прокси) в какой-то мере решает вопрос с производительностью.
Но с точки зрения архитектуры создавать множество зависимостей у контроллера тоже не совсем правильно.
Кроме того тестирование контроллеров, как правило излишняя операция. Все, конечно, зависит от того как тестирование организовано в Вашем приложении и от того как вы сами к этому относитесь.
*/
//////////////////////////////////////////////////////////////////////////////////////////////////////////////

Комментарии:






