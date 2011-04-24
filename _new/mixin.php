<?php

abstract class Mixin {

	private $clr;

	public function __construct($caller) {
		$this->clr = $caller;
	}

	public function __get($prop) {
		return $this->clr->__access_get_prop($prop);
	}

	public function __set($prop, $value) {
		return $this->clr->__access_set_prop($prop, $value);
	}

	public function __call($method, $args) {
		return $this->clr->__access_call($method, $args);
	}

}

abstract class Caller {

	private $mixins = array(); // массив экземпляров примсей

	public function __call($method, $args) {
		foreach ($this->mixins as $cname => $co) {
			if (method_exists($co, $method)) {
				return call_user_func_array(array($co, $method), $args);
			}
		}
		throw new Exception("call to unfdefined method " . $method);
	}

	public static function __callStatic($method, $args) {
		if (isset(static::$mixins)) {
			foreach (static::$mixins as $cname) {
				if (method_exists($cname, $method)) {
					return call_user_func_array(array($cname, $method), $args);
				}
			}
		}
		throw new Exception("call to unfdefined static method " . $method);
	}

	public function __access_get_prop($prop) {
		if (property_exists($this, $prop)) {
			return $this->$prop;
		}
	}

	public function __access_set_prop($prop, $value) {
		if (property_exists($this, $prop)) {
			return $this->$prop = $value;
		}
	}

	public function __access_call($method, $args) {
		if (method_exists($this, $method)) {
			return call_user_func_array(array($this, $method), $args);
		}
	}

	public function mixin($classes) {
		if (!is_array($classes))
			$classes = array($classes);
		foreach ($classes as $class) {
			$this->mixins[$class] = new $class($this);
		}
	}

}

/*
 * использование
 */

// конкретный родительский класс, расширенный примесью
class Person extends Caller {

	static $mixins = array('SayHello');
	public $name;

	function __construct($name) {
		$this->name = $name;
		$this->mixin('SayHello');
		$this->mixin('SayHello2');
	}

	public function loud() {
		return '<b>' . $this->name . '</b>';
	}

	public function on_save() {
		echo 'Родитель on_save<br />';
		echo $this->on_before_save();
	}

	public function saySomething() {
		$this->addPrefix();
		return '<h2>' . $this->name . '</h2>';
	}

}

// конкретная примесь с присущим ей функционалом
class SayHello extends Mixin {

	public function on_before_save() {
		echo 'Доча on_save';
	}

}

class SayHello2 extends Mixin {

	public function on_before_save() {
		echo 'Доча2 on_save';
	}

}

$p = new Person("Schleicher");

$p->on_save();

/*
echo $p->say(); // Hello Schleicher
echo $p->sayLoud(); // Hello <b>Schleicher</b>!!!
echo $p->saySomething(); // <h2>Mr. Schleicher</h2>
echo $p->testStatic();
 * */
