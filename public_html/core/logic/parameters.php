<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/dataaccess/parameters.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/entity/ajaxresponse.php';

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

function getSiteMeta()
{
    ###De gegevens die vooral gebruikt worden om de meta voor social media toe te voegen
    $meta['title'] = dataaccess_GetParameter('SITE_META_TITLE')->getValue();
    $meta['description'] = dataaccess_GetParameter('SITE_META_DESCRIPTION')->getValue();
    $meta['image'] = dataaccess_GetParameter('SITE_META_IMAGE')->getValue();
    $meta['url'] = dataaccess_GetParameter('SITE_META_URL')->getValue();
    
    return $meta;
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

function getDebugMode()
{
    ###Deze functie moet aangeven of debug mode actief is.
    ###TRUE = JA
    ###FALSE = NEEN
    
    $debugindicator = dataaccess_getParameter('CORE_DEBUG_MODE');
    
    if($debugindicator->getValue()==1)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function getDebugMailadress()
{
    ###Geeft het mailadres weer waar het eventuele debug rapport naartoe gestuurd moet worden
    $debugmail = dataaccess_GetParameter('CORE_DEBUG_MAIL');
    return $debugmail->getValue();
}

function getFacebookLoginStatus()
{
    ###Geeft true of false terug afhankelijk of facebook gebruikt kan worden om accounts te creëren
    $fbloginstatus = dataaccess_GetParameter('CORE_FB_LOGIN_ENABLED');
    
    if($fbloginstatus->getValue()==1)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function getFacebookLoadApiStatus()
{
    ###Geeft aan of de API standaard moet ingeladen worden op alle pagina's
    $fbapistatus = dataaccess_GetParameter('CORE_FB_LOAD_API');
    
    if($fbapistatus->getValue()==1)
    {
        return true;
    }
    else
    {
        return false;
    }
}

function getFacebookAppID()
{
    ###Deze functie haalt de publieke facebook appid op
    $appid = dataaccess_GetParameter('CORE_FB_APPID');
    
    return $appid->getValue();
}

function getFacebookSappId()
{
    ###Deze functie haalt de geheime appid op
    $sappid = dataaccess_GetParameter('CORE_FB_SAPPID');
    
    return $sappid->getValue();
}

function getFacebookNameSpace()
{
    ###Deze functie haalt de namespace op die gebruikt kan worden voor
    ##API aanroepen
    $namespace = dataaccess_GetParameter('CORE_FB_APP_NAMESPACE');
    
    return $namespace->getValue();
}

function getFacebookScope()
{
    $scope=dataaccess_GetParameter('CORE_FB_SCOPE');
    return $scope->getValue();
}

function getFacebookScopeAjax()
{
    $data = array();
    $data['scope']=getFacebookScope();
    
    $result = new ajaxResponse('ok');
    $result->addField('scope');
    
    $result->addData($data);
    $result->getXML();
    
}

function getFacebookPageId()
{
    ###Geeft het paginaid terug van de FB pagina van de site
    $result = dataaccess_GetParameter('CORE_FB_PAGEID');
    return $result->getValue();
}

function getFacebookPageToken()
{
    ###Geeft de longterm page token terug van de FB pagina
    $result = dataaccess_GetParameter('CORE_FB_PAGETOKEN');
    return $result->getValue();
}

function setFacebookPageId($value)
{
    $result = dataaccess_GetParameter('CORE_FB_PAGEID');
    $result->setValue($value);
    dataaccess_EditParameter($result);
}

function setFacebookPageToken($value)
{
    $result = dataaccess_GetParameter('CORE_FB_PAGETOKEN');
    $result->setValue($value);
    dataaccess_EditParameter($result);
}

function getSSLenabled()
{
    $parameter = dataaccess_GetParameter('CORE_SSL_ENABLED');
    return $parameter->getValue();
}

function getMaintenanceEnabled()
{
    ###Deze functie gaat na of de site in onderhoudsmodus staat
    $parameter = dataaccess_GetParameter('CORE_MAINTENANCE_MODE');
    
    if($parameter->getValue()==1)
    {
        return true;
    }
    else
    {
        return false;
    }
}
?>