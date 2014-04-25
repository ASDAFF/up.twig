<?php

namespace UP\Twig;

class BitrixExtension extends \Twig_Extension
{
	const DEFAULT_TEMPLATE_PATH = "/bitrix/templates/.default";

	public function getName()
	{
		return 'bitrix';
	}

	public function getGlobals()
	{
		global $APPLICATION;

		return array(
			'app' => $APPLICATION,
			"site" => array(
				"lang" => LANGUAGE_ID,
				"dir" => SITE_DIR,
				"charset" => LANG_CHARSET,
				"template" => array(
					"id" => SITE_TEMPLATE_ID,
					"path" => SITE_TEMPLATE_PATH
				)
			),
			"POST_FORM_ACTION_URI" => POST_FORM_ACTION_URI
		);
	}

	public function getFunctions()
	{
		$funcs = array();
		$functions = array('GetMessage', 'bitrix_sessid_get', 'bitrix_sessid_post', 'ShowNote', 'ShowError', 'ShowMessage');

		foreach ($functions as $function)
		{
			$funcs[] = new \Twig_SimpleFunction($function, $function);
		}

		$funcs[] = new \Twig_SimpleFunction(
			'includeScript',
			array(
				$this,
				"includeScript"
			),
			array(
				"needs_environment" => true
			)
		);

		return $funcs;
	}

	public function includeScript($env, $path)
	{
		$loader = $env->getLoader();
		
		if ($loader === null) { return; }

		$isRelativePath = !(preg_match("/^@/", $path));

		foreach($loader->getNamespaces() as $namespace)
		{
			if ($isRelativePath || preg_match("/@".$namespace."/", $path))
			{
				foreach($loader->getPaths($namespace) as $templatesPath)
				{
					$templatesPath = $isRelativePath ? $templatesPath . "/" . $path : preg_replace("/@".$namespace."/", $templatesPath, $path);

					if (file_exists($templatesPath)) {
						global $APPLICATION;
						include $templatesPath;
						return;
					}
				}
			}

		}
	}
}