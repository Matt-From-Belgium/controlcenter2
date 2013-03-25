var errordiv = document.getElementById('errorlist');
errordiv.innerHTML=null;

function addAlbum(albumNaam)
{
    //eventuele vorige foutboodschappen worden verwijderd
    errordiv.innerHTML = null;
    
    //Er moet een verbinding gelegd worden met de server
    var transactie = new ajaxTransaction();
    transactie.destination='/modules/fotoalbum/logic/albumlogic.php';
    transactie.phpfunction = 'addAlbum';
    
    transactie.onComplete= function(){ addAlbumResponse(transactie)};
    
    transactie.addData('albumnaam',albumNaam);
    
    transactie.ExecuteRequest();
}

function addAlbumResponse(transactie)
{
    if(transactie.successIndicator)
        {
            //De bewerking is geslaagd
            alert('Album toegevoegd');
        }
    else
        {
            //Er is een probleem
            //We moeten de errors visualiseren
            //alert(transactie.errorList.length + ' fouten opgetreden');
            

            
            for(i=0;i<transactie.errorList.length;i++)
                {
                    var currentError = transactie.errorList[i];
                    //De foutmeldingen moeten weergegeven worden in de div errors
                    errordiv.innerHTML = errordiv.innerHTML + currentError.value + '<BR />';
                }
        }
           
}

function getAlbums()
{
    var transactie = new ajaxTransaction();
    
    transactie.destination = '/modules/fotoalbum/logic/albumlogic.php';
    transactie.phpfunction = 'getAlbums';
    transactie.onComplete = function() { showAlbums(transactie)};
    
    transactie.ExecuteRequest();
}

function showAlbums(transactie)
{
    alert("antwoord");
    if(transactie.successIndicator)
        {
            for(i=0;i<transactie.result.length;i++)
                {
                    alert(transactie.result[i].name);
                }
        }
}

getAlbums();