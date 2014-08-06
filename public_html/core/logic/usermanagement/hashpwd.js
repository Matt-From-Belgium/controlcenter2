/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function activateHash()
{
    //We zoeken het gebruikerformulier
    var form = document.getElementById('userform');

    form.onsubmit = function(){
        //het formulier wordt doorgestuurd, we maken vooraf de hash aan
        var password1 = document.getElementById('password').value;
        var password2 = document.getElementById('password2').value;
        
        document.getElementById('password').value = hex_sha512(password1);
        document.getElementById('password2').value = hex_sha512(password2);
        
        password1 = null;
        password2 = null;
        
        alert('ok');
    };
}