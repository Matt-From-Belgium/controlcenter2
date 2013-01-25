// JavaScript Document
function getHTTPObject()
{
	//Deze functie maakt een object aan voor AJAX transacties
	var xhr = false;
	
	if(window.XMLHttpRequest)
	{
		xhr = new XMLHttpRequest();
	}
	else if(window.ActiveXObject)
	{
		try
		{
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch(e)
		{
			try
			{
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch(e)
			{
				xhr=false;
			}
		}
	}
	
	return xhr;
}

function ValidateField(control,validator,identifier)
{
	//We krijgen de control als argument => Eerst gaan we op zoek naar de bovenliggende FORM-tag
	parentelement = control
	do
	{
		//We halen de parentnode op van parentelent, bij eerste iteratie zal dit de 
		//aangeleverde control zijn
		parentelement = parentelement.parentNode
	} while(parentelement.nodeName !== "FORM");
	
	//Na het uitvoeren van de loop is parentelement gelijk aan de FORM-tag
	var formid = parentelement.id;
	var controlid = control.id;
	var controlvalue = control.value;
	
	var request = getHTTPObject();
	
	//De antwoorden worden verwerkt door getResponse, formid wordt meegegeven om zeker te zjin
	//dat de error in het juiste vak wordt weergegeven als er meerdere errors zouden zijn.
	request.onreadystatechange = function() { getResponse(request,formid) };
	var poststring = 'fieldname='+controlid+'&value='+controlvalue+'&id='+identifier;
	request.open("POST",validator,true);
	request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	request.send(poststring);
}

function getResponse(request,formid)
{
	if(request.readyState == 4)
	{
		if(request.status == 200 || request.status == 304)
		{
			//Het XML antwoord is ontvangen, nu moet het antwoord ontleed worden.
			var xmlresponse = request.responseXML;
			
			var fieldname = xmlresponse.getElementsByTagName("fieldname")[0].firstChild.nodeValue;
			
			//Om de errormessages te kunnen groeperen wordt er bij de <response></response> structuur
			//een attribuut validator meegegeven.
			var validator = xmlresponse.getElementsByTagName("response")[0].getAttribute("validator");
			
			if(xmlresponse.getElementsByTagName("message")[0].firstChild)
			{
				//Er is een foutboodschap afgeleverd => deze moet weergegeven worden
				var message = xmlresponse.getElementsByTagName("message")[0].firstChild.nodeValue;
				showError(fieldname,message,validator,formid);
			}
			else
			{
				removeError(fieldname,validator);
			}
		}
	}
}

function showError(fieldname,message,validator,formid)
{
	//Als er nog geen errorbox is dan moet deze aangemaakt worden
	if(!document.getElementById(validator))
	{
		// De errorbox moet nog aangemaakt worden...
		generateErrorBox(validator,formid);
	}
	
		//Als er geen errorbox was dan is deze nu aangemaakt... We kunnen dus verder gaan.
		var errorbox = document.getElementById(validator);
					
		//Er moet nagekeken worden of er voor dit veld al een errormessage is
		nodeexists = false;
					
		for(var i=0;i<errorbox.childNodes.length;i++)
		{
			if(errorbox.childNodes[i].id == fieldname)
			{
				//Er is reeds een foutmelding voor dit veld => melding aanpassen
				errorbox.childNodes[i].firstChild.nodeValue = message;
				nodeexists = true;
			}
		}
				
		//als nodeexists hier true is dan is de errormessage aangepast en moet er geen nieuw
		//LI element worden aangemaakt. Als er echter geen item gevonden is moet dit worden aangemaakt
		if(nodeexists == false)
		{
			var newlistelement = document.createElement("LI");
			newlistelement.id = fieldname;
			var newlistelementtext = document.createTextNode(message);
			newlistelement.appendChild(newlistelementtext);
			errorbox.appendChild(newlistelement);
		}
}

function generateErrorBox(validator,formid)
{
	// Er moet een errorbox worden aangemaakt net voor het formulier
	var form = document.getElementById(formid);
	var formparent = form.parentNode;
	
	var errorbox = document.createElement("UL");
	errorbox.id = validator;
	errorbox.setAttribute("class","errorbox");
	//bugfix vor Internet Explorer
	errorbox.setAttribute("className","errorbox");

	//De errorbox wordt aangemaakt en ingevoegd voor het formelement.
	formparent.insertBefore(errorbox,form);
}

function removeError(fieldname,validator)
{
	//Geen errors => nagaan of er een errormessage moet worden verwijderd
	if(document.getElementById(validator))
	{
		var errorbox = document.getElementById(validator);
				
		//nodes overlopen en als id = fieldname => node verwijderen
		for(var i =0;i<errorbox.childNodes.length;i++)
		{
			if(errorbox.childNodes[i].id==fieldname)
			{
				var childtoremove = errorbox.childNodes[i];
				errorbox.removeChild(childtoremove);
			}
		}
		
		// Op dit punt zijn de errormessages verwijderd... de vraag is nu of de errorbox zelf nog nodig is
		if(errorbox.getElementsByTagName("*").length==0)
		{
			//geen errors meer => verwijder de error box
			var parent = errorbox.parentNode;
			parent.removeChild(errorbox);
		}
	}
}

function getLanguageConstant(type,itemname,constantname)
{
	//Deze functie haalt via AJAX een languageconstant op van de server. Er moeten 3 parameters
	//opgegeven worden:
	//
	// ->type: moet component of module zijn
	// ->itemname: de naam van het component/de module
	// ->constantname: de taalconstante die opgevraagd wordt.
	
	var request = getHTTPObject();
	
	//De antwoorden worden verwerkt door getResponse, formid wordt meegegeven om zeker te zjin
	//dat de error in het juiste vak wordt weergegeven als er meerdere errors zouden zijn.
	
	var poststring = 'type='+type+'&itemname='+itemname+'&constantname='+constantname;
	
	//We kunnen slechts verde als we het antwoord hebben => Synchrone request!!
	request.open("POST","/core/logic/languages/ajaxlanguages.php",false);
	request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	request.send(poststring);
	
	var constantvalue = request.responseText;
	return(constantvalue);
}

