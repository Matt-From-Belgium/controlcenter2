/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function updateTicketTimer()
{
    var endDate = new Date(2015, 6, 11, 9);
    //var endDate = new Date(2014,2,14,7,46);
    
    
    var date = new Date();
    
    var difference_ms = endDate.getTime() - date.getTime();
    
    if(difference_ms<=0)
    {
        window.location.reload();
    }
    else
    {
        difference_ms = difference_ms/1000;
        var seconds = Math.floor(difference_ms % 60);
        difference_ms = difference_ms/60; 
        var minutes = Math.floor(difference_ms % 60);
        difference_ms = difference_ms/60; 
        var hours = Math.floor(difference_ms % 24);  
        var days = Math.floor(difference_ms/24);

       var datestring = days + 'd ' + hours + 'u ' + minutes + 'm ' + seconds + 's';

       var timerfield = document.getElementById('tickettimer');
       timerfield.innerHTML = datestring;
    }

}