<?php

namespace OpenCloud\Common;

class Lang 
{
	
	public static function translate($word = null) 
	{
		return $word;
	}
	
	public static function noslash($str) 
	{
            //well then...
            if(is_array($str)) {
                $strAr = array();
                foreach($str as $s) {
                    while ($s && (substr($s, -1) == '/')) {
			$s = substr($s, 0, strlen($s) - 1);
                    }
                    $strAr[] = $s;
                }
                return $strAr;
            } else {
		while ($str && (substr($str, -1) == '/')) {
			$str = substr($str, 0, strlen($str) - 1);
		}
		return $str;
            }
	}
	
}
