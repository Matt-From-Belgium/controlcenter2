//We zoeken het formulier voor het toevoegen van gebruikers en voegen een event toe zodat er 
//code uitgevoerd wordt wanneer er wijzingen gebeuren.

var adduserform = document.getElementById("userform");
var inputs = adduserform.getElementsByTagName("input");

for(var i =0;i<inputs.length;i++)
{
	//Om de validator ook bij edits te kunnen gebruiken is er een identifier nodig die de uniciteit van het object
	//bepaalt
	var id = document.getElementById("userid").value;
	
	//Alle velden behalve het veld password2 worden via de php validator geverifieerd.
	inputs[i].onblur = function() { ValidateField(this,"/core/logic/usermanagement/adduserajax.php",id) };

	var password2 = document.getElementById("password2");
	password2.onblur = function() { ValidatePassword2(document.getElementById("password"),this) };
	
}

function ValidatePassword2(passwordcontrol,password2control)
{
	//Deze functie controleert of de waarden van password en password2 overeen komen. De control wordt als argument
	//meegegeven omdat je anders de waarde voor het wijzigen krijgt.
	var password = passwordcontrol.value;
	var password2 = password2control.value;
	
	//soms is password gelijk aan -1 wanneer het veld leeg is
	if(password == -1)
	{
		password='';
	}
	
	if(password == password2)
	{
		removeError("password2","uservalidator");
	}
	else
	{
		var errortext = getLanguageConstant("component","usermanagement","LANG_ERROR_PASSWORDMATCH");
		showError("password2",errortext,"uservalidator","userform");
	}
	
}