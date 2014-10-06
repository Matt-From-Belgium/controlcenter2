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
        document.getElementById('password1').disabled = true;
        document.getElementById('password2').disabled = true;
        
        //aangezien er geen wachtwoord moet opgegeven worden moet er ook niet gehashed worden
        document.getElementById('phash').value=0;
}