<?php

abstract class View {
	public function dispatch($request, $parameters) {
		$request_method = strtolower($request->method);
		if (method_exists($this, $request_method)) {
			return call_user_func_array(array($this, $request_method), [$request, $parameters]);
		} else {
			throw new Exception("Method not found");
		}
	}
}

trait TemplateTrait {
	public $template = "<h1>Framework</h1><p>%s</p>";

	public function get_template() {
		return $this->template;
	}
	protected function get_context() {
		return "";
	}

	public function get($request, $parameters) {
		$string = sprintf($this->get_template(), $this->get_context($request, $parameters));
		$response = new HttpResponse($string);
		return $response;
	}
}

