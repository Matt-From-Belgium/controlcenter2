/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function togglView(value)
{
    if(value === 'popular')
    {
        //We halen de publicaties op
        var publicatieArray =document.getElementsByClassName('publicatie');
      
        for(i=0;i<publicatieArray.length;i++)
        {
            if(!publicatieArray[i].classList.contains('popular'))
            {
                //Het gaat om een publicatie die niet de class popular heeft => verbergen
                publicatieArray[i].style.display='none';
            }
        }
        
    }
    else
    {
        //Alles moet weergegeven worden
        
        //We halen de publicaties op
        var publicatieArray =document.getElementsByClassName('publicatie');
      
        for(i=0;i<publicatieArray.length;i++)
        {
            publicatieArray[i].style.display='block';
        }
    }
}
