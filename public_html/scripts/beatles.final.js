document.addEventListener("fbSDKLoaded",showFriendsOnLoad,false);document.addEventListener("fbSDKLoaded",enableShareBoxOnLoad,false);window.setInterval(function(){updateTicketTimer()},1000);function showFriendsOnLoad(a){if(a.detail.status==="connected"){showFriends(a)}}function enableShareBoxOnLoad(a){if(a.detail.status==="connected"){if(a.detail.grantedPermissions.indexOf("publish_actions")>=0){document.getElementById("shareOnFB").checked=true}else{document.getElementById("shareOnFB").checked=false}}}function enableShareBox(a){if(document.getElementById("shareOnFB").checked===true){registerWithFacebook(function(){document.getElementById("shareOnFB").checked=true;document.location.href="#beatlesvote"},"publish_actions",function(){document.getElementById("shareOnFB").checked=false});a.preventDefault;return false}}function updateTicketTimer(){var c=new Date(2014,5,1,10);var a=new Date();var h=c.getTime()-a.getTime();if(h<=0){window.location.reload()}else{h=h/1000;var f=Math.floor(h%60);h=h/60;var b=Math.floor(h%60);h=h/60;var e=Math.floor(h%24);var g=Math.floor(h/24);var d=g+"d "+e+"u "+b+"m "+f+"s";var j=document.getElementById("tickettimer");j.innerHTML=d}}function getPosition(a){var c=0;var b=0;while(a){c+=(a.offsetLeft-a.scrollLeft+a.clientLeft);b+=(a.offsetTop-a.scrollTop+a.clientTop);a=a.offsetParent}return{x:c,y:b}}function showFriends(b){if(b){var a=b.detail.userID}else{var a=facebookStatus.userID}FB.api("269412919890552/attending?fields=picture.type(square).height(100).width(100),first_name,id&limit=100","GET",function(c){if(!c||c.error){alert("Er heeft zich een fout voorgedaan. Probeer later opnieuw...")}else{FB.api("me/friends?fields=id","GET",function(g){if(!c||c.error){alert("Er heeft zich een fout voorgedaan. Probeer het later opnieuw...")}else{var h=new Array();for(x=0;x<g.data.length;x++){h.push(g.data[x].id)}var l=document.getElementById("friendsthatgo");l.innerHTML="";var m=document.getElementById("friendstext");m.innerHTML="Waarom geen vrienden meenemen? Deze Facebookvrienden hebben aangegeven dat ze aanwezig zullen zijn! Spreek samen af en maak van ons concert een leuke groepsactiviteit!";var e=false;for(i=0;i<c.data.length;i++){if(h.indexOf(c.data[i].id)>=0){var f=document.createElement("div");f.classList.add("friend");var k=document.createElement("img");k.classList.add("friendimg");k.src=c.data[i].picture.data.url;var j=document.createElement("div");j.classList.add("friendname");j.innerHTML=c.data[i].first_name;f.appendChild(k);f.appendChild(j);l.appendChild(f)}if(c.data[i].id===a){rsvpOk()}FB.api("/me?fields=picture.width(100).height(100).type(square),first_name","GET",function(o){var q=document.getElementById("userpic");q.src=o.picture.data.url;var p=document.getElementById("username");username.innerHTML=o.first_name;var n=document.getElementById("fbloginbutton");n.style.display="none";var r=document.getElementById("fbfunctioncontainer");r.style.display="block"})}var d=document.createElement("div");d.style.clear="both";l.appendChild(d)}})}})}function rsvpOk(){document.getElementById("whenGoing").style.display="block";document.getElementById("whenNotGoing").style.display="none"}function rsvp(){var a=document.getElementsByName("rsvp");var b;for(i=0;i<a.length;i++){if(a[i].checked){b=a[i].value;break}}if(b==="ik ga"){FB.api("269412919890552/attending","POST",function(){rsvpOk()})}if(b==="misschien"){FB.api("269412919890552/maybe","POST",function(){alert("genoteerd! We hopen dat je erbij kan zijn.")})}}function jump(){this.style.visibility="hidden"}function inviteFriend(){FB.ui({method:"send",link:"http://www.projectkoorchantage.be/beatles.php",})}function inviteFriendMobile(){alert("ok");var a="https://www.facebook.com/dialog/feed?app_id=518339518198298&display=popup&link=http://alpha.projectkoorchantage.be/beatles.php&redirect_uri=http://alpha.projectkoorchantage.be/beatles.php";document.location.href=a}function getPosition(f){var h=document.body,j=document.defaultView,b=document.documentElement,g=document.createElement("div");g.style.paddingLeft=g.style.width="1px";h.appendChild(g);var d=g.offsetWidth==2;h.removeChild(g);g=f.getBoundingClientRect();var e=b.clientTop||h.clientTop||0,k=b.clientLeft||h.clientLeft||0,a=j.pageYOffset||d&&b.scrollTop||h.scrollTop,c=j.pageXOffset||d&&b.scrollLeft||h.scrollLeft;return{y:g.top+a-e,x:g.left+c-k}}var beatlesSongID=null;var beatlesItunesID=null;var beatlesSelectedSong=null;function searchSongsWait(a){var b=a.value.length;setTimeout(function(){var c=document.getElementById("searchtext").value.length;if(b===c){searchSongs(a)}else{}},500)}function searchSongs(e){var b=document.getElementById("beatlesVote");b.disabled=true;b.classList.add("voteDisabled");b.classList.remove("voteEnabled");beatlesSongID=null;beatlesItunesID=null;beatlesSelectedSong=null;var d=document.getElementById("beatlesSongs");if(d===null){var d=document.createElement("div");d.id="beatlesSongs";var f=getPosition(e);var a=document.getElementsByTagName("body")[0];var h=f.y+e.offsetHeight+10;d.style.top=h+"px";d.style.left=f.x+"px";d.style.zIndex="1000";a.insertBefore(d,a.firstChild)}var c=e.value;if(c.length>0){d.style.position="absolute";d.innerHTML="";var g=new ajaxTransaction;g.destination="/scripts/beatlesajax.php";g.phpfunction="ajaxSearchBeatlesSong";g.addData("searchtext",c);g.onComplete=function(){var l=document.getElementById("beatlesSongs");if(g.result){l.innerHTML="";for(i=0;i<g.result.length;i++){var k=document.createElement("div");k.classList.add("beatlesSong");k.id=g.result[i].id;k.itunesID=g.result[i].itunesID;k.title=g.result[i].title;k.audio=document.createElement("audio");k.audio.setAttribute("preload","auto");var m=document.createElement("div");var j=document.createElement("source");j.src=g.result[i].previewurl;k.audio.appendChild(j);m.innerHTML=g.result[i].title;k.appendChild(m);k.onmouseover=function(){this.audio.play()};k.onmouseout=function(){if(beatlesSongID===null){this.audio.pause();this.audio.load()}};k.onmousedown=function(){beatlesSongID=this.id;beatlesItunesID=this.itunesID;beatlesSelectedSong=this;var n=document.getElementById("beatlesVote");n.disabled=false;n.classList.add("voteEnabled");n.classList.remove("voteDisabled");e.value=this.title;l.style.display="none"};k.appendChild(k.audio);l.appendChild(k)}l.style.display="block"}else{l.style.display="none"}};g.ExecuteRequest()}else{d.style.display="none"}}function hideSearchBox(){if(beatlesSongID===null){var a=document.getElementById("beatlesSongs");if(a){a.style.display="none";document.getElementById("searchtext").value=""}}}function beatlesVote(){if(beatlesSongID){var b=new ajaxTransaction();b.destination="/scripts/beatlesajax.php";b.phpfunction="ajaxVoteForSong";var a=document.getElementById("loader");a.style.visibility="visible";if(beatlesSongID>-1){b.addData("songid",beatlesSongID)}else{b.addData("itunesid",beatlesItunesID)}b.onComplete=function(){if(b.successIndicator){var e=b.result[0].id;if(facebookStatus.authStatus==="connected"){if(document.getElementById("shareOnFB").checked===true){var d="/me/"+facebookStatus.appNamespace+":vote_for";var c="http://www.projectkoorchantage.be/beatles.php?id="+e;FB.api(d,"post",{song:c,"fb:explicitly_shared":"true"},function(f){document.getElementById("vote").style.display="none";document.getElementById("afterVote").style.display="block";a.style.visibility="hidden";refreshTop5()})}else{document.getElementById("vote").style.display="none";document.getElementById("afterVote").style.display="block";a.style.visibility="hidden";refreshTop5()}}}};b.ExecuteRequest()}}function refreshTop5(){var a=document.getElementById("top5");a.innerHTML="";var b=document.createElement("div");var d=document.createElement("img");d.src="/images/groteloader.gif";d.style.marginLeft="auto";d.style.marginRight="auto";b.appendChild(d);a.appendChild(b);var c=new ajaxTransaction;c.destination="/scripts/beatlesajax.php";c.phpfunction="ajaxTop5";c.onComplete=function(){if(c.successIndicator){var f=document.getElementById("top5");f.innerHTML="";for(i=0;i<c.result.length;i++){var h=document.createElement("div");h.classList.add("beatlesChart");var g=i+1;h.innerHTML="<h1>"+g+"</h1>&nbsp;"+c.result[i].title;h.audio=document.createElement("audio");h.audio.setAttribute("preload","auto");var e=document.createElement("source");e.src=c.result[i].previewurl;h.audio.appendChild(e);h.appendChild(h.audio);h.id=c.result[i].id;h.itunesID=c.result[i].itunesID;h.title=c.result[i].title;h.onmouseover=function(){this.audio.play()};h.onmouseout=function(){this.audio.pause();this.audio.load()};h.onmousedown=function(){beatlesSongID=this.id;beatlesItunesID=this.itunesID;beatlesSelectedSong=this;var k=document.getElementById("searchtext");var j=document.getElementById("beatlesVote");j.disabled=false;j.classList.add("voteEnabled");j.classList.remove("voteDisabled");k.value=this.title};f.appendChild(h)}}};c.ExecuteRequest()};