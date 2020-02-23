<?php


class DataModel
{
	public function __construct() {
		return new stdClass();
	}

	public function __get($name) {
		if(property_exists($this, $name)) {
			return $this->$name;
		}
		else {
			return null;
		}
	}

	public function __call($name, $arguments) {
		if(method_exists($this, $name)) {
			return $this->$name();
		}
		else {
			return new self();
		}
	}
}