package  {
	
	public class Slide {
		
		//Declaratie van privates
		public var _slidepath
		public var _productie
		public var _jaar
		
		public function Slide(productie:String,jaar:int,path:String)
		{
			this._slidepath = path;
			this._jaar = jaar;
			this._productie = productie;
		}
		
		public function Productie():String
		{
			return this._productie;
		}
		
		public function Jaar():String
		{
			return this._jaar;
		}
		
		public function Pad():String
		{
			return this._slidepath;
		}
	}
	
}