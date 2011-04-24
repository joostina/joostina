<?php

class Mixin {

	private $mixed = array();

	public function __get($name) {
		foreach ($this->mixed as $object) {
			if (property_exists($object, $name))
				return $object->$name;
		}

		throw new Exception('Property $name is not defined.');
	}

	public function __set($name, $value) {
		foreach ($this->mixed as $object) {
			if (property_exists($object, $name))
				return $object->$name = $value;
		}

		throw new Exception('Property $name is not defined.');
	}

	public function __isset($name) {
		foreach ($this->mixed as $object) {
			if (property_exists($object, $name) && isset($this->$name))
				return true;
		}

		return false;
	}

	public function __unset($name) {
		foreach ($this->mixed as $object) {
			if (property_exists($object, $name))
				$object->$name = null;
		}
	}

	public function __call($name, $parameters) {
		foreach ($this->mixed as $object) {
			if (method_exists($object, $name))
				return call_user_func_array(array($object, $name), $parameters);
		}

		throw new Exception("Method $name is not defined.");
	}

	public function mix($name, $class) {
		return $this->mixed[$name] = new $class();
	}

}

class A extends Mixin {
	
}

class B {

	public $foo = "barn";

	function test() {
		echo "Success!n";
	}

}

class C {

	public $foo = "barn-C";

	function test() {
		echo "Success!-C";
	}

}

$a = new A();
$a->mix('b', 'B');
$a->mix('c', 'C');
$a->test();
echo $a->foo;