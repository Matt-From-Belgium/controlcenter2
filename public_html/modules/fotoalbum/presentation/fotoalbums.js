

function addAlbum(albumNaam)
{
    //eventuele vorige foutboodschappen worden verwijderd
    errordiv.innerHTML = null;
    
    //Er moet een verbinding gelegd worden met de server
    var transactie = new ajaxTransaction();
    transactie.destination='/modules/fotoalbum/logic/albumlogic.php';
    transactie.phpfunction = 'addAlbum';
    
    transactie.onComplete= function(){ addAlbumResponse(transactie);};
    
    transactie.addData('albumnaam',albumNaam);
    
    transactie.ExecuteRequest();
}

function addAlbumResponse(transactie)
{
    if(transactie.successIndicator)
        {
            //De bewerking is geslaagd
            //Nu moeten we het nieuwe album weergeven door de albums opnieuw in te laden
            getAlbums();
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
    transactie.onComplete = function() { showAlbums(transactie);};
    
    transactie.ExecuteRequest();
}

function showAlbums(transactie)
{
    //Eerst verwijderen we de inhoud van de tabel albums
    var albums = document.getElementById('albums');
    albums.innerHTML = '';
    
    if(transactie.successIndicator)
        {
            if(transactie.result)
            {
                for(i=0;i<transactie.result.length;i++)
                    {
                        createAlbumRow(transactie.result[i].name,transactie.result[i].id);
                    }
            }
            else
            {
                albums.innerHTML = "Er zijn momenteel geen albums aanwezig...";
            }
        }
}

function createAlbumRow(name,id)
{
    //Deze functie voegt een lijn toe aan de tabel albums
    var rij = document.createElement("tr");
    
    var naamveld = document.createElement("td");
    var optiesWijzigen = document.createElement("td");
    var optiesVerwijderen = document.createElement("td");
   
    var editLink = document.createElement('a');
    editLink.href="/modules/fotoalbum/presentation/editalbum.php?id="+id;
    editLink.innerHTML = "Wijzigen";
    
    var deleteLink = document.createElement('a');
    deleteLink.href='#';
    deleteLink.innerHTML="Verwijderen";
    
    
    naamveld.innerHTML=name;
    optiesWijzigen.appendChild(editLink);
    optiesVerwijderen.appendChild(deleteLink);
    
    rij.appendChild(naamveld);
    rij.appendChild(optiesWijzigen);
    rij.appendChild(optiesVerwijderen);
    
    var albumtabel = document.getElementById("albums");
    albumtabel.appendChild(rij);
}
   


getAlbums();