<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

include_once "vendor/autoload.php";

// bitrix autoload

CModule::AddAutoloadClasses(
	'up.twig',
	array(
		'\UP\Twig\BitrixExtension' => 'lib/bitrixExtension.php',
		'\UP\Twig\Enviroment' => 'lib/enviroment.php',
		'\UP\Twig\Template' => 'lib/template.php',
		'\UP\Twig\ComponentTemplate' => 'lib/componentTemplate.php',
		'\UP\Twig\Handlers' => 'lib/handlers.php',
	)
);

// register bitrix func

if (!function_exists("upRenderTwigTemplate"))
{
	function upRenderTwigTemplate($templateFile, $arResult, $arParams, $arLangMessages, $templateFolder, $parentTemplateFolder, $template)
	{
		global $arLangMessages;

		$twig = new \UP\Twig\ComponentTemplate($templateFile);

		$vars = array(
			'result' => $arResult,
			'params' => $arParams,
			'lang' => $arLangMessages,
			'templateFolder' => $templateFolder,
			'parentTemplateFolder' => $parentTemplateFolder,
			'template' => $template,
		);

		if ($arParams["SAVE_HTML"] === true)
		{
			$arResult["HTML"] = $twig->render($vars);
		}
		else
		{
			$twig->display($vars);
		}

		$twig->epilog($template);
	}
}