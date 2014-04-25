<?php

namespace UP\Twig;

class Template
{
	protected $env;
	protected $loader;
	protected $templateId;
	protected $templatePath;

	protected $content;

	function __construct($templateId, $templatePath)
	{
		global $APPLICATION;

		$this->templateId = $templateId;
		$this->templatePath = $templatePath;

		$this->loader = $this->createLoader($_SERVER["DOCUMENT_ROOT"] . $templatePath);
		$this->env = new Enviroment($this->loader);

		$this->content = $APPLICATION->EndBufferContentMan();
		$APPLICATION->RestartBuffer();
	}

	private function createLoader($templatePath)
	{
		$loader = new \Twig_Loader_Filesystem($templatePath);
		$loader->addPath($templatePath."/", "root");
		$loader->addPath($templatePath."/components", "components");
		$loader->addPath($templatePath."/template", "template");

		return $loader;
	}

	private function defaultParams()
	{
		return array(
			"template" => array(
				"id" => $this->templateId,
				"path" => $this->templatePath
			),
		    "content" => $this->content
		);
	}

	function display($file = "default", $vars = array())
	{
		$this->env->display("template/".$file.".twig", array_merge_recursive($this->defaultParams(), $vars));
	}

	function render($file = "default", $vars = array())
	{
		return $this->env->render("template/".$file.".twig", array_merge_recursive($this->defaultParams(), $vars));
	}
}