<?php

// Gramatical case
class CzechCase
{

	// Gramatical Gender
	const GENDER_MASCULINE = "M";
	const GENDER_FEMININE  = "F";
	const GENDER_NEUTER    = "N";

	private static $vowels = "aáeéiíoóuúůyý";

	private static $carons1 = Array(
		"d" => "ď",
		"n" => "ň",
		"t" => "ť",
		"D" => "Ď",
		"N" => "Ň",
		"T" => "Ť",
	);

	private static $carons2 = array_flip(static::$carons1);

	// Unicode SubString function
	public static function substr_u($str, $s, $l = null)
	{
		return join("", array_slice(preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $s, $l));
	}


	public static function Vocative($words, $gender)
	{

		$words = explode(" ", $words);

		foreach ( $words as &$s )
		{
			$k1 = self::substr_u($s, -1);
			$k2 = self::substr_u($s, -2);
			$k3 = self::substr_u($s, -3);

			$s1 = self::substr_u($s, 0, -1);
			$s2 = self::substr_u($s, 0, -2);
			$s3 = self::substr_u($s, 0, -3);

			if ( $gender == self::GENDER_MASCULINE )
			{
				// Some exceptions
				switch ( $s )
				{
					case "Karel":
					case "Pavel":
					case "Havel":
						$s = $s2."l";
				}
				switch ( $k1 )
				{
					case "a": $s = $s1 . "o";break;
					case "ď":
					case "ť":
					case "ň": $s = $s1 . $carons2[$k1] . "i";break;
					case "r": if(is_bool(strpos($vowels, self::substr_u($s, -2, 1)))) $s = $s1 . "ř";break;
				}
				switch ( $k2 )
				{
					// Exception in exception - word "švec"
					case "ec": $s = ($s == "Švec" ? "Šev" : $s2) . "če";$k1=" ";break;
					case "ek": $s = $s2 . $k1;break;
					case "ěk": if(isset($carons1[$k3{0}])) $s = $s3 . $carons1[$k3{0}] . "k";break;
				}
				if(is_int(strpos("rdtnbflmpvw", $k1))) $s .= "e";
				if(is_int(strpos("žščřcjszx",   $k1))) $s .= "i";
				if(is_int(strpos("ghk",         $k1))) $s .= "u";
			}

			if ( $gender == self::GENDER_FEMININE )
			{
				if($k1 == "a") $s = $s1 . "o";
			}
		}

		return implode(" ", $words);
	}
}
?>