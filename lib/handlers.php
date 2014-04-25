<?php

namespace UP\Twig;

class Handlers
{
	static function register()
	{
		global $arCustomTemplateEngines;

		$arCustomTemplateEngines = array(
			"twig" => array(
				"templateExt" => array("twig"),
				"function" => "upRenderTwigTemplate"
			)
		);
	}
}