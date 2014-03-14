function registerWithFacebook(onCompleteFunction,manualPermissions)
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
                       
                       //Gebruiker heeft de toegang verleend
                       //We kunnen dus beginnen met gegevens ophalen
                       //MAAR: facebookStatus heeft hier geen waarde omdat de gebruiker onload nog niet
                       //verbonden was met onze app.
                         //getFacebookUserDetails();
                       //MAAR: We zijn niet zeker of al onze toegangen goedgekeurd zijn.
                       
                       var desiredPermissionArray = scope.split(',');
                       //We vragen een overzicht van de toegestande permissions
                       FB.api('/me/permissions','get',function(response){
                           var grantedPermissions=response.data[0];
                           
                           
                           var grantedPermissionsArray = new Array();
                           //Facebook geeft een JSON object terug met de permissions
                           for(var key in grantedPermissions)
                           {
                               grantedPermissionsArray.push(key);
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
                               //Niet alle toegangen zijn goedgekeurd, het heeft geen zin om verder te gaan
                               console.log('not all necessary permissions have been granted');
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
                 }, {scope: scope});
           /*  }*/
       }
    }
    else
        {
            alert('hang on... Facebook plugins are still being loaded...');
        }
}

function getFacebookUserDetails()
{
    FB.api('/me','get',function(response){
        
        //We hebben nu de gegevens en we kunnen deze invullen in het formulier
        document.getElementById('username').value = response.first_name+' '+response.last_name;
        document.getElementById('facebookid').value = response.id;
        
        document.getElementById('mail').value = response.email;
        
        document.getElementById('firstname').readOnly = true;
        document.getElementById('firstname').value = response.first_name;
        
        document.getElementById('lastname').readOnly = true;
        document.getElementById('lastname').value = response.last_name;
        
        blockPasswordFields();

    });
}

function blockPasswordFields()
{
        document.getElementById('password').disabled = true;
        document.getElementById('password2').disabled = true;
}
          
function getFacebookScope()
{
    var ajax = new ajaxTransaction;
    ajax.destination = '/core/logic/parameters.php';
    ajax.phpfunction='getFacebookScopeAjax';
    ajax.onComplete = function(){
        
    };

    ajax.ExecuteRequest();
}