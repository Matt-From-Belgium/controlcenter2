/* 
Alle javascript functionaliteit van de navigatie
 */
              window.onscroll = function(){detectScroll();};
              
              function detectScroll(e)
              {
                  setTimeout(function(){changeNav();},200);
              }
              
              function changeNav(event)
              {
                  
                    var doc = document.documentElement, body = document.body;
                    var left = (doc && doc.scrollLeft || body && body.scrollLeft || 0);
                    var top = (doc && doc.scrollTop  || body && body.scrollTop  || 0);
                    var navElement = document.getElementById('navigation');
                    var siteShellElement = document.getElementById('siteShell');
                    
                    if(top === 0)
                    {
                    navElement.className = 'navBig';
                    siteShellElement.className= 'navBig';
                    }
                    else
                    {
                    navElement.className = 'navSmall';
                    siteShellElement.className= 'navSmall';
                    
                    }
                  
              }
              
              var menu = false;
              
              function toggleMobileMenuState()
              {
                  var menuElement = document.getElementById('navLinks');
                  if(menu===false)
                      {
                          /*Menu is niet zichtbaar => tonen*/
                         menuElement.style.display='block';
                         menu = true;
                      }
                 else
                     {
                         menuElement.style.display='none';
                         menu = false;
                     }
              }
