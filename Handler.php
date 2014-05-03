<?php

require_once('../framework/http/HttpRequest.php');

class Handler {
	protected $application;
	protected $settings;

	public function __construct($application, $settings) {
		$this->application = $application;
		$this->settings = $settings;
	}

	public function build_request() {
		$request = new HttpRequest();
		$path_pattern = '#^'.preg_quote($this->settings->url_prefix, '#').'(.*?)'.'(\?'.preg_quote($_SERVER['QUERY_STRING'], '#').')?$#';
		preg_match($path_pattern, $_SERVER['REQUEST_URI'], $path_matches);
		$request->path = $path_matches[1];
		$request->method = strtoupper($_SERVER['REQUEST_METHOD']);
		$request->GET = $_GET;
		$request->POST = $_POST;

		return $request;
	}


	public function get_response() {
		$request = $this->build_request();
		$response = $this->application->dispatch($request);
		return $response;
	}

	public function run() {
		$response = $this->get_response($request);
		http_response_code($response->status_code);
		echo $response->body;
	}
}

