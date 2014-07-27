        function setCookiesOk()
        {
            var ajax = new ajaxTransaction();
            ajax.destination='/core/templatesystem/templatelogic.php';
            ajax.phpfunction='setCookiesOk';
            ajax.onComplete=function(){              
                document.getElementById('cookies').style.display='none';
            };
            ajax.ExecuteRequest();
        }
        