package  {
	
	import flash.net.URLLoader;
	import flash.net.URLRequest;
	import flash.events.Event;
	import Math;
	import Slide;
	import flash.display.MovieClip;
	import flash.display.Shape;
	import flash.display.Loader;
	
	public class Slideshowclass {
		private static var fileLoader:URLLoader;
		private static var xmlFilename:String = "/templates/toverlantaarn-update1/assets/slideshow.xml";
		
		public static var SlideList:XMLList;
		public static var volgendeSlide:Slide;
		
		private static var loadCompleted:Boolean = false;

		public static function getVolgendeSlide():Slide
		{
			return volgendeSlide
		}

		public static function setVolgendeSlide(newslide:Slide):void
		{
			volgendeSlide = newslide;
		}

		public static function LoadSlideData():void
		{
			//Eerst wordt het bestand ingeladen
			var fileRequest:URLRequest = new URLRequest(xmlFilename);
			fileLoader = new URLLoader(fileRequest);
			
			//Het bestand wordt geladen en van zodra dit gebeurd is kan het parsen
			//beginnen
			fileLoader.addEventListener("complete",parseXML);

			fileLoader.load(fileRequest);
			
			}
		
		public static function LoadCompleted():Boolean
		{
			//Geeft true terug als alles ingeladen is
			return loadCompleted;
		}
		
		private static function parseXML(eventLoad:Event):void
		{
			//De XMLfile wordt geparst
			var xmlSlideshow:XML = new XML(fileLoader.data);
			
			//Er wordt een XMLList aangemaakt met alle slide-tags en hun gegevens.
			SlideList = xmlSlideshow.elements('slide');
			loadCompleted = true;
		}
		
		public static function selectSlide():Slide
		{
			//Er moet een willekeurige slide geselecteerd worden
			var max:int = SlideList.length();
			var random = Math.random();
			
			
			
			var slidenummer = Math.round(random*max);
			
			//Na afronding kan het getal gelijk zijn aan het aantal slides
			//Maar door de zerobased nummering van XMLLists zou dit een fout
			//opleveren => er wordt 1 afgetrokken. probleem is echter dat het getal ook
			//niet onder nul mag komen
			
			if(slidenummer < 0)
			{
				slidenummer = 0;
			}
			
			if(slidenummer == max)
			{
				slidenummer = slidenummer - 1;
			}
			
			//Het slidenummer heeft nu de waarde van de volgende slide. Nu kunnen we een slideobject
			//opbouwen om terug te geven naar de movie.
			var Productienaam = SlideList.elements('productie')[slidenummer].toString();
			var Jaar = SlideList.elements('jaar')[slidenummer].toString();
			var Path = SlideList.elements('foto')[slidenummer].toString();
			
			//Er wordt een slide-object opgebouwd dat dan gebruikt kan worden door de movie
			//om de verdere verwerking te doen.
			var selectedSlide:Slide = new Slide(Productienaam,Jaar,Path);
			return selectedSlide;
			}
			
	public static function showSlide(path:String,target:MovieClip)
	{
		var rect:Shape = new Shape();
		rect.graphics.beginFill(0x000000);
		rect.graphics.drawRect(0,0,757,200);
		rect.graphics.endFill();
		
		var ldr = new Loader();
		ldr.mask = rect;
		var urlReq = new URLRequest(path);
		ldr.load(urlReq);
		
		//Om een overflow te voorkomen worden alle andere children verwijderd
		var aantalelementen:int = target.numChildren;
		var i:int;
		for(i=0;i<aantalelementen;i++)
		{
			target.removeChildAt(i);
		}
		
		target.addChild(ldr);
	}
	}
}