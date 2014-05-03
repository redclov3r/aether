<?php

abstract class AbstractSettings {
	public $url_prefix = "";
	abstract public function get_routes();
}
