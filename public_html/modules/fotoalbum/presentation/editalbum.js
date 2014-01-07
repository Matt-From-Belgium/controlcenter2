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
             
             //Een absoluut gepositioneerde div voor de afsluitknop
             var closeButton = document.createElement('div');
             closeButton.style.visibility = 'hidden';
             closeButton.classList.add('closeMonitor');
             closeButton.innerHTML='<img src="/modules/fotoalbum/presentation/assets/close-icon.png">';
             
             
             
        
        //We hangen alles samen op één div met de class uploadMonitor
        var mainDiv = document.createElement('div');
        mainDiv.classList.add('uploadMonitor');
        
        mainDiv.appendChild(previewDiv);
        mainDiv.appendChild(statusDiv);
        mainDiv.appendChild(closeButton);
        
        
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
                descriptionForm.innerHTML = "<form method='post'><input type='hidden' id='photoid' value='"+ this.newId +"'><label for='description'>Beschrijving</label><textarea cols=50 rows=5 id='newdescription'></textarea><br/><label for='verzend'>&nbsp;</label><input type='button' id='verzend' value='verzend' onClick='javascript:saveDescription(this)'><span id='loadIndicator' style='visibility:hidden'>Bezig met opslaan...</span></form>";
                statusDiv.appendChild(descriptionForm);
                
                //De afsluitenknop mag nu getoond worden
                mainDiv.onmouseover = function(){closeButton.style.visibility='visible';closeButton.style.cursor='pointer';};
                mainDiv.onmouseout = function(){closeButton.style.visibility='hidden';};
                closeButton.onclick = function(){uploadList.removeChild(mainDiv);};
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
    var newdescription = null;
    var loadIndicator = null;
    //Aangezien getElementById blijkbaar niet werkt op element-niveau moeten we
    //lussen om de elementen te overlopen
    for(i=0;i<form.childNodes.length;i++)
        {
            if(form.childNodes[i].id==='photoid')
                {
                    photoid=form.childNodes[i].value;
                }
            if(form.childNodes[i].id === 'newdescription')
                {
                    newdescription = form.childNodes[i].value;
                }
            if(form.childNodes[i].id === 'loadIndicator')
                {
                    //We halen de span loadIndicator eruit om daar de vooruitgang te tonen
                    loadIndicator = form.childNodes[i];
                }
        }
        
    //We maken de loadIndicator zichtbaar om te tonen aan de gebruiker dat er iets gebeurt
    //We stellen ook de innerHTML in want het kan de tweede keer zijn dat er gewijzigd wordt
    loadIndicator.innerHTML = 'Bezig met opslaan...';
    loadIndicator.style.visibility='visible';
        
    var ajax = new ajaxTransaction();
    
    ajax.addData('id',photoid);
    ajax.addData('newdescription',newdescription);
    
    ajax.destination='/modules/fotoalbum/logic/albumlogic.php';
    ajax.phpfunction='changeDescription';
    
    ajax.onComplete= function(){changeDescriptionComplete(ajax,loadIndicator);};
    ajax.ExecuteRequest();
        
        //We hebben de nodige variabelen opgehaald, nu kunnen we alles verzenden
        //changeDescription(photoid,description,loadIndicator);
}

/*function changeDescription(id,value)
{
    var ajax = new ajaxTransaction();
    
    ajax.addData('id',id);
    ajax.addData('description',value);
    
    ajax.destination='/modules/fotoalbum/logic/albumlogic.php';
    ajax.phpfunction='changeDescription';
    
    ajax.onComplete= function(){changeDescriptionComplete(ajax);};
    ajax.ExecuteRequest();
}*/

function changeDescriptionComplete(ajax,loadIndicator)
{
    if(ajax.successIndicator)
    {
        loadIndicator.innerHTML = 'Wijzigingen opgeslagen';
    }
    else
    {
        loadIndicator.innerHTML = 'FOUT!';
    }
}



function albumEditor(albumid,previewElement)
{
    //private vars
    var photoCollection = null;
    
    //public vars
    
    //constructor
        //We gaan eerst het album inladen
        var loader = new albumLoader(albumid);
        loader.onComplete=function(){
            photoCollection = loader.photoCollection;
            populateElement();
        };
        
        loader.load();
    
        //Nu moeten we een element aanmaken voor de weergave
        var photoDisplay = new photoEditor;
        
    //private methods
        function populateElement()
        {
            //De foto's zijn ingeladen en nu voegen we die toe aan het element
            if(document.getElementById(previewElement))
                {
                    //Element gevonden in de DOM structuur => we kunnen verder
                    var photoPreviewElement = document.getElementById(previewElement);
                    
                    for(i=0;i<photoCollection.length;i++)
                        {
                            var preview=createImage(i);
                            photoPreviewElement.appendChild(preview);
                            
                            //Nu gaan we de foto zelf voorladen, thumbs worden geladen bij de creatie van het
                            //photo object
                            photoCollection[i].preLoad();
                        }
                     
                    
                    
                }
            else
                {
                    //Element niet gevonden => stop
                    throw 'Element not found';
                }
                
             
        }
        
        function createImage(photoIndex)
        {
            
            //We creëren met deze functie een kant en klare div om te koppelen
            //aan het ogegeven HTML DOM element
            var imageDiv = document.createElement('div');
            imageDiv.classList.add('imagePreview');
            
            
            imageDiv.onmouseover = function() {
                //Er zit één div onder imageDiv met de optieknoppen
                optionsDiv.classList.toggle('imageOptionsOver');
                optionsDiv.classList.toggle('imageOptions');
            };
            
             imageDiv.onmouseout= function() {
                //Er zit één div onder imageDiv met de optieknoppen
                optionsDiv.classList.toggle('imageOptionsOver');
                optionsDiv.classList.toggle('imageOptions');
            };
            
            /*imageDiv.onclick = function()
            {
                photoDisplay.setCollection(photoCollection);
                photoDisplay.displayImage(photoIndex);
            };*/
            
            var imageTag = document.createElement('img');
            imageTag.src = photoCollection[photoIndex].thumbnail;
            
            imageDiv.appendChild(imageTag);
            
            var optionsDiv = document.createElement('div');
            
                //Options div bevat 2 knoppen: verwijderen en aanpassen
                var deletePhotoButton = document.createElement('a');
                
                deletePhotoButton.innerHTML = 'verwijder';
                deletePhotoButton.onclick = function() {
                    var bevestiging = confirm('Bent u zeker dat u deze foto wil verwijderen?');
                    
                    if(bevestiging)
                    {
                        deletePhoto(photoCollection[photoIndex]);
                    }
                };
                
               
                
                var editDescriptionButton = document.createElement('a');
                
                editDescriptionButton.innerHTML = 'aanpassen';
                
                editDescriptionButton.onclick = function(){
                    photoDisplay.setCollection(photoCollection);
                    photoDisplay.displayImage(photoIndex);
                };    
            
            
            optionsDiv.classList.add('imageOptions');
            
            optionsDiv.appendChild(editDescriptionButton);
             optionsDiv.appendChild(deletePhotoButton);
            
            imageDiv.appendChild(optionsDiv);
            return imageDiv;
        }
        
 
    
    //public methods
}

function deletePhoto(photo)
{
    //De foto moet verwijderd worden
    var ajax = new ajaxTransaction();
    ajax.addData('id',photo.id);
    
    ajax.destination = '/modules/fotoalbum/logic/albumlogic.php';
    ajax.phpfunction = 'deletePhoto';
    
    ajax.onComplete = function() {
        if(ajax.successIndicator)
        {
            alert('photo verwijderd');
        }
        
    };
    
    ajax.ExecuteRequest();
}

function photoEditor()
{
    //private vars
    var displayPhotoCollection=null;
    var currentIndex=null;
    var that = this;
    var slideshowDelay = 3000;
    var slideShowInterval = null;
    
    //constructor
       //We halen het body element op
       var body = document.getElementsByTagName('body')[0];
 
       //We creëren een container die zich over het ganse oppervlak van de pagina moet kunnen zetten
       //Zo krijgen we een 'dim the lights' effect
       var displayContainer = document.createElement('div');
       displayContainer.id= 'displayContainer';
       displayContainer.style.display='none';
       
            //Wanneer de gebruiker naast de viewer klikt moet alles afsluiten
            displayContainer.onclick = function(){
                
            displayContainer.style.display='none';
                
            
            };
       
       //Binnen displayContainer moeten we nu onze elementen van de viewer creëren
       var photoDisplayer = document.createElement('div');
       photoDisplayer.id='photoDisplayer';
       
       photoDisplayer.onclick = function (e) {
           //Enkel wanneer er op de displaycontainer geklikt wordt mag alles afgesloten worden
           //Hier moeten we dit dus blokkeren.
           var event = e || window.event;
               e.cancelBubble=true;
       };
       
       var photoContainer = document.createElement('div');
       photoContainer.id='photoContainer';
       
       var imageTag = document.createElement('img');
       imageTag.id='photo';
       
       var description = document.createElement('div');
       description.id='description';
       
        //Formulier voor het wijzigen van de beschrijving
        var descriptionFormTag = document.createElement('form');
        descriptionFormTag.id='descriptionform';
        
        var descriptionTextBox = document.createElement('textarea');
        descriptionTextBox.setAttribute('rows',3);
        descriptionTextBox.style.width='80%';
        descriptionTextBox.style.display= 'inline-block';
   
        var descriptionProgressDiv = document.createElement('div');
        
        descriptionProgressDiv.style.display='hidden';
        
        
        var descriptionSubmitButton = document.createElement('input');
        descriptionSubmitButton.setAttribute('type','button');
        descriptionSubmitButton.setAttribute('value','Opslaan');
        descriptionSubmitButton.style.display= 'inline-block';
        

        descriptionSubmitButton.onclick = function(){
                var ajax = new ajaxTransaction();
    
                ajax.addData('id',displayPhotoCollection[currentIndex].id);
                
                ajax.addData('newdescription',descriptionTextBox.value);

                ajax.destination='/modules/fotoalbum/logic/albumlogic.php';
                ajax.phpfunction='changeDescription';

                ajax.onComplete= function(){
                    
                };
                ajax.ExecuteRequest();
        };
        
        descriptionFormTag.appendChild(descriptionTextBox);
        descriptionFormTag.appendChild(descriptionProgressDiv);
        descriptionFormTag.appendChild(descriptionSubmitButton);
        
        description.appendChild(descriptionFormTag);
       
       var controls = document.createElement('div');
       controls.id='controls';
       
            var nextPhotoButton = document.createElement('img');
            nextPhotoButton.onclick = function(e){
                that.nextImage();
            };
                
            nextPhotoButton.src='/modules/fotoalbum/presentation/assets/volgendeknop.png';
                
            
            
            
            var previousPhotoButton = document.createElement('img');
            previousPhotoButton.onclick = function(e) {
                  
                  that.previousImage();
            };
            
            previousPhotoButton.src = '/modules/fotoalbum/presentation/assets/terugknop.png';
       
       controls.appendChild(previousPhotoButton);
       controls.appendChild(nextPhotoButton);
       
       
       photoContainer.appendChild(imageTag);
       photoContainer.appendChild(description);
       photoContainer.appendChild(controls);
       
       photoDisplayer.appendChild(photoContainer);
       
       displayContainer.appendChild(photoDisplayer);
       
       //We hangen de displayContainer aan body om deze zichtbaar te maken in het DOM Model
       //Het element moet eerst komen na het body element anders zal het effect niet werken
       body.insertBefore(displayContainer,body.firstChild);
    
    this.setCollection = function(photoCollection)
    {
      displayPhotoCollection=photoCollection;
    };
            
    this.displayImage = function(photoIndex)
    {
           
           currentIndex = photoIndex;

           
           photoObject = displayPhotoCollection[photoIndex];  

           //onload werkt alleen op alle browsers als je een nieuwe image aanmaakt.
           var tempImage = new Image();
           tempImage.onload = function(){
               //Nu gaan we detecteren of het een rechtstaande of liggende afbeelding is
               
               
                 if(tempImage.height>tempImage.width)
                    {

                        photoDisplayer.classList.add('portraitMode');
                    }
                else
                    {

                        photoDisplayer.classList.remove('portraitMode');
                    }
               
                imageTag.src = photoObject.src;
                
                displayContainer.style.display='block';
           };
           
           tempImage.src = displayPhotoCollection[photoIndex].src;
        
          
           
           if(photoObject.description !== 'null')
               {
                  descriptionTextBox.value = photoObject.description;
               }
           else
                {
                    descriptionTextBox.value = '';
                }
               
            
    };
    
    this.previousImage = function() {
        currentIndex--;
        if(currentIndex<0)
            {
                currentIndex=displayPhotoCollection.length - 1;
            }
            
        this.displayImage(currentIndex);
    };

    this.nextImage=function()
    {
        currentIndex++;
        
        if(currentIndex >= (displayPhotoCollection.length))
            {
                currentIndex = 0;
            }
            
        this.displayImage(currentIndex);
    };
    
    
}