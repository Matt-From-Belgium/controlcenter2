<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/fileaccess/languages.php";

function getLanguageFiles()
{
		#Deze functie haalt de taalconstanten op. De taal waarvoor de constanten moeten worden opgehaald wordt afgeleid 
		require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/parameters.php";
		
		$languagestring = getlanguage();
		
		#Eerst wordt de algemene string geopend.
		getCoreLanguageFile($languagestring);
		
		$thispage = $_SERVER['PHP_SELF'];
		
		#Eerst kijken of de pagina onderdeel is van een CORE component
		if(preg_match("/\/core\/(presentation|logic)\/([^\/]+)\//",$thispage,$componentname))
		{
			getComponentLanguageFile($componentname[2],$languagestring);
		}
		elseif(preg_match("/\/modules\/([^\/]+)\//",$thispage,$modulename))
		{
			getModuleLanguageFile($modulename[2],$languagestring);
		}
}

function getComponentLanguageFile($componentname,$languagestring)
{
	fileaccess_getComponentLanguageFile($componentname,$languagestring);
}


function getModuleLanguageFile($modulename,$languagestring)
{
	fileaccess_getModuleLanguageFile($modulename,$languagestring);
}

function getCoreLanguageFile($languagestring)
{
	fileaccess_getCoreLanguageFile($languagestring);
}

function getLanguageFilesManually($type,$itemname)
{
	##Deze functie wordt gebruikt om de taalconstanten in te laden met AJAX.
	##Er wordt een type opgegeven (component of module) en een naam van component/module
	##De core languagefile wordt ingeladen en de bijkomende file.
	require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/parameters.php";
	
	$languagestring = getlanguage();
	
	##Openen van de core languagefile
	getCoreLanguageFile($languagestring);
	
	$type=strtolower($type);
	
	if($type=="component")
	{
		getComponentLanguageFile($itemname,$languagestring);
	}
	elseif($type=="module")
	{
		getModuleLanguageFile($itemname,$languagestring);
	}
	else
	{
		throw new CC2Exception("The languagesystem caused an error","you tried to use getLanguageFilesManually with an invalid type, only component and module is allowed");
	}
}
?>