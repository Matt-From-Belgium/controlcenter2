<?php
function fileaccess_getCoreLanguageFile($languagestring)
{
	#Deze functie gaat de CORE language files ophalen
	$path = $_SERVER['DOCUMENT_ROOT']."/core/languages/".$languagestring.".php";
	if(file_exists($path))
	{
		require_once($path);
	}
	else
	{
		throw new CC2Exception("The template system caused an error","The CORE languagefile for language $languagestring could not be found at $path");
	}

}

function fileaccess_getComponentLanguageFile($componentname,$languagestring)
{
	#Deze functie haalt een languagefile op voor een CORE component
	$path = $_SERVER['DOCUMENT_ROOT']."/core/presentation/".$componentname."/languages/$languagestring.php";
	if(file_exists($path))
	{
		require_once($path);
	}
	else
	{
		throw new CC2Exception("The template system caused an error","The CORE component languagefile for language $languagestring could not be found at $path");
	}

}

function fileaccess_getModuleLanguageFile($modulename,$languagestring)
{
	#Deze functie haalt een languagefile op voor een CORE component
	$path = $_SERVER['DOCUMENT_ROOT']."/modules/".$modulename."/languages/$languagestring.php";
	if(file_exists($path))
	{
		require_once($path);
	}
	
	#Bugfix: het is niet verplicht om gebruik te maken van het meertaligheidssysteem voor modules. Sommige modules worden
	#gewoon in 1 taal ontwikkeld.
	#else
	#{
	#	throw new CC2Exception("The template system caused an error","The module languagefile for language $languagestring could not be found at $path");
	#}

}
?>