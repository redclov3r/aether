<?php

require_once('../framework/http/HttpResponse.php');

/**
 * A basic application class
 *
 * @author Philipp Kreutzer
 */
class Application {
	public function __construct($settings) {
		$this->settings = $settings;
	}

	public function autoload($class) {
		// TODO: Remove
		$filename = sprintf('../framework/%s.php', str_replace("\\", DIRECTORY_SEPARATOR, $class));
		require_once($filename);
	}
	
	/**
	* Maps a request to a view class and call its dispatch method
	*
	* @param HttpRequest $request The request to dispatch
	*
	* @return HttpResponse The response from the view class
	*/
	public function dispatch($request) {
		foreach ($this->settings->get_routes() as $pattern => $viewclass){
			if (preg_match("#".$pattern."#u", $request->path, $matches)) {
				$parameters = $this->separate_array($matches);
				$view = new $viewclass;
				try {
					return call_user_func_array([$view, "dispatch"], [$request, $parameters['named']]);
				} catch (Exception $e) {
					return $this->handle_execption($e);
				}
			}
		}
		return new HttpResponseNotFound("Not Found");
	}

	protected function handle_execption($e) {
		return new HttpResponseServerError($e->getMessage());
	}

	protected function separate_array($array) {
		$result = array();

		$numeric_keys = array_filter(array_keys($array), "is_numeric");
		$numeric = array_intersect_key($array, array_flip($numeric_keys));
		$named = array_diff_key($array, $numeric_keys);
		return array('numeric' => $numeric, 'named' => $named);
	}
}

