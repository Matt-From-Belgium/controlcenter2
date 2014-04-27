// JavaScript Document
function veranderMarkering(ditelement)
{
	//De div voorstellingen bevat alle div elementen met id voorstelling. Er wordt een array afgeleid met al deze div's
	var voorstellingen = document.getElementById('voorstellingen');
	var voorstellingen = voorstellingen.getElementsByTagName('DIV');
	
	//Alle voorstellingslijnen moeten weer wit worden. ALS ZE NIET UITERKOCHT ZIJN
	for(i=0;i<voorstellingen.length;i++)
		{
			var element = voorstellingen[i];

			if(element.className !== 'uitverkocht')
			{
			element.style.color='#000000';
			}
		}
		
	//de geselecteerde voorstelling moet oranje worden
	//we proberen aan de div te geraken die boven de radiobutton ligt
	var voorstellingsdiv = ditelement.parentNode;
	voorstellingsdiv.style.color='#FF6600';
}

function berekenKost()
{
	
	var aantalkaarten = document.getElementById('aantal').value;
	
	if(aantalkaarten<10)
	{
	var kostprijs = aantalkaarten*ticketprijs;
	var kostprijs = new Number(kostprijs);
	}
	else
	{
		alert('Om praktische redenen kunnen bij online reservaties slechts 10 tickets tegelijkertijd gereserveerd worden. Gelieve deze reservatie telefonisch uit te voeren.');
		document.getElementById('aantal').value=0;
		kostprijs = new Number(0);
	}
	
	document.getElementById('kostprijs').innerHTML=kostprijs.toFixed(2);
}

function isNumberKey(evt)
{
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
		{
           return false;
		}
		else
		{
        return true;
		}
}
