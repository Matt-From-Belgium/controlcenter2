function uploadPicture(formElement)
{
    //De functie wordt geactiveerd met het formulierelement als argument
    //Alle formuliervelden worden automatisch klaargezet in het transactieobject
    
    var uploadTransaction = new ajaxTransaction(formElement);
    var albumId = document.getElementById('album').value;
    
    //We halen de gegevens van het geselecteerde bestand op
    var selectedFiles = document.getElementById('photopath').files;
    
    for(i=0;i<selectedFiles.length;i++)
        {
            createUpload(selectedFiles[i],albumId);
        }
    /*
    //Nu moeten we de voortgang tonen
    createUpload(file);
    */
   
    //We maken het uploadformulier leeg
    document.getElementById('photopath').value='';
}

function createUpload(file,albumId)
{
    //We halen de referentie naar de div uploads op
    var uploadsDiv = document.getElementById('uploads');
    
    var filename = file.name;
    
    //We maken de loadmonitor aan
    var upload = new uploadMonitor(file);
    
    //Nu kunnen we het bestand beginnen uploaden
    
    /*
    var ajax = new ajaxTransaction('uploadForm');
    */
   
    var ajax = new ajaxTransaction();
    ajax.addData('album',albumId);
    ajax.addData('photopath',file);
    
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
            upload.newId = ajax.result[0].id;
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
    //Public vars
    this.newId= null;
    
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
        
        
       //we willen de mainDiv nu zichtbaar maken door deze aan uploads toe te voegen
       //We willen dat deze bovenaan komt.
        var uploads =uploadList.getElementsByClassName('uploadMonitor');
        
        if(uploads !== undefined)
            {
                uploadList.insertBefore(mainDiv,uploads[0]);
            }
       
        //uploadList.appendChild(mainDiv);
        
    
    
    //public methods
    this.changeStatus= function(status,errorlist)
    {
        if(status==='ok')
            {
                progressIndicator.innerHTML = '<img src="/modules/fotoalbum/presentation/assets/tick.png">Afbeelding opgeladen';
                
                //We moeten nu een formulier voorzien om een beschrijving in te voeren
                var descriptionForm = document.createElement('div');
                descriptionForm.classList.add('description');
                descriptionForm.innerHTML = "<form method='post'><input type='hidden' id='photoid' value='"+ this.newId +"'><label for='description'>Beschrijving</label><textarea cols=50 rows=5 id='description'></textarea><br/><label for='verzend'>&nbsp;</label><input type='button' id='verzend' value='verzend' onClick='javascript:saveDescription(this)'></form>";
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

function saveDescription(formElement)
{
    //We krijgen als formElement de submitknop waarop geklikt werd.
    //We gaan dus eerst op zoek naar het bovenliggende form element
    var form = formElement.parentNode;
    var photoid = null;
    var description = null;
    //Aangezien getElementById blijkbaar niet werkt op element-niveau moeten we
    //lussen om de elementen te overlopen
    for(i=0;i<form.childNodes.length;i++)
        {
            if(form.childNodes[i].id==='photoid')
                {
                    photoid=form.childNodes[i].value;
                }
            if(form.childNodes[i].id === 'description')
                {
                    description = form.childNodes[i].value;
                }
        }
        
        //We hebben de nodige variabelen opgehaald, nu kunnen we alles verzenden
        changeDescription(photoid,description);
}

function changeDescription(id,value)
{
    var ajax = new ajaxTransaction();
    
    ajax.addData('id',id);
    ajax.addData('description',value);
    
    ajax.destination='/modules/fotoalbum/logic/albumlogic.php';
    ajax.phpfunction='changeDescription';
    
    ajax.onComplete= function(){changeDescriptionComplete(ajax)};
    ajax.ExecuteRequest();
}

function changeDescriptionComplete(ajax)
{
    
}

/*DEBUG
function addUpload()
{
    var test = new uploadMonitor('test.jpg');
    
    var errorlist = new Array("een","twee");
    
    
}*/