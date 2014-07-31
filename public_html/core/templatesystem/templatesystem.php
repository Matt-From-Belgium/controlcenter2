<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/entity/exception.php";
require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/languages/languages.php";

###Getlanguagefiles wordt buiten de klasse gezet om ervoor te zorgen dat de common functie showMessage() zijn werk kan doen.
###uiteindelijk moeten er toch altijd taalconstanten ingeladen worden als templatesystem wordt gebruikt. Daardoor maakt dit geen verschil.
getLanguageFiles();

class htmlpage
{
	private $html;
	private $variables;
	private $bovenliggendearraystring;
        
        /*#Social integration: als enableFacebookAPI true is dan wordt de Javascript facebook-api automatisch
         *Toegevoegd aan de code. Wanneer de parameter CORE_FB_LOGIN_ENABLED 1 is dan is de standaardwaarde hier true
         *In alle andere gevallen is de standaardwaarde false maar kan deze manueel ingeschakeld worden
         */
        private $enableFacebookAPI;
        private $scripts;
        private $stylesheets;
        private $customMeta;

	
###CONSTRUCTOR FUNCTIONALITEIT
	public function __construct($alias)
	{	
			#Bij het uitvoeren van de constructor wordt een alias opgegeven. De aliases zijn in de databases gedefinieerd.
			##STAP1: Nagaan of de alias bestaat en welke directory eraan gekoppeld is.
			require_once $_SERVER['DOCUMENT_ROOT']."/core/templatesystem/templatelogic.php";
			if($directory=AliasGetLinkeddir($alias))
			{
				$directory = $_SERVER['DOCUMENT_ROOT']."/templates/".$directory;
			
				#Nu moet de HTML van de templatefile worden opgehaald.
				$this->html=gettemplatehtml($directory);
                                
                                              

				/*#De taalconstanten worden opgehaald				
				$this->LoadLanguageFiles();	
				*/
                                
                                /*De alias is verwerkt, nu moeten we kijken welke standaardwaarde $this->enableFacebookAPI moet krijgen*/
                                 if(getFacebookLoginStatus())
                                 {
                                     $this->enableFacebookAPI=true;
                                 }
                                 else
                                 {
                                     $this->enableFacebookAPI=false;
                                 }
			}
			else
			{
				throw new Exception("You tried to call an alias '$alias'. The request failed because the alias does not exist");
			}
	}
	
	private function LoadLanguageFiles()
	{
		require_once $_SERVER['DOCUMENT_ROOT']."/core/logic/languages/languages.php";
		getLanguageFiles();
	}
	
#PARSING
	private function ParseTags($html)
	{
		#Deze functie staat in voor de verwerking van de Controlcenter2 Custom tags
		
		#FIRST PARSE: Opsporen van Addins en invoegen
		$pattern = "/(?ims)\<\s*!CC\s*addin\s*'(.+)'\>/";
		
		$hits=0;
		
		do
		{
			$newhtml = @preg_replace_callback($pattern,array($this,'parseAddin'),$html);
			
			if($newhtml == $html)
			{
				$hits = 0;
			}
			else
			{
				$hits = 1;
				$html = $newhtml;
			}
		}
		while($hits>0);
		
		#SECOND PARSE: opsporen van tags die uit een begin en eindtag bestaan.
		#Hier wordt gebruik gemaakt van een lus om recursieve tags toe te laten. De patroonzoeker
		#blijft analyseren tot er geen hits meer zijn op het patroon. 
		###TEMPLATESYSTEM R1: Er worden geen punten meer toegelaten in de parameter.
		###Patroon wordt ungreedy!!!
		$pattern = "/(?imsU)\<!CC\s*(\S+)\s*\[([^.\[\]]*)\]\s*\>(.*)\<!CC\s*END\s*\\1\s*\[\\2\]\s*\>/";
		
		$hits =0;
		
		do
		{
			$newhtml = @preg_replace_callback($pattern,array($this,'SecondParse'),$html);
			
			#De lus wordt uitgevoerd zolang er patronen zijn. Wanneer er een parsing gebeurt die geen
			#veranderingen meer oplevert wordt er gestopt. $hits wordt gebruikt als flag.
			if($newhtml == $html)
			{
				$hits = 0;
			}
			else
			{
				$hits = 1;
				$html = $newhtml;
			}
		}while($hits>0);
		
		
		#THIRD PARSE: opsporen van de tags die slechts uit ��n deel bestaan.
		$pattern = "/(?ims)\<!CC\s*(\S+)\s*\[([^\[\]]*)\]\s*\>/";
		
		$hits = 0;
		
		do
		{
			$newhtml = @preg_replace_callback($pattern,array($this,'ThirdParse'),$html);
			#De lus wordt uitgevoerd zolang er patronen zijn. Wanneer er een parsing gebeurt die geen
			#veranderingen meer oplevert wordt er gestopt. $hits wordt gebruikt als flag.
			if($newhtml == $html)
			{
				$hits = 0;
			}
			else
			{
				$hits = 1;
				$html = $newhtml;
			}
		}while($hits>0);
		
                /*
                ###SCRIPTS
                ###We voegen de scripts in die specifiek voor deze pagina geladen moeten worden
                if(is_array($this->scripts))
                {
                $patternhead = "/(?i)<\s*head\s*>/";
                $html =  @preg_replace_callback($patternhead,array($this,'addScripts'), $html, 1);
                }
                
                ###META
                ###We voegen nu nog de meta gegevens toe op basis van wat in de databank zit
                $patternhead = "/(?i)<\s*head\s*>/";
                $html =  @preg_replace_callback($patternhead,array($this,'addMetaData'), $html, 1);
                 *                  */

               ###Is de gebruiker op de hoogte van het gebruik van cookies? Zo niet moet melding getoond worden
               ###Aangezien hier potentieel een javascript geladen wordt moet deze boven de aanroeping van appendheadtag blijven 
                if(!isset($_COOKIE['cookies']))
                {
                    ###We laden de nodige javascripts
                    $this->loadScript('/core/templatesystem/setcookies.js');
                    ###BUGFIX: als cookies melding getoond wordt moet ajaxtransaction ook geladen worden
                    $this->enableAjax();
                    
                    ###Er zijn 2 mogelijkheden om de cookies notificatie weer te geven
                    #Als er een een tag <!CC cookies> is opgenomen wordt die tag vervangen door de notificatie
                    #Als die er niet is wordt de notificatie ingevoegd na de head tag. Normaal zal het laatste systeem 
                    #voor de meeste gevallen werken maar in geval van fixed layers kan het nodig zijn om
                    #die custom tag te gebruiken.
                    $patterncookiescustom = "/(?i)<\s*!CC\s*cookies\s*>/";
                    
                    ###We kijken eerst of de custom tag werd gebruikt
                    if(preg_match($patterncookiescustom, $html)==1)
                    {
                        ###Er is een match
                        $html=@preg_replace_callback($patterncookiescustom,array($this,'addCookiesNotification'), $html, 1);
                    }
                    else
                    {
                    $patternbody = "/(?i)<\s*body\s*[a-z0-9=\"\']*\s*>/";
                    $html =  @preg_replace_callback($patternbody,array($this,'addCookiesNotification'), $html, 1);
                    
                    
                    }
                }
		
		
                
                
                ###We vullen de head tag aan met javascripts en metadata
                $patternhead = "/(?i)<\s*\/\s*head\s*>/";
                $html = @preg_replace_callback($patternhead,array($this,'appendHeadTag'), $html, 1);
                
                
                ###Facebook integratie: Nu de HTML compleet is voegen we indien nodig de Facebook Javascript api toe
                ###We doen dat net na de body tag. Maar... enkel wanneer $this->enableFacebookAPI = true (zie commentaar bovenaan)
                if($this->enableFacebookAPI)
                {
                    ###bugfix: rekening houden met mogelijke onLoad
                    $patternbody = "/(?i)<\s*body\s*[a-z0-9=\"\']*\s*>/";
                    $html =  @preg_replace_callback($patternbody,array($this,'addFacebookAPI'), $html, 1);
                }
                
                return $html;
                 
                 
	}
	
	private function SecondParse($matches)
	{
		#Deze functie wordt gebruikt voor alle tags die gematcht worden bij de 2e parse.
		#Op dit moment gaat het enkel om de <!CC LOOP [array]><!CC END LOOP> constructie
		#Er wordt hier echter een aparte functie voorzien zodat er later nog extra tags kunnen worden
		#toegevoegd.
		
		#$matches wordt aangeleverd
		#$matches[0] bevat de volledige matching
		#$matches[1] bevat de naam van de tag
		#$matches[2] bevat de parameter
		#$matches[3] bevat de code tussen de tags
		
		$tagnaam = strtolower($matches[1]);
		
		switch($tagnaam)
		{
			case "loop":
			return $this->parseLoopR1($matches[2],$matches[3]);
			break;
			
			case "ifset":
			return $this->parseifset($matches[2],$matches[3]);
			break;
			
			default:
			throw new Exception("The custom tag $matches[1] does not exist (Second Parse)");
		}
	}
	
	private function ThirdParse($matches)
	{
		#Deze functie wordt gebruikt voor de derde parse.
		#Er wordt gezocht naar custom tags die uit 1 deel bestaan
		
		#$matches wordt aangeleverd
		#$matches[0] bevat de tag
		#$matches[1] bevat de naam van de tag
		#$matches[2] bevat de parameter
		
		$tagnaam = strtolower($matches[1]);
		
		switch($tagnaam)
			{
				case "var":
				return $this->parseVariable($matches[2]);
				break;
				
				case "lang":
				return $this->parseLanguageConstant($matches[2]);
				
				return $matches[0];
				
				default:
				throw new Exception("The custom tag $matches[1] does not exist (Third parse)");
			}
	}
        
        private function appendHeadTag($matches)
        {
            
            
            ###javascripts toevoegen
            if(is_array($this->scripts))
            {
                foreach($this->scripts as $value)
                {
                    $newhtml = "<script src='$value' ></script>".PHP_EOL;
                    $html = $html.$newhtml.PHP_EOL;
                }
            }
            
            ###META data
                if(!is_array($this->customMeta))
                {
                     $metadata = getSiteMeta();
                     $fbappid = getFacebookAppID();


                     ###We creëren eerst de gewone meta tags
                     $metahtml[] = "<meta name=description content='$metadata[description]' />";

                     ###nu de Facebook meta
                     $metahtml[] = "<meta property='fb:app_id' content=\"$fbappid\" />";
                     $metahtml[] = "<meta property='og:type' content=\"website\" />";
                     $metahtml[] = "<meta property='og:url'  content=\"$metadata[url]\"/>";
                     $metahtml[] = "<meta property='og:title' content=\"$metadata[title]\" />";
                     $metahtml[] = "<meta property='og:description' content=\"$metadata[description]\" />";
                     $metahtml[] = "<meta property='og:image' content=\"$metadata[image]\" />";



                 foreach($metahtml as $value)
                 {
                     $html = $html.$value.PHP_EOL;
                 }
                }
                else
                {
                    ###CustomMeta heeft waarde => we genereren de meta op basis daarvan                   

                    foreach($this->customMeta as $value)
                    {
                        $newmeta= "<meta property='$value[property]' content=\"$value[content]\"/> ";
                        $html = $html.$newmeta . PHP_EOL;

                    }
                }
                
            ###CSS
                if(is_array($this->stylesheets))
                {
                    ###Er zijn scripts om bij te voegen
                    foreach($this->stylesheets as $value)
                    {
                        $newcode = "<link href='$value' rel='stylesheet' type='text/css'>";
                        $html = $html . $newcode . PHP_EOL;
                    }
                }
            
            ###We plaatsen de eindtag terug
            $html = $html.$matches[0];
                
            return $html;
        }
        
        private function addCookiesNotification($matches)
        {
            
            
            ###Body tag moet natuurlijk behouden blijven
            $html = $matches[0];
            $html = $html.'<div id="cookies">';
            $html = $html.'<div>'.LANG_COOKIES_NOTIFICATION;
            $html = $html.'<input type="button" value="'.LANG_COOKIES_SEND_BUTTON.'" onclick="javascript:setCookiesOk()">';
            $html = $html.'</div></div>';
            
            
            
            return $html;
            
        }
        
        private function addFacebookAPI($matches)
        {
            require_once $_SERVER['DOCUMENT_ROOT'].'/core/logic/parameters.php';
            
            ###Body tag moet natuurlijk behouden blijven
            $html = $matches[0];
            $fbjscode = getFacebookJavaCode();
            
            $html = $html.$fbjscode;
            
            return $html;
            
        }
        
        
#CUSTOM TAG PARSE FUNCTIES
	private function parseAddin($matches)
	{
		#het pad is het eerste en enige subpattern in de regex => opgeslagen in $matches[1]
		$path = $matches[1];
		
		return $this->getAddin($path);
	}
	
	private function getAddin($path)
	{
		#Eerst controleren of het pad de extensie .tpa heeft
		$padlengte = strlen($path);
		
		$extensie = substr($path,-4);
		
		#Enkel .tpa extensie is toegelaten
		if(strtolower($extensie) == ".tpa")
		{
			$addinhtml=getAddinHTML($path);
			return $addinhtml;
		}
		else
		{
			throw new Exception("Addins must have the extension .tpa");
		}
	}
	
	private function parseVariable($varname)
	{
		if(!empty($this->variables[$varname]))
		{
		return $this->variables[$varname];
		}
		else
		{
			return "";
		}
	}
	
	private function parseLanguageConstant($constant)
	{
		if(defined($constant))
		{
			###De taalconstante is gedefinieerd => teruggeven
			return constant($constant);
		}
		else
		{
			#De taalconstante is niet gedefinieerd => exception
			throw new Exception("You tried to implement a language constant $constant wich is not defined. Check your languagefiles");
		}
	}
	
	private function preparseLoop($matches)
	{
		####TEMPLATESYSTEM R1	
		###Deze functie moet ervoor zorgen dat ParseLoop recursief kan worden uitgevoerd. Aangezien er gezocht wordt op een patroon
		###Het is zo dat er voor het detecteren van het patroon en het uitvoeren van de code gebruik gemaakt wordt van de functie preg_replace_callback
		###Probleem is dat de functie die aangeroepen wordt automatisch 1 argument meekrijgt, en dat is niet compatibel met de structuur van parseLoop
		###Deze functie is bedoeld om dit probleem op te lossen. $matches wordt ontleed zodat ParseLoop kan worden aangeroepen.
		
		#$matches wordt aangeleverd
		#$matches[0] bevat de volledige matching
		#$matches[1] bevat de parameter
		#$matches[2] bevat de code tussen de tags
		echo "PREPARSE GEACTIVEERD!!!";
		return $this->parseLoop($matches[1],$matches[2]);
	}	
	
	private function parseNestedLoops($matches)
	{
		###Deze functie moet het parsen van geneste loops mogelijk maken. Probleem is dat de geneste loops door een patroonherkenner worden
		#gedetecteerd en de verwerking kan enkel via een callback functie gebeuren met 1 enkel argument.
		#$matches bevat volgende items
		# $matches[0] = volledige matching
		# $matches[1] = arrayname
		# $matches[2] = code tussen de tags.
		return $this->parseLoopR1($matches[1],$matches[2],$this->bovenliggendearraystring);
	}
	
	private function parseLoopR1($arrayname,$code,$bovenliggendearraystring)
	{
		$arrayname = $arrayname;
		###TEMPLATESYSTEM R1
		#De functie ParseLoop is er voor het verwerken van de lussen
		#Om ervoor te zorgen dat er onbeperkt recursieve lussen kunnen verwerkt worden wordt
		#er binnen de functie verwezen naar de functie zelf. Belangrijk om op te merken is dus dat de code onder deze
		#functie voor ieder mogelijk niveau moet kloppen.
		
		#Wanneer deze functie wordt uitgevoerd op het basisniveau dan heeft $bovenliggendearraystring geen waarde.
		#Ook wanneer $arrayname een punt bevat geeft dit het signaal dat het hier om een geneste lus gaat. Het is wel zo
		#dat het mogelijk is dat 2 lussen genest zijn en dat ze geen direct verband hebben. In dat geval moeten ze ook los
		#van elkaar kunnen behandeld worden en moeten beide lussen verwerkt worden alsof ze niks met elkaar te maken hebben
		#Om die reden zal de aanwezigheid van een punt in $arrayname als flag dienen.
		$arraynamedelen = split("\.",$arrayname);
		
		if(count($arraynamedelen)>1)
		{
			###Het gaat om een geneste lus
			#We moeten nu dus de array zoeken die de lus moet gaan voeden
			#Die array bekomen we door bovenliggendestring[$arraynamedelen(count($arraynamedelen)-1)] 
			#op te halen.
			$looparraystring = '$'.$bovenliggendearraystring."['".$arraynamedelen[count($arraynamedelen)-1]."']";
			eval("\$looparray = $looparraystring;");
		}
		else
		{
		###De lus moet niet als een geneste lus worden behandeld.
		###De waarde waar in de lus wordt verwezen wordt hier aan de variabele looparray toegewezen.
		###Deze variabele zal de lus voeden.
		$looparray = $this->variables[$arrayname];
		
		###Er wordt een tweedevariabele gedeclareerd met daarin letterlijk de naam van de arrayvariabele. 
		###De reden hiervoor is dat de tags zullen vervangen worden door de letterlijke variabelenaam. Op het einde van de 
		###uitvoering worden alle variabelen ge�valueerd.
		$looparraystring ="this->variables['$arrayname']";
		}
		
		if(!empty($looparray))
		{
			#De waarde waar naar wordt verwezen in de code heeft in ieder geval een waarde. De vraag is of dit de juiste waarde is.
			#Hier wordt gecontroleerd of het wel degelijk gaat om een array.
			if(is_array($looparray))
			{
				#De aangeleverde waarde is gedefinieerd en is ook een array => De eigenlijk verwerking van de lus kan beginnen.
				foreach($looparray as $key=>$value)
				{
					###Binnen de iteratie is het zo dat er niet geraakt mag worden aan de $code. De custom tags worden verwerkt en het 
					###het resultaat wordt doorgegeven aan de variabele $iteratieresultaat. Op die manier kan $code opnieuw worden gebruikt voor
					###de volgende iteratie.
					$iteratieresultaat = $code;
					###Geneste lussen zoeken. Deze moeten eerst verwerkt worden <= recursief
					$pattern = "/(?imsU)\<!CC\s*LOOP\s*\[([^\[\]]*)\]\s*\>(.*)\<!CC\s*END\s*LOOP\s*\[\\1\]\s*\>/";
					###De functie moet op dit punt zichzelf terug aanroepen zodat een oneindige recursiviteit kan worden gecre�erd.
					###Om de bovenliggende array te kunnen doorgeven aan zichzelf moet looparraystring hier aangepast worden met de huidige index
					###binnen de loop
					$this->bovenliggendearraystring = $looparraystring."['".$key."']";
					$iteratieresultaat = preg_replace_callback($pattern,array($this,'parseNestedLoops'),$iteratieresultaat);
					
					###Er wordt gezocht naar variabelen binnen deze lus die moeten ingevuld worden
					$pattern = "/(?ims)\<\s*!CC\s*LVAR\s*\[([^\[\]]*)\]\s*\>/";

					###Het deel van de array dat gebruikt moet worden voor deze iteratie wordt toegewezen aan $currentrecord.
					$currentrecord = $value;

					###De LVAR tag wordt vervangen door de variabele die de overeenstemmende waarde bevat.
					###Het dollarteken wordt letterlijk toegevoegd zodat nadien met behulp van de functie eval() alle
					###variabelen kunnen worden vervangen door hun waarde.
					$iteratieresultaat = preg_replace($pattern,"\$currentrecord[$1]",$iteratieresultaat);
					
					eval("\$coderesult .= \"$iteratieresultaat\";");
					

				}
				
				return $coderesult;
			}
			else
			{
				###De aangeleverde waarde is geen array => Exception
				throw new Exception("You tried to execute a loop $arrayname but that variable is not an array");
			}
		}
		else
		{
			#De aangeleverde arraynaam is leeg => er wordt geen exception gegenereerd maar onderstaande waarschuwing wordt
			#afgedrukt in de HTML-code
			return "<!--LOOP not executed, $arrayname was empty-->";			
		}
	}
	
	private function parseLoop($arrayname,$code)
	{
		###DEBUG
		echo "ParseLoop() gestart met array $arrayname en code $code";
		
		###TEMPLATESYSTEM R1
			###Deze functie wordt gebruikt voor parent en child lussen 
		
			###Eerst moet er nagegaan worden op welk nivea we zitten en welke de eventuele parentlussen zijn
			$arraynameparts = split("\.",$arrayname);
			
			###Wanneer we de verschillende delen van de arraynaam ontleed hebben is het mogelijk van de array die de lus moet
			###voeden op te sporen.
			
			###Ieder deel van arraynameparts gaat een dimensie dieper. We doen dit door een lus.
			###Wanneer het om een lus gaat die geen parent heeft dan zal de for lus niet uitgevoerd worden.
			$actievearray = $this->variables[$arraynameparts[0]];
			
			for($i=1;$i<count($arraynameparts);$i++)
			{
				$actievearray = $actievearray[$arraynameparts[i]];
			}
			
			echo "de inhoud van de actieve array is:";
			print_r($actievearray);
		
		
		#Deze functie genereert de HTML die de LOOP-constructie vervangt.
		if(!empty($actievearray))
		{
			if(is_array($actievearray))
			{				
				###TEMPLATESYSTEM R1
				#Vooraleer er begonnen kan worden met het vervangen van LVAR moet er nagekeken worden of er geneste loops
				#zijn. Als dat het geval is moeten deze eerste verwerkt worden voor er vervangingen gebeuren. Anders zal er
				#immers een probleem optreden dat LVAR tags zullen verwerkt worden in de verkeerde lus.
				$pattern = "/(?imsU)\<!CC\s*LOOP\s*\[([^\[\]]*)\]\s*\>(.*)\<!CC\s*END\s*LOOP\s*\[\\1\]\s*\>/";
				$code = preg_replace_callback($pattern,array($this,'preparseLoop'),$code);
				
				#Binnen de LOOP-tags komen er normaal variabelen voor die verwijzen naar items in de array
				#deze moeten opgezocht worden en vervangen
				$pattern = "/(?ims)\<\s*!CC\s*LVAR\s*\[([^\[\]]*)\]\s*\>/";
				$replacementarray = $actievearray;
			
				#De <!CC LVAR structuur wordt vervangen door een letterlijke $variabele
				$code = preg_replace($pattern,"\$data[$1]",$code);
			
				foreach($actievearray as $key=>$value)
				{
					$data = $actievearray[$key];				
					#Het '' karakter van $code wordt vervangen door "" => de variabelen worden toegewezen
					#in de preg_replace hierboven werd reeds verwezn naar de $date array die nu per iteratie
					#wordt gedefinieerd met de waarden die voor die iteratie moeten worden gebruikt.
					eval("\$coderesult .= \"$code\";");
				}
				reset($actievearray);
				
				###DEBUG 
				echo "het resultaat van de verwerking van loop $arrayname is $coderesult";
				
				return $coderesult;
			}
			else
			{
				#LOOPS eisen een array als parameter
				throw new Exception("You tried to execute a loop with variable $arrayname, but that is not an array");
			}
		}
		else
		{
			return "<!--LOOP not executed, variable $arrayname not set-->";
		}
		
	}
	
	private function parseIfSet($varname,$code)
	{
		#De code tussen de ifset constructie wordt pas afgedrukt als de variabele in de parameter een waarde heeft.
		if(!empty($this->variables[$varname]))
		{
			return $code;
		}
		else
		{
			return false;
		}
	}
	
#PUBLIEKE FUNCTIES
	public function setVariable($varname,$value)
	{
		#Deze functie definieert een variabele binnen de instantie.
		$this->variables[$varname]=$value;
	}
	
	public function LoadAddin($pathtoaddin)	
	{
		#Deze functie zorgt ervoor dat een gebruiker vanuit het aanroepende script een addin kan
		#invoegen op de plaats van PageContent
		$pattern = "/(?ims)\<\s*pagecontent\s*\>/";
		$addinhtml = $this->getAddin($pathtoaddin);
		$addinhtml = $addinhtml."\n<PageContent>\n";
		
		$this->html = preg_replace($pattern,$addinhtml,$this->html);
	}
	
	public function insertCode($codestring)
	{
		###Templatesystem R2
		###Deze functie voegt de HTML-code in $codestring in op de plaats van <PageContent>
		$pattern = "/(?ims)\<\s*pagecontent\s*\>/";
		$codetoinsert = $codestring . "\n<PageContent>\n";
		$this->html = preg_replace($pattern,$codetoinsert,$this->html);
	}
        
        public function setFacebookIntegration($value)
        {
            if(is_bool($value))
            {
                ###Enkel booleans worden aanvaard
                $this->enableFacebookAPI = $value;
            }
            else
            {
                throw new Exception('setFacebookIntegration aanvaardt enkel een boolean als argument');
            }
        }
        
        public function addCustomMeta($property,$content)
        {
            ###Hiermee kunnen we zelf meta-tags toekennen aan de pagina
            ###Van zodra deze functie gebruikt wordt zal de standaard meta niet meer gegenereerd worden
            $newmeta['property']=$property;
            $newmeta['content']= $content;
            
            $this->customMeta[] = $newmeta;
        }
        
        public function loadScript($location)
        {
            ###Hiermee kunnen javascripts per pagina geladen worden

                $this->scripts[] = $location;
            
        }
        
        public function loadCSS($pc,$phone=NULL,$tablet=NULL)
        {
            ###TEMPLATESYSTEM R3: je kan kiezen welk CSS script je wil laten
            #op basis van het platform waarmee de pagina geopend wordt
            #- enkel de locatie van het pc script moet opgegeven worden in dat geval
            #wordt het gebruikt voor alle platformen
            #-als phone is opgegeven wordt dat gebruikt voor telefoons en tablets tenzij tablet
            #ook gedefinieerd is
            $detection = new Mobile_Detect();
            $platform = "pc";
            
            if($detection->isTablet())
            {
                $platform = 'tablet';
            }
            else
            {
                if($detection->isMobile())
                {
                    $platform = 'phone';
                }
            }
            
            if(empty($phone))
            {
                $phone= $pc;
            }
            if(empty($tablet))
            {
                $tablet=$phone;
            }
            
            ###Hiermee kunnen CSS scripts per pagina geladen worden
            $this->stylesheets[]= $$platform;
        }
        
        ###Templatesystem R3: enableAjax laadt automatisch ajaxtransaction.js
        #Je zou dit ook met loadScript kunnen doen. Het verschil is hier dat bij debug het niet-geobfusceerde script
        #geladen wordt. In de andere gevallen wordt de versleutelde versie geladen
        public function enableAjax()
        {
            if(getDebugMode())
            {
                $path = '/core/presentation/ajax/ajaxtransaction.code.js';
            }
            else
            {
                $path = '/core/presentation/ajax/ajaxtransaction.js';
            }
            
            $this->loadScript($path);
        }
	
	public function PrintHTML()
	{
		###Deze functie geeft de HTML terug aan de browser

            
		$this->html = $this->ParseTags($this->html);
		
		echo $this->html;
	}
	
	public function getHTML()
	{
		###Templatesystem R2
		###Deze functie geeft de HTML terug in een string zodat ze door andere functies
		###gebruikt kan worden
		$this->html = $this->ParseTags($this->html);
		return $this->html;
	}
}
?>