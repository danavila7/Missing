<?php

class HomeController extends BaseController {
	protected $layout = 'layouts.layout';

	public function showHome()
	{
		$this->layout->content = View::make('objetos.index');
	}
}
