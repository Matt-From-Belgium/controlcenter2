/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function linkPage()
{
    registerWithFacebook(function(){
        //De gebruiker heeft toestemming gegeven => we halen de pagina's op waarvan hij gebruiker is
        FB.api('me/accounts?fields=access_token,id,name,picture.type(square)',function(response){
            var buttonDiv = document.getElementById('linkbutton');
            buttonDiv.style.display = 'none';
            
            var pageSelector = document.getElementById('pageselector');
            
            for(i=0;i<response.data.length;i++)
            {
                var pageElement = document.createElement('div');
                pageElement.data = response.data[i];
                
                pageElement.onclick=function(){
                    var confirmationdiv = document.getElementById('confirmation');
                    
                    confirmationdiv.style.display='block';
                    pageSelector.style.display='none';
                    
                    var pageLogo = document.getElementById('pagelogo');
                    pageLogo.src= this.data.picture.data.url;
                    
                    var pageName = document.getElementById('pagename');
                    pageName.innerHTML = this.data.name;
                    
                                    
                    var confirmbutton = document.getElementById('confirmbutton');
                    
                    var that = this;
                    
                    confirmbutton.onclick=function(){
                        //Paginaselectie ok, nu moet het id naar de server gestuurd worden zodat een longterm token
                        //gegenereerd kan worden.
                        var ajax = new ajaxTransaction();
                        ajax.destination = '/core/logic/sociallink/linkfunctions.php';
                        ajax.phpfunction = 'generatePageToken';
                        ajax.addData('pageid',that.data.id);
                        
                        ajax.onComplete = function(){
                            alert('ok');
                        };
                        
                        ajax.ExecuteRequest();
                    };
                };
                
                var pageIcon = document.createElement('img');
                pageIcon.src= response.data[i].picture.data.url;
                
                var pageName = document.createTextNode(response.data[i].name);
                
                pageElement.appendChild(pageIcon);
                pageElement.appendChild(pageName);
                
                pageSelector.appendChild(pageElement);

            }
        });
    },'manage_pages');
}
