<?php

class Html {
	function call($method, $arg) {
		return $method;
	}
}

/**
 * Вызов прописанных в классе методов
 * Синглтон-обёртка H класса Html
 */
class H1 {
	
	private function __construct() {}
	private function __clone() {}
	
	/*
	 * Синглтон
	 */
	private static $instance = null;	
	private static function getInstance() {
		if ( self::$instance == null ) 
			self::$instance = new Html();
		return self::$instance;
	}

	/*
	 * Вызов прописанных методов
	 */
	static function div( array $arg = array() ) {
		return self::getInstance()->call('div', $arg);
	}
	
}

/**
 * Перехватчик статических методов (непрописанных)
 * Синглтон-обёртка H класса Html
 */
class H2 {
	
	private function __construct() {}
	private function __clone() {}
	
	/*
	 * Синглтон
	 */
	private static $instance = null;	
	private static function getInstance() {
		if ( self::$instance == null ) 
			self::$instance = new Html();
		return self::$instance;
	}
	
	/*
	 * Перехватчик методов
	 */
	static function __callStatic( $method, $arg = array() ) {
		$out = self::getInstance()->call('div', $arg);
		return $method.' вызывает '. $out;
	}
	
}


echo H1::div();	// div
echo '<br>';
echo H2::a();	// a вызывает div