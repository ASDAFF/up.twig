<?php

namespace UP\Twig;

class ComponentTemplate
{
	protected $env;
	protected $loader;
	protected $templateDir;
	protected $templateFile;

	function __construct($templateFile)
	{
		$this->templateDir = dirname($templateFile);
		$this->templateFile = basename($templateFile);

		$this->loader = $this->createLoader($this->templateDir);
		$this->env = new Enviroment($this->loader);

		$this->includeLang();
	}

	private function createLoader($templatePath)
	{
		$loader = new \Twig_Loader_Filesystem($templatePath);
		$loader->addPath($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/", "templateRoot");
		$loader->addPath($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/template/", "template");
		$loader->addPath($_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/components/", "templateComponents");

		return $loader;
	}

	private function defaultParams()
	{
		return array();
	}

	function includeLang()
	{
		IncludeTemplateLangFile($_SERVER["DOCUMENT_ROOT"] . "/" . $this->templateDir . "/" . str_replace(".twig", ".php", $this->$basename));
	}

	function display($vars)
	{
		$this->env->display($this->templateFile, array_merge_recursive($this->defaultParams(), $vars));
	}

	function render($vars)
	{
		return $this->env->render($this->templateFile, array_merge_recursive($this->defaultParams(), $vars));
	}

	public function epilog($template)
	{
		$component_epilog = $this->templateDir . "/component_epilog.php";

		if (file_exists($component_epilog) && $template)
		{
			$template->__component->SetTemplateEpilog(
				array(
					"epilogFile" => str_replace($_SERVER["DOCUMENT_ROOT"], "", $component_epilog),
					"templateName" => $template->__name,
					"templateFile" => $template->__file,
					"templateFolder" => $template->__folder,
					"templateData" => false,
				)
			);
		}
	}
}