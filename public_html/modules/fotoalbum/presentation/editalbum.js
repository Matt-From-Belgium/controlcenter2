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
    
    //De laadbalk die moet aangeven of de upload nog bezig is
    var loadindicator = document.createElement('div');
    loadindicator.id = 'loadindicator';
    loadindicator.innerHTML = "<img src='/modules/fotoalbum/presentation/assets/opladen.gif'>";
    
    imageUploadMonitor.appendChild(title);
    imageUploadMonitor.appendChild(previewImage);
    imageUploadMonitor.appendChild(loadindicator);
    
    uploadsDiv.appendChild(imageUploadMonitor);
    
    //Nu kunnen we het bestand beginnen uploaden
    var ajax = new ajaxTransaction('uploadForm');
    ajax.destination='/modules/fotoalbum/logic/albumlogic.php';
    ajax.phpfunction='addPhoto';
    ajax.onComplete = function(){processResponse(ajax,imageUploadMonitor);};
    ajax.ExecuteRequest();
}

function processResponse(ajax,uploadMonitor)
{
    
    var loadindicator = null;
    
    //We moeten op zoek naar de loadIndicator
    for(i=0;i<uploadMonitor.childNodes.length;i++)
        {
            if(uploadMonitor.childNodes[i].id==='loadindicator')
                {
                    loadindicator = uploadMonitor.childNodes[i];
                }
        }
        
    loadindicator.innerHTML = '<img src="/modules/fotoalbum/presentation/assets/green-check-icon.png">Afbeelding opgeladen';
}