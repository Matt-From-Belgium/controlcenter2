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
        showFriends(e);
    }
}



function updateTicketTimer()
{
    var endDate = new Date(2014, 5, 1, 10);
    //var endDate = new Date(2014,2,14,7,46);
    
    
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

function getPosition(element) {
    var xPosition = 0;
    var yPosition = 0;
  
    while(element) {
        xPosition += (element.offsetLeft - element.scrollLeft + element.clientLeft);
        yPosition += (element.offsetTop - element.scrollTop + element.clientTop);
        element = element.offsetParent;
    }
    return { x: xPosition, y: yPosition };
}

function showFriends(e)
{    
    //Wie is de gebruiker?
    if(e)
    {
        //Functie wordt geladen bij startup met event als argument
        var userID = e.detail.userID;
    }
    else
    {
        var userID = facebookStatus.userID;
    }
    
    FB.api('269412919890552/attending?fields=picture.type(square).height(100).width(100),first_name,id&limit=100','GET',function(response){
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

                    var friendstext = document.getElementById('friendstext');
                    friendstext.innerHTML='Waarom geen vrienden meenemen? Deze Facebookvrienden hebben aangegeven dat ze aanwezig zullen zijn! Spreek samen af en maak van ons concert een leuke groepsactiviteit!';

                    var userIsAttending = false;

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
                        
                        //We kijken ook of we de gebruiker zelf vinden
                        if(response.data[i].id===userID)
                        {
                            //ok, deze persoon heeft zich opgegeven
                            rsvpOk();
                        }
                    //We zoeken nu ook nog even de gegevens van de actieve gebruiker op
                    FB.api('/me?fields=picture.width(100).height(100).type(square),first_name', 'GET', function(userdata){
                        
                        var userPic = document.getElementById('userpic');
                        userPic.src = userdata.picture.data.url;
                        
                        var userName = document.getElementById('username');
                        username.innerHTML = userdata.first_name;
                        
                        //We zijn klaar
                        //We verbergen de knop
                        var fbbutton = document.getElementById('fbloginbutton');
                        fbbutton.style.display='none';

                        //We maken de container zichtbaar
                        var fbdiv = document.getElementById('fbfunctioncontainer');
                        fbdiv.style.display = 'block';
                        
                    });
                   
                        
                    }
                    
                        //We hebben nu een container met floated elements, dit gaat voor layout problemen zorgen
                        var clearFix = document.createElement('div');
                        clearFix.style.clear = 'both';
                        
                        friendsdiv.appendChild(clearFix);
                }
            });
            
            
        }
    });
}

function rsvpOk()
{
    document.getElementById('whenGoing').style.display='block';
    document.getElementById('whenNotGoing').style.display='none';
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
            rsvpOk();
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

function inviteFriend()
{
  FB.ui({
  method: 'send',
  link: 'http://www.projectkoorchantage.be',
});
}

function getPosition(element) {
    var body = document.body,
        win = document.defaultView,
        docElem = document.documentElement,
        box = document.createElement('div');
    box.style.paddingLeft = box.style.width = "1px";
    body.appendChild(box);
    var isBoxModel = box.offsetWidth == 2;
    body.removeChild(box);
    box = element.getBoundingClientRect();
    var clientTop  = docElem.clientTop  || body.clientTop  || 0,
        clientLeft = docElem.clientLeft || body.clientLeft || 0,
        scrollTop  = win.pageYOffset || isBoxModel && docElem.scrollTop  || body.scrollTop,
        scrollLeft = win.pageXOffset || isBoxModel && docElem.scrollLeft || body.scrollLeft;
    return {
        y : box.top  + scrollTop  - clientTop,
        x: box.left + scrollLeft - clientLeft};
}

var beatlesSongID = null;

function searchSongs(element)
{
    //Van zodra er getypt wordt moet de stemknop gedisabled worden tot een keuze is gemaakt
    //De genomen keuze moet ook weg zijn
    document.getElementById('beatlesVote').disabled=true;
    beatlesSongID=null;
    
    var beatlesSongs = document.getElementById('beatlesSongs');
    
    if(beatlesSongs===null)
    {
        //We moeten het element nog aanmaken
        var beatlesSongs = document.createElement('div');
        beatlesSongs.id='beatlesSongs';

        var elementPosition = getPosition(element);

        var body = document.getElementsByTagName('body')[0];

        var top = elementPosition.y + element.offsetHeight+10;

        beatlesSongs.style.top = top+'px';
        beatlesSongs.style.left = elementPosition.x+10+'px';
        beatlesSongs.style.zIndex = '1000';

        body.insertBefore(beatlesSongs,body.firstChild);
    }
    
    var searchString = element.value;
    
    if(searchString.length>0)
    {
        
        

        
        
        beatlesSongs.style.position='absolute';
        
        
        beatlesSongs.innerHTML='';


            //Als er meer dan 3 karakters werden ingevoerd zoeken we
            var ajax = new ajaxTransaction;
            ajax.destination = '/scripts/beatlesajax.php';
            ajax.phpfunction = 'ajaxSearchBeatlesSong';

            ajax.addData('searchtext',searchString);

            ajax.onComplete = function(){
                var beatlesSongs = document.getElementById('beatlesSongs');
                
                if(ajax.result)
                {
                    //ER zijn resultaten
                    //Eerst het resultaatvenster weer leeg maken
                    beatlesSongs.innerHTML='';
                
                    for(i=0;i<ajax.result.length;i++)
                    {
                        var foundSong = document.createElement('div');
                        foundSong.classList.add('beatlesSong');
                        foundSong.id=ajax.result[i].id;
                        foundSong.title=ajax.result[i].title;
                        
                        
                        

                        var songTitle = document.createElement('div');
                        var audioElement = document.createElement('audio');
                        var sourceElement = document.createElement('source');

                        sourceElement.src = ajax.result[i].previewurl;

                        songTitle.innerHTML = ajax.result[i].title;

                        foundSong.appendChild(songTitle);
                        foundSong.onmouseover = function(){
                            var audiotag = this.getElementsByTagName('audio')[0];
                            audiotag.play();
                        };

                        foundSong.onmouseout = function(){
                            //We willen de audio stoppen maar als een keuze werd gemaakt mag het nummer verder
                            //spelen
                            if(beatlesSongID===null)
                            {
                                var audiotag = this.getElementsByTagName('audio')[0];
                                audiotag.pause();
                                audiotag.load();
                            }
                        };
                        
                        foundSong.onmousedown = function(){
                            beatlesSongID=this.id;
                            document.getElementById('beatlesVote').disabled=false;
                            element.value = this.title;
                            beatlesSongs.style.display='none';
                        };

                        audioElement.appendChild(sourceElement);
                        

                        foundSong.appendChild(audioElement);

                        beatlesSongs.appendChild(foundSong);
                    }
                    
                    beatlesSongs.style.display='block';
                }
                else
                {
                    beatlesSongs.style.display='none';
                }
            };

            ajax.ExecuteRequest();
        }
        else
        {
            //Er is geen zoektekst => resultaatgebied verbergen
            beatlesSongs.style.display='none';
            
        }
}

function hideSearchBox()
{
    //Dit event wordt getriggerd na de onmousedown, dus als een keuze werd gemaakt kunnen we dit
    //hier detecteren
    
    if(beatlesSongID===null)
    {
        //Er werd geen keuze gemaakt
        //Het zoekveld moet gewist worden en het resultaatsvenster verborgen
        var beatlesSongs = document.getElementById('beatlesSongs');
        
        if(beatlesSongs)
        {
            beatlesSongs.style.display='none';
            document.getElementById('searchtext').value='';
        }
    }
}