<?php

namespace OpenCloud\Base\Request\Response;

class Blank extends Http {
	public
		$errno,
		$error,
		$info,
		$body,
		$headers=array(),
		$status=200,
		$rawdata;
	public function __construct($values=array()) {
		foreach($values as $name => $value)
			$this->$name = $value;
	}
	public function HttpBody() { return $this->body; }
	public function HttpStatus() { return $this->status; }
}