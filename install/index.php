<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

IncludeModuleLangFile(__FILE__);

class up_twig extends CModule {

    public $MODULE_ID = "up.twig";
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;

	private $events = array(
		array(
			"from_module" => "main",
		    "from_event" => "OnProlog",
		    "to_module" => "up.twig",
		    "to_class" => '\UP\Twig\Handlers',
		    "to_method" => 'register'
        )
	);

    function up_twig()
    {
        include(dirname(__FILE__) . "/version.php");

        if (isset($arModuleVersion) && is_array($arModuleVersion))
        {
	        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
	        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

	    $this->MODULE_NAME = GetMessage("UPTWIG_MODULE_NAME");
	    $this->MODULE_DESCRIPTION = GetMessage("UPTWIG_MODULE_DESCRIPTION");
    }

    function DoInstall()
    {
	    foreach($this->events as $event)
	    {
		    RegisterModuleDependences($event["from_module"], $event["from_event"], $event["to_module"], $event["to_class"], $event["to_method"]);
	    }

        RegisterModule($this->MODULE_ID);
    }


    function DoUninstall()
    {
	    foreach($this->events as $event)
	    {
		    UnRegisterModuleDependences($event["from_module"], $event["from_event"], $event["to_module"], $event["to_class"], $event["to_method"]);
	    }

        UnRegisterModule($this->MODULE_ID);
    }

}