<?php

/////////////////////// Element //////////////////////////////////
abstract class Point {
	private $metric = -1;
	// подключаем новый функционал (расширение)
	public function accept( Visitor $v ){
		$v->visit( $this );
	}
	public function getMetric(){
		return $this->metric;
	}
	public function setMetric( $metric ){
		$this->metric = $metric;
	}
}
 
class Point2d extends Point {
	protected $x;
	protected $y;
	public function __construct( $x, $y ) {
		$this->x = $x;
		$this->y = $y;
	}
	public function getX() { return $this->x; }
	public function getY() { return $this->y; }
}
 
class Point3d extends Point2d {
	protected $z;	
	public function __construct( $x, $y, $z ) {
		parent::__construct($x, $y);
		$this->z = $z;
	}
	public function getZ() { return $this->z; }
}
 
///////////////////// Visitor /////////////////////////////////////
abstract class Visitor {
	public function visit ( Point $point ) {
		if ( $point instanceof Point3d ) $this->visitPoint3d( $point );
		elseif ( $point instanceof Point2d ) $this->visitPoint2d( $point );
	}
	abstract function visitPoint2d( Point2d $point );
	abstract function visitPoint3d( Point3d $point );
}
 
class Euclid extends Visitor{
	public function visitPoint2d( Point2d $point ) {
		$point->setMetric( sqrt( $point->getX() * $point->getX() + $point->getY() * $point->getY() ) );
	}
	public function visitPoint3d( Point3d $point ){
		$point->setMetric( sqrt( $point->getX() * $point->getX() + $point->getY() * $point->getY() + $point->getZ() * $point->getZ() ) );
	}
}
 
class Chebyshev extends Visitor {
	public function visitPoint2d( Point2d $point ) {
		$x = abs( $point->getX() );
		$y = abs( $point->getY() );
		$point->setMetric( $x > $y ? $x : $y );
	}
	public function visitPoint3d( Point3d $point ) {
		$x = abs(p.getX());
		$y = abs(p.getY());
		$z = abs(p.getZ());
		$max = $x > $y ? $x : $y;
		if ( $max < $z ) $max = $z;	
		$point->setMetric( $max );
	}
}


// Добавляем точке функционал Chebyshev
$point = new Point2d( 1, 2 );
$visitor = new Chebyshev();
$point->accept($visitor);
echo 'Point2d - Chebyshev: '.$point->getMetric().'<br>';

// Добавляем точке функционал Euclid
$point = new Point3d( 1, 2, 3 );
$visitor = new Euclid();
$point->accept($visitor);
echo 'Point3d - Euclid: '.$point->getMetric().'<br>';

