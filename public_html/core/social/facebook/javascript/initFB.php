<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/parameters.php';

    $appid = getFacebookAppID();
    $desiredscope = getFacebookScope();
    $appnamespace = strtolower(getFacebookNameSpace());
?>
           (function () {
            function CustomEvent ( event, params ) {
              params = params || { bubbles: false, cancelable: false, detail: undefined };
              var evt = document.createEvent( 'CustomEvent' );
              evt.initCustomEvent( event, params.bubbles, params.cancelable, params.detail );
              return evt;
             };

            CustomEvent.prototype = window.CustomEvent.prototype;

            window.CustomEvent = CustomEvent;
          })();
          

          function facebookStatus(){
            this.appNamespace = '<?php echo $appnamespace?>';
            this.sdkLoaded = false;
            this.userID = null;
            this.authStatus = false;
            this.desiredScope = '<?php echo $desiredscope?>';
            this.grantedPermissions = null;
          };
        
        
        var facebookStatus = new facebookStatus();
        
        //We gaan de channelURL genereren
        var hostname = window.location.hostname;
        var channel = 'http://'+hostname+'/core/social/facebook/javascript/channel.html';
        

          window.fbAsyncInit = function() {
            // init the FB JS SDK
            FB.init({
              appId      : '<?php echo $appid?>',                        // App ID from the app dashboard
              channelUrl : channel, // Channel file for x-domain comms
              status     : true,                                 // Check Facebook Login status
              cookie     : true,                                 //Share session with PHP
              xfbml      : true,                                  // Look for social plugins on the page
              version    : 'v2.1'
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
                           var grantedPermissions = response.data;
                           var grantedPermissionsArray = new Array();
                           
                           for(i=0;i<grantedPermissions.length;i++)
                           {
                               //vanaf api v2 zit er ook een statusveld in de permission
                               //Voor toegestane permissions staat dit op granted
                               //Nog geen permissions gezien die iets anders hebben...
                               var grantedPermission= grantedPermissions[i];
                               if(grantedPermission.status==='granted')
                               {
                                    //alert(grantedPermission.permission);
                                    grantedPermissionsArray.push(grantedPermission.permission);
                               }
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
             js.src = "//connect.facebook.com/en_US/sdk.js";
             fjs.parentNode.insertBefore(js, fjs);
           }(document, 'script', 'facebook-jssdk'));
           
           <?php
            ###checkFBAccount wordt enkel gebruikt wanneer CORE_FB_LOGIN_ENABLED = 1
            if(getFacebookLoginStatus())
            {
           ?>
           document.addEventListener('fbSDKLoaded',checkFBAccount,false);
           <?php
            }
           ?>
           
           function getFacebookScope()
            {
                var ajax = new ajaxTransaction;
                ajax.destination = '/core/logic/parameters.php';
                ajax.phpfunction='getFacebookScopeAjax';
                ajax.onComplete = function(){

                };

                ajax.ExecuteRequest();
            }
            
            function registerWithFacebook(onCompleteFunction,manualPermissions,onFailFunction)
            {  

                if(facebookStatus.sdkLoaded)
                {
                    //We gaan eerst de scope opbouwen die we voor deze request willen bereiken
                    //Als manualPermissions geen waarde heeft gebruiken we de desiredscope
                    if(!manualPermissions)
                    {
                       var scope = facebookStatus.desiredScope;

                    }
                    else
                    {
                       //Als manualPermissions wel een waarde heeft voegen we die toe aan desiredscope
                       manualPermissions.toLowerCase();
                       var desiredscope = facebookStatus.desiredScope;
                       var scope = desiredscope + ',' + manualPermissions;

                    }  

                    if(facebookStatus.authStatus==='connected')
                    {
                        //We gaan de scope opbreken in aparte permissions en die vergelijken met de
                        //toegestane permissions. Zo kunnen we nagaan of het nodig is om de login flow
                        //nogmaals te laten lopen
                        var scopeArray = new Array();
                        var scopeArray = scope.split(',');

                        var grantedPermissionsArray = facebookStatus.grantedPermissions;

                        var allPermissionsGranted = true;

                        for(i=0;i<scopeArray.length;i++)
                        {
                            if(grantedPermissionsArray.indexOf(scopeArray[i])<0)
                            {
                                allPermissionsGranted = false;
                            }
                        }
                    }
                    else
                    {
                        var allPermissionsGranted = false;
                    }

                    if(allPermissionsGranted)
                    {
                        //We moeten niet langs de login-flow, alles is toegekend
                        //We mogen de onCompleteFunction dus uitvoeren
                        if(typeof onCompleteFunction != 'function')
                            {
                                throw 'onCompleteFunction is not an actual function';
                            }
                            else
                             {
                                 //refreshFacebookLoginStatus();
                                 onCompleteFunction();

                                 //We halen de gegevens op van de gebruiker en werken facebookStatus bij

                             }      
                    }
                    else
                    {

                            //Eén of meerdere toegangsrechten zijn niet toegekend => we moeten de login
                            //flow opstarten

                             FB.login(function(response){
                               if(response.authResponse)
                               {

                                   //Gebruiker heeft de toegang verleend (of toch op zijn minst basistoegang)
                                   //We kunnen dus beginnen met gegevens ophalen
                                   //MAAR: facebookStatus heeft hier geen waarde omdat de gebruiker onload nog niet
                                   //verbonden was met onze app.
                                     //getFacebookUserDetails();
                                   //MAAR: We zijn niet zeker of al onze toegangen goedgekeurd zijn.

                                   var desiredPermissionArray = scope.split(',');
                                   //We vragen een overzicht van de toegestande permissions
                                   FB.api('/me/permissions','get',function(response){
                                       var grantedPermissions=response.data;


                                       var grantedPermissionsArray = new Array();

                                       for(i=0;i<grantedPermissions.length;i++)
                                       {
                                           //vanaf api v2 zit er ook een statusveld in de permission
                                           //Voor toegestane permissions staat dit op granted
                                           //Nog geen permissions gezien die iets anders hebben...
                                           var grantedPermission= grantedPermissions[i];
                                           if(grantedPermission.status==='granted')
                                           {
                                                //alert(grantedPermission.permission);
                                                grantedPermissionsArray.push(grantedPermission.permission);
                                           }
                                       }

                                       var allPermissionsGranted = true;

                                       for(i=0;i<desiredPermissionArray.length;i++)
                                       {
                                           if(grantedPermissionsArray.indexOf(desiredPermissionArray[i])<0)
                                           {
                                               allPermissionsGranted = false;
                                           }
                                       }

                                       //als allPermissionsGranted hier nog altijd true is dan hebben we wat we nodig hadden
                                       if(allPermissionsGranted)
                                       {
                                            if(typeof onCompleteFunction != 'function')
                                            {
                                                throw 'onCompleteFunction is not an actual function';
                                            }
                                            else
                                             {
                                                 refreshFacebookLoginStatus();
                                                 onCompleteFunction();

                                                 //We halen de gegevens op van de gebruiker en werken facebookStatus bij

                                             }      
                                       }
                                       else
                                       {
                                           //Niet alle toegangen zijn goedgekeurd, het heeft geen zin om verder te gaan.
                                           //MAAR: er kunnen gedeeltelijke toegangen zijn die nuttig zijn voor andere functies op
                                           //dezelfde pagina => toch refreshen

                                           refreshFacebookLoginStatus();

                                           //Als onFailFunction in gevuld is wordt deze uitgevoerd, anders gebeurt er niks
                                           if(onFailFunction)
                                           {
                                               if(typeof onFailFunction!= 'function')
                                               {
                                                   throw 'onCompleteFunction is not an actual function';
                                               }
                                               else
                                               {
                                                   onFailFunction();
                                               }
                                           }
                                       }
                                   });
                                   /*
                                       if(typeof onCompleteFunction != 'function')
                                       {
                                           throw 'onCompleteFunction is not an actual function';
                                       }
                                       else
                                        {
                                            refreshFacebookLoginStatus();
                                            onCompleteFunction();

                                            //We halen de gegevens op van de gebruiker en werken facebookStatus bij

                                        }                       
                                     */
                               }
                               else
                               {
                                   //De gebruiker heeft de basistoegang niet verleend, dus de rest ook niet => onFailFunction uitvoeren als die gedefinieerd is
                                   if(onFailFunction)
                                           {
                                               if(typeof onFailFunction!= 'function')
                                               {
                                                   throw 'onCompleteFunction is not an actual function';
                                               }
                                               else
                                               {
                                                   onFailFunction();
                                               }
                                           }
                               }
                             }, {scope: scope, /*Vanaf api v2.0 moet dit erbij om meerdere keren dezelfde permission te kunnen vragen, het werkt zelfs bij eerste poging => we gebruiken het altijd*/auth_type: 'rerequest'});
                       /*  }*/
                   }
                }
                else
                    {
                        alert('hang on... Facebook plugins are still being loaded...');
                    }
            }