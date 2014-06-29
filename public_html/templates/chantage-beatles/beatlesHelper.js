/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function switchBeatle(member)
{
    var beatlesBio = document.getElementById('beatlesBio');
    var theBeatles = ['Paul','John','Ringo','George'];
    
    //De container tonen
    beatlesBio.classList.add('beatlesBioVisible');
    beatlesBio.classList.remove('beatlesBioHidden');
    
    //De juiste bio tonen
    //Deze is geselecteerd => bio tonen
    document.getElementById(member).style.display='block';
    
    //Deze functie heeft als doel om de juiste biografie te tonen
    for(i=0;i<theBeatles.length;i++)
    {
        if(theBeatles[i] !== member)
        {
            document.getElementById(theBeatles[i]).style.display='none';
        }
    }
    
}
