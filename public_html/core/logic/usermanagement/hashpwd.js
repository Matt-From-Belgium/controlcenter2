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
        //We kijken eerst of het wachtwoord niet al gehashed is door een vorige ingave
        if(document.getElementById('phash').value>0)
        {    
        //het formulier wordt doorgestuurd, we maken vooraf de hash aan
        var password1 = document.getElementById('password1').value;
        var password2 = document.getElementById('password2').value;
        
        document.getElementById('password1').value = hex_sha512(password1);
        document.getElementById('password2').value = hex_sha512(password2);
        
        password1 = null;
        password2 = null;
        
        
        }
    };
    
    //We moeten nu wel zorgen dat wanneer de hash al berekend is dat de gebruiker deze niet kan wijzigen
    //Wanneer er op het wachtwoordveld geklikt wordt moet alles blanco worden
    document.getElementById('password1').onfocus = function(){clearPasswordFields();};
    document.getElementById('password2').onfocus = function(){clearPasswordFields();};

}

function clearPasswordFields()
{
    if(document.getElementById('phash').value<=0)
    {
        document.getElementById('password1').value=null;
        document.getElementById('password2').value=null;
        
        //Aangezien het om een nieuw wachtwoord gaat moet er gehashed worden
        document.getElementById('phash').value=1;
    }
    

}