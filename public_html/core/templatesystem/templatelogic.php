<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatedata.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatefile.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/mobiledetect/mobiledetect.php";

function AliasGetlinkeddir($alias)
{
        ###Templateystem R3: we moeten detecteren welk platform gebruikt wordt
        $platform = 'pc';
        
        $detection = new Mobile_Detect();
        if($detection->isTablet())
        {
            $platform = 'tablet';
        }
        else
        {
            if($detection->isMobile())
            {
                $platform = 'phone';
            }
        }
    
	return DataAccess_AliasGetlinkeddir($alias,$platform);
}

function GetTemplatehtml($directory)
{
	return fileaccess_gettemplatehtml($directory);
}

function GetAddinHTML($path)
{
	return fileaccess_GetAddinHTML($path);
}

function setCookiesOk()
{
    require_once $_SERVER['DOCUMENT_ROOT'].'/core/entity/ajaxresponse.php';
    setcookie("cookies","ok",time()+10*365*24*60*60,'/');
    
    $response = new ajaxResponse('ok');
    return $response->getXML();
}

?>
