// JavaScript Document
var contactform = document.getElementById("berichtform");
var inputs = contactform.getElementsByTagName("input");

for(var i =0;i<inputs.length;i++)
{
	//Om de validator ook bij edits te kunnen gebruiken is er een identifier nodig die de uniciteit van het object
	//bepaalt
	var id = 0;
	
	//Alle velden behalve het veld password2 worden via de php validator geverifieerd.
	inputs[i].onblur = function() { ValidateField(this,"/contact/berichtajax.php",id) };
}

var textbox = document.getElementById("bericht");
textbox.onblur = function() { ValidateField(this,"/contact/berichtajax.php",id) };