<?php
##Deze file zorgt voor het doorgeven van een languageconstante naar een javascript
##Er worden 3 variabelen aangeleverd:
## - het type (module of component)
## - de naam van de module/component
## - de naam van de taalconstante die moet teruggegeven worden

require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/languages/languages.php";
getLanguageFilesManually($_POST['type'],$_POST['itemname']);

echo constant($_POST['constantname']);
?>