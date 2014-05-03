<?php

class HttpResponse {
	public $headers;
	public $body;
	public $status_code = 200;

	public function __construct($body="", $headers=array()) {
		$this->headers = $headers;
		$this->body = $body;
	}
}

class HttpResponseNotFound extends HttpResponse {
	public $status_code = 404;
}

class HttpResponseServerError extends HttpResponse {
	public $status_code = 500;
}
