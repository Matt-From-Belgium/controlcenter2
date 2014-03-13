/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
document.addEventListener('fbSDKLoaded',showFriendsOnLoad,false);

window.setInterval(function(){updateTicketTimer();},1000);

function showFriendsOnLoad(e)
{
    if(e.detail.status==="connected")
    {
        showFriends();
    }
}



function updateTicketTimer()
{
    var endDate = new Date(2014, 5, 1, 9);
    //var endDate = new Date(2014,2,13,21,15);
    
    
    var date = new Date();
    
    var difference_ms = endDate.getTime() - date.getTime();
    
    if(difference_ms<=0)
    {
        window.location.reload();
    }
    else
    {
        difference_ms = difference_ms/1000;
        var seconds = Math.floor(difference_ms % 60);
        difference_ms = difference_ms/60; 
        var minutes = Math.floor(difference_ms % 60);
        difference_ms = difference_ms/60; 
        var hours = Math.floor(difference_ms % 24);  
        var days = Math.floor(difference_ms/24);

       var datestring = days + 'd ' + hours + 'u ' + minutes + 'm ' + seconds + 's';

       var timerfield = document.getElementById('tickettimer');
       timerfield.innerHTML = datestring;
    }

}

function showFriends()
{    
    FB.api('269412919890552/attending?fields=picture.width(100).height(100).type(square),first_name,id','GET',function(response){
        //We krijgen een array terug met de gegevens die we nodig hebben
        if(!response || response.error){
            alert('Er heeft zich een fout voorgedaan. Probeer later opnieuw...');
        }
        else
        {
            //vriendjes ophalen
            FB.api('me/friends?fields=id','GET',function(friendresponse){
                if(!response || response.error){
                    alert('Er heeft zich een fout voorgedaan. Probeer het later opnieuw...');
                }
                else
                {
                    var friendslist = new Array();
                    
                    for(x=0;x<friendresponse.data.length;x++)
                    {
                        friendslist.push(friendresponse.data[x].id);
                    }
                    
                    var friendsdiv = document.getElementById('friendsthatgo');
            
                    friendsdiv.innerHTML='';

                    var friendstext = document.createElement('div');
                    friendstext.classList.add("friendstext");
                    friendstext.innerHTML='Waarom geen vrienden meenemen? Deze Facebookvrienden hebben aangegeven dat ze aanwezig zullen zijn! Spreek samen af en maak van ons concert een leuke groepsactiviteit!';

                    friendsdiv.appendChild(friendstext);

                    for(i=0;i<response.data.length;i++)
                    {
                        //Eerst kijken we of deze persoon voorkomt op de vriendelijst
                        if(friendslist.indexOf(response.data[i].id)>=0)
                        {
                            //Het gaat om een vriend => toevoegen
                            var friend = document.createElement('div');
                            friend.classList.add('friend');

                            var friendimg = document.createElement('img');
                            friendimg.classList.add('friendimg');
                            friendimg.src = response.data[i].picture.data.url;

                            var friendname = document.createElement('div');
                            friendname.classList.add('friendname');
                            friendname.innerHTML=response.data[i].first_name;


                            friend.appendChild(friendimg);
                            friend.appendChild(friendname);
                            friendsdiv.appendChild(friend);
                        }
                        
                        
                    }
                }
            });
            
            
        }
    });
}

function rsvp()
{
    var rsvpElements = document.getElementsByName('rsvp');
    var rsvpValue;
    
    for(i=0;i<rsvpElements.length;i++)
    {
        if(rsvpElements[i].checked)
        {
            rsvpValue = rsvpElements[i].value;
            break;
        }
    }
    
    if(rsvpValue==='ik ga')
    {
        FB.api('269412919890552/attending','POST',function(){
            alert('genoteerd!');
        });
    }
    
    if(rsvpValue === 'misschien')
    {
        FB.api('269412919890552/maybe','POST',function(){
            alert('genoteerd! We hopen dat je erbij kan zijn.');
        });
    }
}

function jump()
{
    this.style.visibility = 'hidden';
}