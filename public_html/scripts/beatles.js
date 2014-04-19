/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
document.addEventListener('fbSDKLoaded',showFriendsOnLoad,false);
document.addEventListener('fbSDKLoaded',enableShareBoxOnLoad,false);


window.setInterval(function(){updateTicketTimer();},1000);

function showFriendsOnLoad(e)
{
    if(e.detail.status==="connected")
    {
        showFriends(e);
    }
}

function enableShareBoxOnLoad(e)
{
    if(e.detail.status==='connected'){
        //Er is verbinding maar kunnen we acties publiceren?
        if(e.detail.grantedPermissions.indexOf('publish_actions')>=0)
        {
            //Toegang is ok => checkbox standaard aanvinken
            document.getElementById('shareOnFB').checked=true;
        }
        else
        {
            document.getElementById('shareOnFB').checked=false;
        }
    }
}

function enableShareBox(e)
{
    //enkel als de checkbox aangevinkt wordt mag er ingegrepen worden op het browsergedrag
    if(document.getElementById('shareOnFB').checked===true)
    {
        
        
    registerWithFacebook(function(){
        //Gebruiker geeft toegang
        document.getElementById('shareOnFB').checked=true;
        document.location.href='#beatlesvote';
    },'publish_actions',function(){
        //Gebruiker geeft geen toegang
        document.getElementById('shareOnFB').checked=false;
    });
    
    e.preventDefault;
    return false;
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
  link: 'http://www.projectkoorchantage.be/beatles.php',
});
}

function inviteFriendMobile()
{
    alert('ok');
    var url = 'https://www.facebook.com/dialog/feed?app_id='+'518339518198298'+'&display=popup&link=http://www.projectkoorchantage.be/beatles.php&redirect_uri=http://www.projectkoorchantage.be/beatles.php';
    document.location.href=url;
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
var beatlesItunesID=null;
var beatlesSelectedSong=null;

function searchSongsWait(element)
{
    //Om te voorkomen dat er in sneltempo zoekopdrachten zijn bouwen we een delay in
    var value = element.value.length;
    
    setTimeout(function(){
        var value2 = document.getElementById('searchtext').value.length;
        
        
        if(value===value2)
        {
            
            searchSongs(element);
        }
        else
        {
            
        }
        
    },500);
}

function searchSongs(element)
{
    
    //Van zodra er getypt wordt moet de stemknop gedisabled worden tot een keuze is gemaakt
    //De genomen keuze moet ook weg zijn
    var voteButton = document.getElementById('beatlesVote');
    voteButton.disabled=true;
    voteButton.classList.add('voteDisabled');
    voteButton.classList.remove('voteEnabled');
    
    beatlesSongID=null;
    beatlesItunesID=null;
    beatlesSelectedSong=null;
    
    
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
        beatlesSongs.style.left = elementPosition.x+'px';
        beatlesSongs.style.zIndex = '1000';

        body.insertBefore(beatlesSongs,body.firstChild);
    }
    
    var searchString = element.value;
    
    if(searchString.length>0)
    {
     
        
        beatlesSongs.style.position='absolute';
        
        
        beatlesSongs.innerHTML='';


            
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
                        foundSong.itunesID = ajax.result[i].itunesID;
                        foundSong.title=ajax.result[i].title;
                        foundSong.audio=document.createElement('audio');
                        
                        foundSong.audio.setAttribute('preload','auto');

                        var songTitle = document.createElement('div');
                        //var audioElement = document.createElement('audio');
                        var sourceElement = document.createElement('source');

                        sourceElement.src = ajax.result[i].previewurl;
                        foundSong.audio.appendChild(sourceElement);
                        

                        songTitle.innerHTML = ajax.result[i].title;

                        foundSong.appendChild(songTitle);
                        foundSong.onmouseover = function(){
                            /*var audiotag = this.getElementsByTagName('audio')[0];
                            audiotag.play();*/
                            
                            this.audio.play();
                        };

                        foundSong.onmouseout = function(){
                            //We willen de audio stoppen maar als een keuze werd gemaakt mag het nummer verder
                            //spelen
                            if(beatlesSongID===null)
                            {
                                /*var audiotag = this.getElementsByTagName('audio')[0];
                                audiotag.pause();
                                audiotag.load();*/
                                
                                this.audio.pause();
                                this.audio.load();
                            }
                        };
                        
                        foundSong.onmousedown = function(){
                            //De id van het databaseitem wordt bijgehouden
                            //Als het over een nieuw nummer gaat is deze id -1
                            //Op die manier weten we dat we de iTunesID moeten gebruiken
                            beatlesSongID=this.id;
                            beatlesItunesID = this.itunesID;
                            beatlesSelectedSong= this;
                            
                            var voteButton = document.getElementById('beatlesVote');
                            voteButton.disabled=false;
                            voteButton.classList.add('voteEnabled');
                            voteButton.classList.remove('voteDisabled');
                            element.value = this.title;
                            beatlesSongs.style.display='none';
                        };

                        
                        

                        foundSong.appendChild(foundSong.audio);

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

function beatlesVote()
{
    if(beatlesSongID)
    {
        //Er is een keuze gemaakt uit de databank
        var ajax = new ajaxTransaction();
        
        ajax.destination = '/scripts/beatlesajax.php';
        ajax.phpfunction = 'ajaxVoteForSong';
         
        var loader = document.getElementById('loader');
        loader.style.visibility = 'visible';
        
        if(beatlesSongID> -1)
        {
            //De gebruiker koos een nummer uit de databank
            ajax.addData('songid',beatlesSongID);
        }
        else
        {
            //De gebruiker koos een niet gekend nummer uit de iTunes DB
            ajax.addData('itunesid',beatlesItunesID);
        }
        ajax.onComplete=function(){
           if(ajax.successIndicator)
           {
               //We krijgen het id van het gekozen nummer terug
               //Wanneer we uit iTunes catalogus gekozen hebben krijgen we het nieuw gecreëerde id
               var votedId = ajax.result[0].id;
               
               //We delen de song
               if(facebookStatus.authStatus==='connected')
               {
                   if(document.getElementById('shareOnFB').checked===true)
                   {
                       var apiPath = '/me/' + facebookStatus.appNamespace+':vote_for';
                       var songPath = 'http://www.projectkoorchantage.be/beatles.php?id='+votedId;
                       
                       FB.api(apiPath,'post',
                       {
                           song: songPath,
                           'fb:explicitly_shared': 'true'
                       },
                       function(response){
                                //We verbergen het stemformulier en tonen de bedanking
                                document.getElementById('vote').style.display='none';
                                document.getElementById('afterVote').style.display='block';
                                loader.style.visibility = 'hidden';
                                refreshTop5();
                           ;}
                        );
                   }
                   else
                   {
                       //We verbergen het stemformulier en tonen de bedanking
                                document.getElementById('vote').style.display='none';
                                document.getElementById('afterVote').style.display='block';
                                loader.style.visibility = 'hidden';
                                refreshTop5();
                   }
               }
           }
        };
        
        ajax.ExecuteRequest();
    }
}

function refreshTop5()
{
    //We tonen de loader
           var top5 = document.getElementById('top5') ;
           top5.innerHTML='';
           var loadIndicator = document.createElement('div');
           var loadImage = document.createElement('img');
           loadImage.src = '/images/groteloader.gif';
           loadImage.style.marginLeft='auto';
           loadImage.style.marginRight='auto';
           loadIndicator.appendChild(loadImage);
           top5.appendChild(loadIndicator);
    
    var ajax = new ajaxTransaction;
    ajax.destination = '/scripts/beatlesajax.php';
    ajax.phpfunction = 'ajaxTop5';
    
    ajax.onComplete=function(){
        if(ajax.successIndicator)
        {
           var top5 = document.getElementById('top5') ;
           
           top5.innerHTML='';
           
           for(i=0;i<ajax.result.length;i++)
           {
               var newElement = document.createElement('div');
               newElement.classList.add('beatlesChart');
               
               var number = i+1;
               
               newElement.innerHTML='<h1>'+number+'</h1>&nbsp;'+ajax.result[i].title;
               newElement.audio = document.createElement('audio');
               newElement.audio.setAttribute('preload','auto');
               var sourceElement = document.createElement('source');
               sourceElement.src= ajax.result[i].previewurl;
               
               newElement.audio.appendChild(sourceElement);
               newElement.appendChild(newElement.audio);
               
               newElement.id=ajax.result[i].id;
               newElement.itunesID = ajax.result[i].itunesID;
               newElement.title=ajax.result[i].title;
               
               newElement.onmouseover=function(){
                 this.audio.play();  
               };
               
               newElement.onmouseout = function(){
                   this.audio.pause();
                   this.audio.load();
               };
               
               newElement.onmousedown = function() {
                            //De id van het databaseitem wordt bijgehouden
                            //Als het over een nieuw nummer gaat is deze id -1
                            //Op die manier weten we dat we de iTunesID moeten gebruiken
                            beatlesSongID=this.id;
                            beatlesItunesID = this.itunesID;
                            beatlesSelectedSong= this;
                            
                            var element = document.getElementById('searchtext');
                            
                            var voteButton = document.getElementById('beatlesVote');
                            voteButton.disabled=false;
                            voteButton.classList.add('voteEnabled');
                            voteButton.classList.remove('voteDisabled');
                            element.value = this.title;
                            
               };
               
               top5.appendChild(newElement);
               
               
               
           }
           
        }
        
        
    };
    
    
    
    ajax.ExecuteRequest();
}