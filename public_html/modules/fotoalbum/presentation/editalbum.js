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
    var uploadsDiv = document.getElementById('uploads');
 
    //We creëren de basisDiv waar we alles aan zullen hangen
    var imageUploadMonitor = document.createElement('div');
    
    var file = document.getElementById('photopath').files[0];
    
    var filename = file.name;
    var filesize = file.size;
    
    var title = document.createElement('div');
    title.innerHTML = filename;
    
    imageUploadMonitor.appendChild(title);
    uploadsDiv.appendChild(imageUploadMonitor);
}