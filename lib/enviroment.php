<?php

namespace UP\Twig;

class Enviroment
{
	private $env;

	function __construct($loader)
	{
		$this->env = new \Twig_Environment(
			$loader,
			array(
				"cache" => $_SERVER["DOCUMENT_ROOT"]."/bitrix/cache/twig/",
				"auto_reload" => true
			)
		);

		$this->addExtensions();

		$rsEvents = GetModuleEvents("up.twig", "onEnviromentConstruct");

		while ($arEvent = $rsEvents->Fetch())
		{
			ExecuteModuleEvent($arEvent, $this->env);
		}
	}

	private function addExtensions()
	{
		$this->env->addExtension(new BitrixExtension());
	}

	public function render($file, $vars)
	{
		return $this->env->render($file, $vars);
	}

	public function display($file, $vars)
	{
		$this->env->display($file, $vars);
	}
}