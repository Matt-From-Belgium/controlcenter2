function registerWithFacebook(onCompleteFunction)
{
    if(facebookStatus.sdkLoaded)
    {
        
          if(facebookStatus.authStatus==='connected')
              {
                  //De gebruiker heeft al toegang verleend, is er niet al een account gemaakt
                  //voor deze gebruiker?
                  //getFacebookUserDetails();
                  //alert('connected');
                           if(typeof onCompleteFunction != 'function')
                           {
                               throw 'onCompleteFunction is not an actual function';
                           }
                           else
                            {

                                onCompleteFunction();
                            }
              }
         else
             {
                 //Ofwel is er nog geen toegang verleend of de gebruiker is niet ingelogd.
                 //OF THIRD PARTY COOKIES DISABLED!
                 //We moeten sowieso passeren via de login flow
                 
                 //We halen eerst de scope op die moet toegekend worden door de gebruiker
                 var scope = getFacebookScope();
                 
                 FB.login(function(response){
                   if(response.authResponse)
                   {
                       //Gebruiker heeft de toegang verleend
                       //We kunnen dus beginnen met gegevens ophalen
                       //MAAR: facebookStatus heeft hier geen waarde omdat de gebruiker onload nog niet
                       //verbonden was met onze app.
                         //getFacebookUserDetails();
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
                 }, {scope: facebookStatus.desiredScope});
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