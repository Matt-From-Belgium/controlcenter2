/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function encryptPassword(password)
{
    var encrypted = hex_sha512(password);
    return encrypted;
}

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
        
        document.getElementById('password1').value = encryptPassword(password1);
        document.getElementById('password2').value = encryptPassword(password2);
        
        password1 = null;
        password2 = null;
        
        
        }
    };
    
    //We moeten nu wel zorgen dat wanneer de hash al berekend is dat de gebruiker deze niet kan wijzigen
    //Wanneer er op het wachtwoordveld geklikt wordt moet alles blanco worden
    document.getElementById('password1').onfocus = function(){clearPasswordFields();};
    document.getElementById('password2').onfocus = function(){clearPasswordFields();};

}

function hashLogin()
{
    //We halen het wachtwoord op
    var password = document.getElementById('p').value;
    
    document.getElementById('p').value = encryptPassword(password);
    
}

function hashPasswordChange()
{
            document.getElementById('passwordchange').onsubmit = function(){
            var oldpass = document.getElementById('oldpassword').value;
            var newpass1 = document.getElementById('newpassword1').value;
            var newpass2 = document.getElementById('newpassword2').value;
    
            document.getElementById('oldpassword').value = encryptPassword(oldpass);
            document.getElementById('newpassword1').value = encryptPassword(newpass1);
            document.getElementById('newpassword2').value = encryptPassword(newpass2);
    };

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