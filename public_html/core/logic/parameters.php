<?php
function getLanguage()
{
	#Deze functie haalt de parameter CORE_LANGUAGE op maar koppelt deze ook onmiddelijk aan een taal in stringformaat
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/parameters.php";
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/language.php";
	
	$taalinteger=dataaccess_getParameter("CORE_LANGUAGE");
	$taalstring = dataaccess_getLanguagestring($taalinteger->getValue());
	return $taalstring;
}

function getServerMailadress()
{
	###Deze functie haalt de parameter CORE_SERVER_MAILADRESS op
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/parameters.php";
	$servermailadress = dataaccess_GetParameter("CORE_SERVER_MAILADRESS");
	return $servermailadress->getValue();
}

function getUserActivationParameter()
{
	###Deze functie gaat na of de parameter CORE_USER_SELF_ACTIVATION op 0 of 1 staat en geeft een boolean terug
	$selfactivationparameter = dataaccess_GetParameter("CORE_USER_SELF_ACTIVATION");
	
	if($selfactivationparameter->getValue() == 0)
	{
		###parameterwaarde is 0 => geen gebruikersactivatie nodig => false
		return false;
	}
	else
	{
		###parameterwaarde is 1 => wel gebruikersactivatie nodig => true
		return true;
	}
}

function getAdminActivationParameter()
{
	###Deze functie gaat na of de parameter CORE_USER_ADMIN_ACTIVATION op 0 of 1 staat en geeft een boolean terug
	$selfactivationparameter = dataaccess_GetParameter("CORE_USER_ADMIN_ACTIVATION");
	
	if($selfactivationparameter->getValue() == 0)
	{
		###parameterwaarde is 0 => geen gebruikersactivatie nodig => false
		return false;
	}
	else
	{
		###parameterwaarde is 1 => wel gebruikersactivatie nodig => true
		return true;
	}
}

function getSiteName()
{
	###Deze functie geeft de naam van de site terug zoals die opgeslagen staat in CORE_SITE_NAME
	$sitename = dataaccess_GetParameter("CORE_SITE_NAME");
	return $sitename->getValue();
}

function getCaptchaPublicKey()
{
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/parameters.php";	
	###Deze functie haalt de publieke reCaptcha sleutel op
	$publickey = dataaccess_getParameter("CORE_RECAPTCHA_PUBLIC");
	return $publickey->getValue();
}

function getCaptchaPrivateKey()
{
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/parameters.php";
	###Deze functie haalt de private reCaptcha sleutel op
	$privatekey = dataaccess_getParameter("CORE_RECAPTCHA_PRIVATE");
	return $privatekey->getValue();
}

function getSelfRegisterStatus()
{
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/parameters.php";
	
	###Deze functie gaat na of registratie door gebruikers zelf toegestaan is.
	$status = dataaccess_getParameter("CORE_USER_EXT_REGISTRATION")->getValue();
	
	if($status=="1")
	{
		
		return true;
	}
	else
	{
		return false;
	}
	
}

function getNoAccessURL()
{
	###Deze functie haalt de waarde op van de parameter CORE_NOACCESS_URL
	require_once $_SERVER['DOCUMENT_ROOT']."/core/dataaccess/parameters.php";
	
	$url = dataaccess_getParameter("CORE_NOACCESS_URL")->getValue();
	
	return $url;
}
?>