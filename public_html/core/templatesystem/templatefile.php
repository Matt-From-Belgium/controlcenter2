<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";

function fileaccess_gettemplatehtml($directory)
{
	#Deze functie moet in de directory op zoek gaan naar een template.htm of template.html file
	#Deze staat onder de root als er sprake is van een template dat niet verandert bij verschillende tagen
	#Wanneer er wel taalverschillen zijn dan zijn er subdirectories met de naam van de talen en die worden 
	#afgezocht naar template.htm. Vanzelfsprekend wordt enkel de html file geopend van de taal die actief is.
	
	#Zit er een template.htm of html file in de root
	if(file_exists($directory."/template.htm"))
	{
		$templatefilepath=$directory."/template.htm";
	}
	elseif(file_exists($directory."/template.html"))
	{
		$templatefilepath=$directory."/template.html";
	}
	else
	{
		#Geen templatefile in de root => taal ophalen en nakijken of er een taaldirectory is
		require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/parameters.php";
		$languagestring = getlanguage();
		
		#het templatepad moet dan /templates/$directory/$languagestring/template.htm of html zijn
		$templatedirectory = $directory."/".$languagestring;

		if(file_exists($templatedirectory."/template.htm"))
		{
			$templatefilepath=$directory."/".$languagestring."/template.htm";
		}
		elseif(file_exists($templatedirectory."/template.html"))
		{
			$templatefilepath=$directory."/".$languagestring."/template.htmL";
		}
		else
		{
			throw new Exception("Unable to find template.htm / template.html under $directory");
		}
	}
	
	#Op dit punt is de uitvoering gestopt wegens een exception of is de templatefile bekend.
	#Nu moet de htmlingelezen worden en teruggegeven.
	$filehandler = fopen($templatefilepath,'r',0);
	$html = fread($filehandler,filesize($templatefilepath));
	#De HTML is ingeladen en wordt teruggegeven naar het templatesysteem voor verdere verwerking.
	return $html;
}

function fileaccess_GetAddinHTML($path)
{
	#Deze functie kijkt na of het opgegeven pad bestaat. Zo ja, dan wordt de inhoud van de file teruggegeven.
	$path = $_SERVER['DOCUMENT_ROOT'].$path;
	if(file_exists($path))
	{

		#Het bestand bestaat op de server => de HTML wordt opgehaald
		$handler = fopen($path,'r');
		$html = fread($handler,filesize($path));
		
		/*GEDISABLED OMDAT DE é werd verknoeid... eventueel later andere oplossing zoeken als dit een probleem blijkt.
		//BUGFIX: vervangen van speciale karakters zonder tags te vernietigen
		// Take all the html entities
        $caracteres = get_html_translation_table(HTML_ENTITIES);
        // Find out the "tags" entities
        $remover = get_html_translation_table(HTML_SPECIALCHARS);
        // Spit out the tags entities from the original table
        $caracteres = array_diff($caracteres, $remover);
        // Translate the string....
        $html = strtr($html, $caracteres);
        // And that's it!
		*/
		
		return $html;
	}
	else
	{
		throw new Exception("You tried to open an addin at $path, that file does not exist");
	}
}
?>
