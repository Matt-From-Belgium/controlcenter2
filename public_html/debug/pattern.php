<?php
	$subject = "SELECT languages.name from languages WHERE languages.id=@integer";
	$variable = "integer";
	$value = 2;
	$pattern= "/(?Ui)(')?@$variable(')?(,|\s|\)|$)/";
	
	#$queryafterreplacement = preg_replace_callback($pattern,"createValueString",$subject);
	$queryafterreplacement = preg_replace($pattern,"\${1}$value\${2}\${3}",$subject);
		echo "<p>query: $subject";
		echo "<br>attribute: $variable";
		echo "<br>value: $value";
		echo "<br>".$queryafterreplacement;	
		
	function createValueString($matches)
	{
		echo "value: $value";
	}
?>
