//ajaxTransactionList houdt alle transacties met de server bij zodat er acties uitgevoerd kunnen worden
function ajaxTransactionList()
{
    //private variabelen
    var innerTransactionList = new Array();
    
    //constructor
    //private functies
    
    //publieke functies
    this.addTransaction= function(transaction)
    {
        innerTransactionList.push(transaction);
    };
    
    this.cancelGroup = function(transactionGroup,except)
    {
      //Deze functie annuleert alle transacties behalve de transactie die opgegeven wordt in except
      
      for(i=0;i<innerTransactionList.length;i++)
      {
          if((innerTransactionList[i].getTransactionGroup()===transactionGroup)&&(innerTransactionList[i]!==except)&&(innerTransactionList[i].getStatus()>-1)&&(innerTransactionList[i].getStatus()<3))
          {
              innerTransactionList[i].cancelRequest();
          }
      }
    };
}

var ajaxTransactionList = new ajaxTransactionList();

//ajaxTransaction wordt gebruikt voor iedere ajax communicatie
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
  var transactionGroup = null;
  var status = 0;
  //Revisie 2: er wordt een statusvariabele gedefinieerd
  //-1: geannuleerd
  // 0: aangemaakt
  // 1: verzonden
  // 2: antwoord ontvangen
  // 3: voltooid

 
 //publieke variabelen
  this.onComplete=null;
  this.successIndicator = null;
  this.errorList=null;
  this.resultLength=null;
  this.result = null;
  this.destination = null;
  this.phpfunction = null;
  //Revisie 2: er wordt een groepsnaam voorzien zodat ajaxqueries van een bepaalde groep afgebroken kunnen
  //worden
  
 
 //constructor functionaliteit
 if(formElement)
    {
    constructor(formElement);
    }
 
 //we voegen onze transactie toe aan ajaxTransactionList
 ajaxTransactionList.addTransaction(this);
 
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
 
 function setStatus(newStatus)
 {
     //Als de status al -1 is mag er niks meer gewijzigd worde
     //annulatie is onherroepelijk
     if(status>-1)
     {
         //de request is niet gecancelled
        status = newStatus;    
     }
     
 }
 
 
 //public methods
 this.getStatus= function()
 {
     return status;
 };
 
 this.setTransactionGroup = function (value)
   {
       //We annuleren de andere transacties van dezelfde groep
       ajaxTransactionList.cancelGroup(value);
       
       //We geven transactionGroup een waarde
       transactionGroup=value;
   };
   
 this.getTransactionGroup = function()
 {
     return transactionGroup;
 };
   
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
 
 this.cancelRequest = function() 
 {
     //Deze functie verandert de status naar -1
     //Dit heeft enkel zin als deze nog niet afgewerkt is
     if(status<3)
     {
         setStatus(-1);
         //alert('request cancelled');
     }
     //-1 is de flag zodat de onComplete niet meer uitgevoerd wordt.
 };
 
 this.ExecuteRequest = function()
 {
	 var request = getHTTPObject();
	 request.onreadystatechange = function() 
	 { 
                if(progressindicator)
                {
                progressindicator.style.visibility='visible';
                }
					                
	 	if(request.readyState == 4)
		{
			if(request.status == 200)
			{	
                                setStatus(2);
				if(typeof that.onComplete != 'function')
				{
					//Er is geen functie gedefinieerd in onComplete => exception
					throw "onComplete() is niet gedefinieerd";
				}
				else
				{

					
					//De gegevens worden weggeschreven naar klassevariabelen waar ze evt met de onComplete functie kunnen worden opgehaald
					//Aangezien onComplete pas hierna wordt uitgevoerd ben je hier wel zeker dat die variabelen een waarde hebben bij het
					//uitvoeren van onComplete()
                                        //MAAR: we controleren of de request niet gecancelled is
					if(that.getStatus()>-1)
                                        {
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
                                                                                    
                                                                                    //BUGFIX: als we html terugkrijgen met CSS dan loopt de javascript vast door de ;
                                                                                    //Door encodeURIComponent lossen we dit op
                                                                                    fieldvalue = encodeURIComponent(fieldvalue);
                                                                                    
                                                                                    toewijzingscode = toewijzingscode + 'instantie.' + naam + '="' + fieldvalue + '";';
                                                                                    
                                                                                    //BUGFIX: we willen dat de waarde bij teruggave nog steeds de speciale karakters bevat
                                                                                    toewijzingscode = toewijzingscode + 'instantie.' + naam + '=' + 'decodeURIComponent(instantie.' + naam + ');';

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
                                                    //Maar alleen als de request op dit moment nog niet gecancelled is
                                                    if(that.getStatus()>-1)
                                                    {
                                                        that.onComplete(request);		
                                                        setStatus(3);
                                                    }
                                                }
                                                else
                                                    {
                                                        throw "onverwacht resultaat: resulttag ontbreekt. Response was: " + request.responseText;
                                                    }
                                        }
                                        
                                        if(progressindicator)
					{
					progressindicator.style.visibility='hidden';
					}
				}
			}
		}
	 };

	 request.open("POST",connectionpoint,true);
	 
	//Lijn gedesactiveerd op aandraden van fora. Nu worden $_POST en $_FILE correct ingevuld. 
        /*request.setRequestHeader("Content-Type","application/x-www-form-urlencoded");*/
	
	 request.send(completePoststring());
         setStatus(1);
 };
 
}
