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
    previewImage.classList.add('thumb');
    
    var reader = new FileReader();
    reader.onload = function(e) {previewImage.src = e.target.result;};
    reader.readAsDataURL(file);
    
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

function uploadMonitor(fileName,dataUrl)
{
    //private vars
    
    //private methods
    
    //CONSTRUCTOR
        //Eerst halen we de div voor de uploads op
        var uploadList = document.getElementById('uploads');
        
        //We creëren de verschillende items
            //De div met de preview
            var previewDiv = document.createElement('div');
            previewDiv.classList.add('preview');
            previewDiv.innerHTML = "<img src='/modules/fotoalbum/photos/13.jpg'>";
            
            //Het rechterdeel met de statusgegevens
            var statusDiv = document.createElement('div');
            statusDiv.classList.add('status');
            
                //De bestandsnaam
                var filename = document.createElement('h2');
                filename.innerHTML = fileName;
                
                //progressindicator
                var progressIndicator = document.createElement('div');
                progressIndicator.innerHTML = "<img src='"+"/modules/fotoalbum/presentation/assets/opladen.gif"+"'> Bezig met kopiëren naar de server...";
                
                statusDiv.appendChild(filename);
                statusDiv.appendChild(progressIndicator);
                
        
        //We hangen alles samen op één div met de class uploadMonitor
        var mainDiv = document.createElement('div');
        mainDiv.classList.add('uploadMonitor');
        
        mainDiv.appendChild(previewDiv);
        mainDiv.appendChild(statusDiv);
        
        uploadList.appendChild(mainDiv);
        
    
    
    //public methods
    this.changeStatus= function(status,errorlist)
    {
        if(status==='ok')
            {
                progressIndicator.innerHTML = '<img src="/modules/fotoalbum/presentation/assets/tick.png">Afbeelding opgeladen';
                
                //We moeten nu een formulier voorzien om een beschrijving in te voeren
                var descriptionForm = document.createElement('div');
                descriptionForm.classList.add('description');
                descriptionForm.innerHTML = "<form method='post'><label for='description'>Beschrijving</label><textarea cols=50 rows=5></textarea><br/><label for='verzend'>&nbsp;</label><input type='submit' id='verzend' value='verzend'></form>";
                statusDiv.appendChild(descriptionForm);
            }
       else if(status==='error')
           {
               progressIndicator.innerHTML = '<img src="/modules/fotoalbum/presentation/assets/cross.png">Afbeelding niet opgeladen';
               
               var errors = document.createElement('ul');
              
               if(errorlist !== undefined)
               {
                    for(i=0;i<errorlist.length;i++)
                    {
                        var error = document.createElement('li');
                        error.innerHTML = errorlist[i];
                        errors.appendChild(error);
                    }
               }
               
               progressIndicator.appendChild(errors);
           }
    };
}

function addUpload()
{
    var test = new uploadMonitor('test.jpg');
    
    var errorlist = new Array("een","twee");
    
    test.changeStatus('ok');
}