/*function getAlbumPhotos(id,onCompleteFunction)
{
    if(id)
    {
        var ajax = new ajaxTransaction();
        ajax.addData('albumid',id);
        ajax.destination = '/modules/fotoalbum/logic/ajaxLogic.php';
        ajax.phpfunction = 'GetAlbumPhotosAjax';
        
        ajax.onComplete = function(onCompleteFunction){
            if(ajax.successIndicator)
                {
                    var photoCollection= new Array();
                    for(i=0;i<ajax.result.length;i++)
                        {
                            var newPhoto = new photo;
                            newPhoto.setFilename(ajax.result[i].filename);
                            newPhoto.id= ajax.result[i].id;
                            newPhoto.description = ajax.result[i].description;
                            
                            photoCollection.push(newPhoto);
                        }
                    
                    if(typeof onCompletefunction != 'function')
                        {
                            throw 'onComplete is not a valid function';
                            
                        }
                    else{
                       onComplete(photoCollection); 
                    }
                    
                }
            
        };
        
        ajax.ExecuteRequest();
    }
    else
    {
        throw exception('id must be set')
    }
}*/

function albumLoader(id)
{
    //private vars
    var that = this;
    var id= id;
    
    //public vars
    this.onComplete=null;
    this.photoCollection = new Array();
    
    //constructor
        //id is verplicht
        if(!id)
            {
              throw 'id must have a value'  ;
            }
    
    //public methods
    this.load = function()
    {
        //checken of onComplete wel degelijk een correcte functie is
        if(typeof that.onComplete != 'function')
            {
                throw 'onComplete is not a valid function';
            }
        else
            {
                //Alles lijkt ok => albumgegevens ophalen
                var ajax = new ajaxTransaction();
                ajax.addData('albumid',id);
                ajax.destination = '/modules/fotoalbum/logic/ajaxLogic.php';
                ajax.phpfunction = 'GetAlbumPhotosAjax';
                
                ajax.onComplete = function(onCompleteFunction) 
                {
                if(ajax.successIndicator)
                    {
                        var photoCollection= new Array();
                        for(i=0;i<ajax.result.length;i++)
                            {
                                var newPhoto = new photo;
                                newPhoto.setFilename(ajax.result[i].filename);
                                newPhoto.id= ajax.result[i].id;
                                newPhoto.description = ajax.result[i].description;

                                
                                photoCollection.push(newPhoto);
                            }
                            
                        that.photoCollection = photoCollection;

                        that.onComplete();
              

                    }
                else
                    {
                        throw('server error');
                    }
                
                 };
                 
                 ajax.ExecuteRequest();
            }
    
    
    };
}

function photo()
{
    //private vars
    var filename;
    var path;
    var tnPath;
    
    
    
    //public vars
    this.description;
    this.id;
    this.src;
    this.thumbnail;
    
    this.setFilename= function(name)
    {
        filename = name;
        
        //onmiddellijk inladen van de afbeelding
        path = '/modules/fotoalbum/photos/'+filename;
        var img =new Image();
        img.src = path;
        this.src = img.src;
        
        
        //en inladen van de thumbnail
        tnPath = '/modules/fotoalbum/photos/tn_'+filename;
        var tnImg = new Image();
        tnImg.src = tnPath;
        this.thumbnail = tnImg.src;
    };
    
    this.getFilename = function() {
        return filename;
    };
    
    this.getPath = function() {
        return path;
    };
}

function photoViewer(albumid,previewElement)
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
        var photoDisplay = new photoDisplayer;
        
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
            
            /*imageDiv.onclick = function(){window.open(photo.src);};*/
            imageDiv.onclick = function()
            {
                photoDisplay.setCollection(photoCollection);
                photoDisplay.displayImage(photoIndex);
            };
            
            var imageTag = document.createElement('img');
            imageTag.src = photoCollection[photoIndex].thumbnail;
            
            imageDiv.appendChild(imageTag);
            return imageDiv;
        }
        
 
    
    //public methods
}

function photoDisplayer()
{
    //private vars
    var displayPhotoCollection=null;
    var currentIndex=null;
    var that = this;
    
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
       
       var photoContainer = document.createElement('div');
       photoContainer.id='photoContainer';
       
       var imageTag = document.createElement('img');
       imageTag.id='photo';
       
       var description = document.createElement('div');
       description.id='description';
       description.innerHTML='test';
       
       var controls = document.createElement('div');
       controls.id='controls';
       
       
       
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
           
           displayContainer.style.display='block';
           
           photoObject = displayPhotoCollection[photoIndex];
           
           imageTag.src = photoObject.src;
           
           //Wanneer een gebruiker op de afbeelding klikt moet de volgende
           //weergegeven worden
           imageTag.onclick = function(e){
               
               //Wanneer dit event geactiveerd wordt moeten we zorgen dat het onCLick op de onderliggende
               //laag niet geactiveerd wordt, dit doen we door het propageren tegen te gaan.
               var event = e || window.event;
               e.cancelBubble=true;
               
               that.nextImage();
               
           };
           
           if(photoObject.description !== 'null')
               {
                   description.innerHTML=photoObject.description;
                   description.style.visibility='visible';
               }
           else
               {
                   description.style.visibility='hidden';
                   description.innerHTML=null;
               }
    };

    this.nextImage=function()
    {
        currentIndex++;
        this.displayImage(currentIndex);
    };
}

