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

function getFacebookJavaCode()
{
    $appid = getFacebookAppID();
    
    $desiredscope = getFacebookScope();
    
    $code = "<div id=\"fb-root\"></div>
        <script type='text/javascript' SRC='/core/presentation/ajax/ajaxtransaction.js'></script>
        <script>


          function facebookStatus(){
            this.sdkLoaded = false;
            this.userID = null;
            this.authStatus = false;
            this.desiredScope = '$desiredscope';
            this.grantedPermissions = null;
          };
        
        
        var facebookStatus = new facebookStatus();
        

          window.fbAsyncInit = function() {
            // init the FB JS SDK
            FB.init({
              appId      : '$appid',                        // App ID from the app dashboard
              channelUrl : 'http://controlcenter2.dragoneyehosting.be/core/social/facebook/javascript/channel.html', // Channel file for x-domain comms
              status     : true,                                 // Check Facebook Login status
              cookie     : true,                                 //Share session with PHP
              xfbml      : true                                  // Look for social plugins on the page
            });
            
            // Additional initialization code such as adding Event Listeners goes here                 



            refreshFacebookLoginStatus();


          };
          
          function refreshFacebookLoginStatus()
          {
                var message = null;
                var userID = null;

                 FB.getLoginStatus(function(response){
                    if(response.status === 'connected')
                    {
                        //Ingelogd + auth ok
                        message = 'connected';
                        userID = response.authResponse.userID;
                        
                        //Voor we het signaal geven dat de SDK geladen is vragen
                        //we ook de permissions op
                        FB.api('/me/permissions','get',function(response)
                        {
                           //We zetten het JSON object in response.data[0] om in een array
                           var grantedPermissions = response.data[0];
                           var grantedPermissionsArray = new Array();
                           
                           for(var key in grantedPermissions)
                           {
                               grantedPermissionsArray.push(key);
                           }
                           
                           //We zijn, klaar.
                           dispatchSdkLoadedEvent(message,userID,grantedPermissionsArray);
                        });

                        
                    }
                else if(response.status === 'not_authorized')
                    {
                        //Ingelogd maar geen auth
                        message = 'not_authorized';
                        dispatchSdkLoadedEvent(message,userID,null);
                    }
                else
                    {
                        //Niet ingelogd, we weten dus helemaal niks;
                        //of... third party cookies disabled
                        message = 'unknown';
                        dispatchSdkLoadedEvent(message,userID,null);
                    }
                    
                 
            });
          }

          function dispatchSdkLoadedEvent(message,userID,grantedPermissions)
          {
            var fbSDKLoadedEvent = new CustomEvent('fbSDKLoaded',
                         {
                                 detail: {
                                         status: message,
                                         userID: userID,
                                         grantedPermissions: grantedPermissions,
                                 },
                                 bubbles: true,
                                 cancelable: true
                         }
                         );
                
                 document.getElementById('fb-root').dispatchEvent(fbSDKLoadedEvent);
          }

          //We linken hier een functie aan het fbSDKLoadedEvent zodat we
          //facebookStatus kunnen invullen met de juiste waarden
          document.addEventListener('fbSDKLoaded',saveFbParams,false);
          
          function saveFbParams(e)
          {
            facebookStatus.sdkLoaded = true;
            facebookStatus.userID= e.detail.userID;
            facebookStatus.authStatus = e.detail.status;
            facebookStatus.grantedPermissions=e.detail.grantedPermissions;
          }
          
          
          
            function checkFBAccount()
            {
                
                //De functie wordt gestart wanneer de SDK geladen is
                //Als de gebruiker niet verbonden is met Facebook heeft het geen zin om verder te zoeken
                if((facebookStatus.authStatus==='connected'))
                {
                    var ajax = new ajaxTransaction();
                    ajax.destination = '/core/logic/usermanagement/fbLoginAjax.php';
                    ajax.phpfunction = 'checkFBAccount';

                    ajax.onComplete = function(){
                        if(ajax.successIndicator)
                            {
                                //alert(ajax.result[0].userId);
                                
                                //We lanceren een event zodat we de gewijzigde logintoestand kunnen opvangen
                                        var userLoggedIn = new CustomEvent('userLoggedIn',
                                        {
                                                detail: {
                                                        
                                                },
                                                bubbles: true,
                                                cancelable: true
                                        }
                                        );
                                document.getElementById('fb-root').dispatchEvent(userLoggedIn);
                            }
                            else{
                                //Waarde 1 betekent dat er geen gebruiker gevonden is met dit facebookid
                                //Waarde 2 betekent dat de account nog geactiveerd moet worden door de admin
                                //Waarde 3 betekent dat er al een gebruiker ingelogd is
                                //alert(ajax.errorList[0].value);
                                
                            }
                    };

                    ajax.ExecuteRequest();
                }
            }

          // Load the SDK asynchronously
          (function(d, s, id){
             var js, fjs = d.getElementsByTagName(s)[0];
             if (d.getElementById(id)) {return;}
             js = d.createElement(s); js.id = id;
             js.src = \"//connect.facebook.net/en_US/all.js\";
             fjs.parentNode.insertBefore(js, fjs);
           }(document, 'script', 'facebook-jssdk'));
           
           document.addEventListener('fbSDKLoaded',checkFBAccount,false);

           

        </script>";
    
    return $code;
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
?>