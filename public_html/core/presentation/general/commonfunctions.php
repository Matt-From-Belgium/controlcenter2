<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/core/templatesystem/templatesystem.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/entity/exception.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/languages/languages.php';

function showMessage($title,$body,$link="",$linktext="",$alias='frontend')
{
	###Deze functie geeft aan de eindgebruiker een HTMLpagina weer met een simpele layout waarin de titel, bericht en de link voorkomen.
	###$title en $body zijn verplicht, de laatste 2 argumenten niet, het is echter wel zo dat als $link een waarde heeft dat $linktext die ook moet hebben
	###en omgekeerd

	
	if(empty($title) || empty($body))
	{
		throw new CC2Exception('An error has occured in the templatesystem','when using showMessage you must supply both a $title and a $body');		
	}
	else
	{
		###$title en $body hebben een waarde => de waarden van $link en $linktext controleren
		if(empty($link) || empty($linktext))
		{
			###Minstens één van beide waarden is leeg, ofwel zijn de beiden leeg en dan is er geen probleem maar moet er geen link
			###getoond worden. Als slechts 1 van beiden leeg is moet er een exception gegeven worden.
			if(empty($link) and empty($linktext))
			{
				###er moet geen link worden weergegeven
			}
			else
			{
				###EXCEPTION: 1 van beide waarden is ingevuld
				throw new CC2Exception('An error has occured in the templatesystem','when using showMessage() you must leave $link and $linktext blank or supply both values');
			}
		}
		
		$html = new htmlpage($alias);
		$html->LoadAddin("/core/presentation/general/addins/message.tpa");
		$html->setVariable("messagetitle",$title);
		$html->setVariable("message",$body);
		$html->setVariable("link",$link);
		$html->setVariable("linktext",$linktext);
		$html->PrintHTML();
	}
}
?>