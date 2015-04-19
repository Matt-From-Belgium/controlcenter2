<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/entity/ajaxresponse.php';

function generatePageToken()
{
    ##We genereren een pageToken
    ###Alle toegangen zouden ok moeten zijn. We zullen dus gewoon proberen om het token op
    ###te halen
    
    $response = new ajaxResponse('ok');
    $response->addField('resultcode');
    
    require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/parameters.php";
    require $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';
    Facebook\FacebookSession::setDefaultApplication(getFacebookAppID(), getFacebookSappId());
    
    ###We nemen de javascript sessie over
    $helper = new Facebook\FacebookJavaScriptLoginHelper;
    $session = $helper->getSession();
    
    $extendedSession = $session->getLongLivedSession();
    
    ###We halen het pagetoken op voor de betreffende pagina
    $apipath = '/'.$_POST['pageid'].'?fields=access_token,id';
    
    $fbresponse = (new Facebook\FacebookRequest($extendedSession, 'GET', $apipath))->execute();
    $object=$fbresponse->getGraphObject();
    
    ###Nu hebben we een pagetoken dat niet vervalt
    $token = $object->getProperty('access_token');
    
    setFacebookPageId($_POST['pageid']);
    setFacebookPageToken($token);
    
    return $response->getXML();
}

?>