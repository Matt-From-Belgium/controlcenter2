// JavaScript Document
//Uitlijning van postcodeselector
function haalPostcodes(postcode)
{
	if(postcode.length>1)
	{
		var request = getHTTPObject();
		if(request)
		{
			request.onreadystatechange = function() {displaypostcodes(request);}
		}
		request.open("POST","/modules/ticketserver/postcodes/postcodes.php",true);
		var querystring = "postcode="+postcode;

		request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
		request.send(querystring);
	}
	else
	{
		document.getElementById('postcodeselector').style.display='none';
	}
}

function veldLeegmaken(ctr)
{
	ctr.value='';
}

function changePostcode(id,gemeentenaam,postcode)
{
	var gemeenteveld = document.getElementById('gemeente').value= id;
	var gemeentenaamveld = document.getElementById('gemeentenaam').innerHTML=gemeentenaam;
	var gemeentenaamveld = document.getElementById('gemeentenaamveld').value= gemeentenaam;
	
	//nu mag de box terug verborgen worden
	document.getElementById('postcode').value=postcode;
	document.getElementById('postcodeselector').style.display='none';
}


function displaypostcodes(request)
{
	if(request.readyState == 4)
	{
		if(request.status == 200 || request.status == 304)
		{
			var responseXML = request.responseXML;
			var resultarray = responseXML.getElementsByTagName('result');
	
			//Nu moeten we de HTML aanpassen
			var postcodeselector = document.getElementById('postcodeselector');
			postcodeselector.innerHTML='';
	
			if(resultarray.length>0)
			{
					var header = document.createElement("div");
					header.className="postcodeheader";
					header.innerHTML="Selecteer uw gemeente";
					
					postcodeselector.appendChild(header);

				for(i=0;i<resultarray.length;i++)
				{

					var postcodeElement = document.createElement("div");
					postcodeElement.className='postcodeitem';
				
					//var id = resultarray[i].getElementsByTagName('id')[0].childNodes[0].nodeValue;
					//var postcode = resultarray[i].getElementsByTagName('postcode')[0].childNodes[0].nodeValue;
					//var gemeentenaam = resultarray[i].getElementsByTagName('gemeente')[0].childNodes[0].nodeValue;
				
					postcodeElement.id = resultarray[i].getElementsByTagName('id')[0].childNodes[0].nodeValue;
					postcodeElement.postcode = resultarray[i].getElementsByTagName('postcode')[0].childNodes[0].nodeValue;
					postcodeElement.gemeentenaam = resultarray[i].getElementsByTagName('gemeente')[0].childNodes[0].nodeValue;

					postcodeElement.innerHTML= postcodeElement.postcode + " - " + postcodeElement.gemeentenaam;
					postcodeElement.onclick= function() {changePostcode(this.id,this.gemeentenaam,this.postcode);}
					//postcodeElement.innerHTML="<a href=\"javascript:changePostcode('"+ id + "','" + gemeentenaam + "')\">" + postcode + " - " + gemeentenaam + '</a>';
					postcodeselector.appendChild(postcodeElement);
				}
			}
			else
			{
				postcodeselector.innerHTML='ongeldige postcode';
			}
			var postcodeveld = document.getElementById('postcode');
			document.getElementById('postcodeselector').style.left = getElementPosition('postcode').left + 'px';
			document.getElementById('postcodeselector').style.top = getElementPosition('postcode').top +  20 + 'px';
			postcodeselector.style.display='block';
		}
	}
}

function checkGemeente()
{

}

function getElementPosition(elemID){
var offsetTrail = document.getElementById(elemID);
var offsetLeft = 0;
var offsetTop = 0;
while (offsetTrail){
offsetLeft += offsetTrail.offsetLeft;
offsetTop += offsetTrail.offsetTop;
offsetTrail = offsetTrail.offsetParent;
}
if (navigator.userAgent.indexOf('Mac') != -1 && typeof document.body.leftMargin != 'undefined'){
offsetLeft += document.body.leftMargin;
offsetTop += document.body.topMargin;
}
return {left:offsetLeft,top:offsetTop};
}

function createDiv()
{
//postcodeselector maken
var body = document.getElementsByTagName('body')[0];
var postcodeselector = document.createElement('div');
postcodeselector.id="postcodeselector";
body.appendChild(postcodeselector);
}
