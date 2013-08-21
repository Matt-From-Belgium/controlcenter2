function uploadPicture(formElement)
{
    //De functie wordt geactiveerd met het formulierelement als argument
    //Alle formuliervelden worden automatisch klaargezet in het transactieobject
    
    var uploadTransaction = new ajaxTransaction(formElement);
    
    //Nu moeten we de voortgang tonen
    createUpload(formElement);
}

function createUpload(formElement)
{
    //We halen de referentie naar de div uploads op
    var uploadsDiv = document.getElementById('uploads');
 
    //We creëren de basisDiv waar we alles aan zullen hangen
    var imageUploadMonitor = document.createElement('div');
    
    //We halen de gegevens van het geselecteerde bestand op
    var file = document.getElementById('photopath').files[0];
    
    var filename = file.name;
    
    //We maken de loadmonitor aan
    var upload = new uploadMonitor(file);
    
    //Nu kunnen we het bestand beginnen uploaden
    var ajax = new ajaxTransaction('uploadForm');
    ajax.destination='/modules/fotoalbum/logic/albumlogic.php';
    ajax.phpfunction='addPhoto';
    ajax.onComplete = function(){processResponse(ajax,upload);};
    ajax.ExecuteRequest();
}

function processResponse(ajax,upload)
{
    //Eerst kijken we of de upload succesvol was
    if(ajax.successIndicator)
        {
            //We passen de uploadMonitor aan
            upload.changeStatus('ok');
        }
    else
        {
            //Er is iets fout gelopen, errors weergeven
            var errors = new Array();
            
            for(i=0;i<ajax.errorList.length;i++)
                {
                    errors.push(ajax.errorList[i].value);
                }
            
            upload.changeStatus('error',errors);
        }
}

function uploadMonitor(file)
{
    //private methods
    function loadPreview(file)
    {
            var reader = new FileReader();
            reader.onload = function(e) {previewElement.src = e.target.result;};
            reader.readAsDataURL(file);
    }
    
    //CONSTRUCTOR
        //Eerst halen we de div voor de uploads op
        var uploadList = document.getElementById('uploads');
        
        //We creëren de verschillende items
            //De div met de preview
            var previewDiv = document.createElement('div');
            previewDiv.classList.add('preview');
            
                var previewElement = document.createElement('img');
                loadPreview(file);
                
            previewDiv.appendChild(previewElement);
            
            //Het rechterdeel met de statusgegevens
            var statusDiv = document.createElement('div');
            statusDiv.classList.add('status');
            
                //De bestandsnaam
                var filename = document.createElement('h2');
                filename.innerHTML = file.name;
                
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

/*DEBUG
function addUpload()
{
    var test = new uploadMonitor('test.jpg');
    
    var errorlist = new Array("een","twee");
    
    
}*/