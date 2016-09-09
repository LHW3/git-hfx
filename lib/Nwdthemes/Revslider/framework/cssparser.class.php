<?php

// Overrides original css parser class

require_once Mage::getBaseDir('lib') . '/Nwdthemes/Revslider/original/framework/cssparser.class.php';

class UniteCssParserRev extends UniteCssParserRevOriginal {


	public static function parseDbArrayToCss($cssArray, $nl = "\n\r"){
		$css = '';
		foreach($cssArray as $id => $attr){
		    $stripped = '';
		    if(strpos($attr['handle'], '.tp-caption') !== false){
			    $stripped = trim(str_replace('.tp-caption', '', $attr['handle']));
		    }
			$styles = self::_jsonDecodeReplace($attr['params'], true);
    		$css.= $attr['handle'];
	    	if(!empty($stripped)) $css.= ', '.$stripped;
		    $css.= " {".$nl;
			if(is_array($styles)){
				foreach($styles as $name => $style){
					$css.= $name.':'.$style.";".$nl;
				}
			}
			$css.= "}".$nl.$nl;

			//add hover
			$setting = json_decode($attr['settings'], true);
			if(@$setting['hover'] == 'true'){
				$hover = json_decode(str_replace("'", '"', $attr['hover']), true);
				if(is_array($hover)){
                    $css.= $attr['handle'].":hover";
                    if(!empty($stripped)) $css.= ', '.$stripped.':hover';
                    $css.= " {".$nl;
					foreach($hover as $name => $style){
						$css.= $name.':'.$style.";".$nl;
					}
					$css.= "}".$nl.$nl;
				}
			}
		}
		return $css;
	}

		public static function parseDbArrayToArray($cssArray, $handle = false){

			if(!is_array($cssArray) || empty($cssArray)) return false;

			foreach($cssArray as $key => $css){
				if($handle != false){
					if($cssArray[$key]['handle'] == '.tp-caption.'.$handle){
						$cssArray[$key]['params'] = self::_jsonDecodeReplace($css['params']);
						$cssArray[$key]['hover'] = self::_jsonDecodeReplace($css['hover']);
						$cssArray[$key]['settings'] = self::_jsonDecodeReplace($css['settings']);
						return $cssArray[$key];
					}else{
						unset($cssArray[$key]);
					}
				}else{
					$cssArray[$key]['params'] = self::_jsonDecodeReplace($css['params']);
					$cssArray[$key]['hover'] = self::_jsonDecodeReplace($css['hover']);
					$cssArray[$key]['settings'] = self::_jsonDecodeReplace($css['settings']);
				}
			}

			return $cssArray;
		}
		
		/**
		 * Convert db css string to array
		 * tries old way first, if failed uses mew function
		 *
		 * @param string $jsonCss
		 * @param boolean $toArray Force decode to array
		 * @return array
		 */ 
		
		private static function _jsonDecodeReplace($jsonCss = '', $toArray = false) {
			$arrCss = json_decode(str_replace("'", '"', $jsonCss), $toArray);
			if (is_null($arrCss) && ! is_null($jsonCss))
			{
				$arrCss = Mage::helper('nwdrevslider')->stringCssToArray($jsonCss);
			}
			return $arrCss;
		}

		public static function parseCssToArray($css){

			while(strpos($css, '/*') !== false){
				if(strpos($css, '*/') === false) return false;
				$start = strpos($css, '/*');
				$end = strpos($css, '*/') + 2;
				$css = str_replace(substr($css, $start, $end - $start), '', $css);
			}

			preg_match_all( '/(?ims)([a-z0-9\s\.\:#_\-@]+)\{([^\}]*)\}/', $css, $arr);

			$result = array();
			foreach ($arr[0] as $i => $x){
				$selector = trim($arr[1][$i]);
				if (strpos($css, '.tp-caption' . $selector . ', ' . $selector) !== false)
				{
					$selector = '.tp-caption' . $selector;
				}
				if(strpos($selector, '{') !== false || strpos($selector, '}') !== false) return false;
				$rules = explode(';', trim($arr[2][$i]));
				$result[$selector] = array();
				foreach ($rules as $strRule){
					if (!empty($strRule)){
						$rule = explode(":", $strRule);
						if(strpos($rule[0], '{') !== false || strpos($rule[0], '}') !== false || strpos($rule[1], '{') !== false || strpos($rule[1], '}') !== false) return false;

						//put back everything but not $rule[0];
						$key = trim($rule[0]);
						unset($rule[0]);
						$values = implode(':', $rule);

						$result[$selector][trim($key)] = trim(str_replace("'", '"', $values));
					}
				}
			}
			return($result);
		}
	
}
