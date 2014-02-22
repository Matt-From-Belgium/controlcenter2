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
	
	if(aantalkaarten<15)
	{
	var kostprijs = aantalkaarten*8;
	var kostprijs = new Number(kostprijs);
	}
	else
	{
		alert('Vanaf 15 tickets komt u mogelijk in aanmerking voor groepskorting. Voor dergelijke reservaties moet u contact opnemen met ons secretariaat op het nummer 051/50 16 16 of 0473 44 13 16');
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
