function ajaxTransaction(formElement)
{
 //private variabelen
  var names = new Array();
  var values = new Array();
  var connectionpoint= '/core/logic/ajax/ajax.php';
  var that = this;
  var progressindicator;
  //Revisie 1: waarden worden nu bijgehouden door een FormData object
  var formData = new FormData();
 
 //publieke variabelen
  this.onComplete=null;
  this.successIndicator = null;
  this.errorList=null;
  this.resultLength=null;
  this.result = null;
  this.destination = null;
  this.phpfunction = null;
 
 //constructor functionaliteit
 if(formElement)
    {
    constructor(formElement);
    }
 
 //private methods
 function constructor(formElement)
    {
        //Doel van de constructor is om onmiddellijk een formulier in te laden
        //zodat de inhoud gemakkelijker doorgestuurd kan worden

               //Er werd een waarde test opgegeven (anders zou de functie niet geactiveerd worden, 
               //maar is dit een geldig HTMLDOM element?
               if(document.getElementById(formElement))
                   {
                       //De gegevens uit het formulier worden klaargezet
                       formData = new FormData(document.getElementById(formElement));
                   }
              else
                  {
                      throw formElement+" is not a valid element";
                  }


    }
 
 /*Revisie 1: data wordt beheerd door FormData
 function buildPostString()
 {
 	//we bouwen de poststring. we sturen hier ook de bestemming en de phpfunction variabelen mee
 	//De ajaxgegevens worden zowieso naar /core/logic/ajax/ajax.php maar binnen dat script kunnen andere
 	//functies aangeroepen worden. Beide variabelen moeten dus wel een waarde hebben.
 	if(that.destination!=null && that.phpfunction!=null)
 	{
 		 var poststring = '';
 		 
 		 //De waarden destination en phpfunction worden toegevoegd aan de poststring
 		 
		poststring = 'destination='+that.destination+'&phpfunction=' + that.phpfunction + '&'; 		 
 		 
	 	for(i=0;i< names.length ; i++)
	 	{
			 poststring = poststring+names[i]+'='+values[i]+'&';
	 	}
	 	
	 
		 poststring = poststring.replace(' ','+');
	 
		return poststring;
 	}
 	else
 	{
 		throw "both destination and phpfunction need a value";
 	}
 }*/
    
 function completePoststring()
 {
     //Revisie 1: FormData zal toelaten om bestanden mee te sturen maar zal het geheel gebruiksvriendelijker maken
     //Probleem is wel dat we nog steeds destination en phpfunction moeten meesturen. We zouden die ook met de appendfunctie kunnen toeveogen
     //aan FormData maar fout gebruik van de klasse zou er kunnen toe leiden dat de waarden meerdere keren voorkomen
     //Dus is er nog steeds behoefte aan een functie die deze variabelen éénmaal definieert in this.formData
     if(that.destination!=null && that.phpfunction!=null)
     {
         formData.append('destination',that.destination);
         formData.append('phpfunction',that.phpfunction);
     }
     else
     {
         //Als de waarden niet gedefinieerd zijn
         throw "both destination and phpfunction need a value";
     }
     
     return formData;
     
 }
 
 function getHTTPObject() 
 {
	 var xhr = false;
	 
	 if(window.XMLHttpRequest)
	 {
		 xhr = new XMLHttpRequest();
	 }
	 else if (window.ActiveXObject)
	 {
		 try
		 {
			 xhr=new ActiveXObject("Msxml2.XMLHTTP");
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
 
 //public methods
 this.addData = function(name,value)
 {
	/*names.push(name);
	values.push(value);*/
     
        //Revisie 1: data wordt opgeslagen in formdata object
        formData.append(name,value);
 };
 
 this.getData = function()
 {
	 /*document.write(names);
	 document.write(values);
         */
        
        //Revisie 1: data wordt opgeslagen in formdata object
        return formData;
 };
 
 this.setIndicator = function(elementid)
 {
	 //met setIndicator koppelen we een HTML-element aan de klasse zodat we de indicator kunnen verbergen en weergeven wanneer nodig
	 if(document.getElementById(elementid))
	 {
		 progressindicator = document.getElementById(elementid);
		 progressindicator.style.visibility='hidden';
	 }
	 else
	 {
		 throw "element aangeleverd met setIndicator bestaat niet";
	 }
 };
 
 this.ExecuteRequest = function()
 {
	 var request = getHTTPObject();
	 request.onreadystatechange = function() 
	 { 
	 	if(request.readyState == 4)
		{
			if(request.status == 200)
			{				
				if(typeof that.onComplete != 'function')
				{
					//Er is geen functie gedefinieerd in onComplete => exception
					throw "onComplete() is niet gedefinieerd";
				}
				else
				{
					if(progressindicator)
					{
					progressindicator.style.visibility='hidden';
					}
					
					//De gegevens worden weggeschreven naar klassevariabelen waar ze evt met de onComplete functie kunnen worden opgehaald
					//Aangezien onComplete pas hierna wordt uitgevoerd ben je hier wel zeker dat die variabelen een waarde hebben bij het
					//uitvoeren van onComplete()
					
					//Eerst kijken we naar de inhoud van de resulttag
					var xml = request.responseXML;
                                        
                                        //Eerst controleren we of er wel degelijk een resulttag terugkwam. Wanneer er server side een exception optreedt
                                        //Kan het in bepaalde gevallen gebeuren dat deze in het antwoord gestuurd wordt.
                                        if(xml)
                                            {
                                        
                                                result = xml.getElementsByTagName('result')[0].firstChild.nodeValue;

                                                if(result == 'ok')
                                                {
                                                        //De server aanvaardt de request en stuurt mogelijk data terug
                                                        that.successIndicator = true;

                                                        //Nu moeten we nagaan of er data terugkeert
                                                        var resultrows = xml.getElementsByTagName('datarow');

                                                        if(resultrows.length>0)
                                                        {
                                                                //Er is datareturn
                                                                //Eerst wordt het aantal rijen opgeslagen in public property resultLength
                                                                that.resultLength = resultrows.length;

                                                                //nu moet de data verwerkt worden en de resultaten in publieke properties opgeslagen worden
                                                                //We creëren een custom object om de resultaatset mooi te structureren
                                                                //Eerst moeten we nagaan welke velden er zijn
                                                                var firstrow = resultrows[0];
                                                                var fieldnames = new Array();
                                                                for(i=0;i<firstrow.childNodes.length;i++)
                                                                {
                                                                        var fieldname = firstrow.childNodes[i].nodeName;
                                                                        fieldnames.push(fieldname);
                                                                }

                                                                //Hier zullen we de code genereren voor de definitie van de public properties van onze custom class
                                                                var code='';

                                                                        //We willen public properties definiëren voor iedere veldnaam
                                                                        for(i=0;i<fieldnames.length;i++)
                                                                        {
                                                                                var nieuwecode = 'this.'+fieldnames[i]+'= null;' ;
                                                                                code = code + nieuwecode;
                                                                        }

                                                                code = ' resultSet = function () { ' + code + '}';

                                                                //door de variabele code te evalueren krijgen we een custom class
                                                                eval(code);

                                                                //nu moeten we voor ieder van onze resultaatrijen een instantie van resultset aanmaken
                                                                //deze instanties moeten opgeslagen worden in een array
                                                                var rows = new Array();

                                                                for(i=0;i<resultrows.length;i++)
                                                                {
                                                                        var instantie = new resultSet();
                                                                        var toewijzingscode = '';

                                                                        var huidigerij = resultrows[i];

                                                                        for(x=0;x<fieldnames.length;x++)
                                                                        {
                                                                                //binnen de resultset moet de property de waarde van de row krijgen
                                                                                if(huidigerij.getElementsByTagName(fieldnames[x])[0].firstChild)
                                                                                {
                                                                                        var fieldvalue = huidigerij.getElementsByTagName(fieldnames[x])[0].firstChild.nodeValue;
                                                                                }
                                                                                else
                                                                                {
                                                                                        var fieldvalue = null;
                                                                                }									
                                                                                var naam = fieldnames[x];

                                                                                toewijzingscode = toewijzingscode + 'instantie.' + naam + '="' + fieldvalue + '";';

                                                                        }
                                                                        eval(toewijzingscode);
                                                                        rows.push(instantie);

                                                                        that.result = rows;
                                                                }


                                                        }
                                                        else
                                                        {
                                                                //Er is geen datareturn
                                                        }

                                                }
                                                else if(result == 'error')
                                                {
                                                        //De server weigert de request en geeft een foutboodschap terug
                                                        that.successIndicator = false;

                                                        //De foutboodschappen komen terug via een vaste XML structuur die dus geparsed kan worden
                                                        var errortags = xml.getElementsByTagName("error");
                                                        var errorlist = new Array();

                                                        for(i=0;i<errortags.length;i++)
                                                        {							
                                                                //class Error
                                                                function Error(parameter,value)
                                                                {
                                                                        //publieke properties
                                                                        this.parameter = parameter;
                                                                        this.value = value;
                                                                }

                                                                newerror = new Error(errortags[i].getElementsByTagName('parameter')[0].firstChild.nodeValue,errortags[i].getElementsByTagName('message')[0].firstChild.nodeValue);							

                                                                errorlist[i] = newerror;
                                                        }

                                                        that.errorList = errorlist;
                                                }
                                                else
                                                {
                                                        throw "onverwacht resultaat: resulttag heeft onverwachte waarde";
                                                }
                                                //De functie die op onComplete staat wordt uitgevoerd
                                                that.onComplete(request);					
                                            }
                                            else
                                                {
                                                    throw "onverwacht resultaat: resulttag ontbreekt. Response was: " + request.responseText;
                                                }
				}
			}
		}
	 }

	 request.open("POST",connectionpoint,true);
	 
	//Lijn gedesactiveerd op aandraden van fora. Nu worden $_POST en $_FILE correct ingevuld. 
        /*request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");*/
	 
	 if(progressindicator)
	 {
	 progressindicator.style.visibility="visible";
	 }
	 
	 request.send(completePoststring());
 };
 
}
