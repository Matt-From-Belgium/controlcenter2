function uploadPicture(formElement)
{
    //De functie wordt geactiveerd met het formulierelement als argument
    //Alle formuliervelden worden automatisch klaargezet in het transactieobject
    
    var uploadTransaction = new ajaxTransaction(formElement);
    
    //Nu moeten we de voortgang tonen
    createUploadMonitor(formElement);
}

function createUploadMonitor(formElement)
{
    //We halen de referentie naar de div uploads op
    var uploadsDiv = document.getElementById('uploads');
 
    //We creëren de basisDiv waar we alles aan zullen hangen
    var imageUploadMonitor = document.createElement('div');
    
    //We halen de gegevens van het geselecteerde bestand op
    var file = document.getElementById('photopath').files[0];
    
    var filename = file.name;
    var filesize = file.size;
    
    //We halen de afbeelding binnen om deze te kunnen weergeven
    var previewImage = document.createElement('img');
    
    var title = document.createElement('div');
    title.innerHTML = filename;
    
    imageUploadMonitor.appendChild(title);
    imageUploadMonitor.appendChild(previewImage);
    
    uploadsDiv.appendChild(imageUploadMonitor);
    
    //Nu kunnen we het bestand beginnen uploaden
    var ajax = new ajaxTransaction('uploadForm');
    ajax.destination='/modules/fotoalbum/logic/albumlogic.php';
    ajax.phpfunction='addPhoto';
    ajax.onComplete = function(){processResponse(ajax);};
    ajax.ExecuteRequest();
}

function processResponse()
{
    alert('ok');
}