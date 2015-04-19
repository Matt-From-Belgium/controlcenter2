/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function nieuwsbriefFB()
{
    registerWithFacebook(function(){
        FB.api('/me','get',function(response){
           document.getElementById('voornaam').value=response.first_name;
           document.getElementById('familienaam').value=response.last_name;
           document.getElementById('mailadres').value=response.email;
        });
    },'email');
}
