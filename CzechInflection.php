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

	private static $carons2 = Array (
		'ď' => 'd',
		'ň' => 'n',
		'ť' => 't',
		'Ď' => 'D',
		'Ň' => 'N',
		'Ť' => 'T',
	);

	// Unicode SubString function
	private static function substr_u($str, $s, $l = null)
	{
		return join("", array_slice(preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $s, $l));
	}


	public static function Genitive($words, $gender)
	{
		$words = explode(" ", $words);

		foreach ($words as &$s)
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
				switch ($s)
				{
					case "Karel":
					case "Pavel":
					case "Havel":

					case "Datel":
					case "Osel":
					case "Mazel":
					case "Menzel":
						$s = $s2."l";
				}
				switch ($k1)
				{
					case "a": $s = $s1 . "y";break;
					case "ý": $s = $s1 . "ého";break;
					case "í": $s = $s1 . "ího";break;
					case "ď":
					case "ť":
					case "ň": $s = $s1 . self::$carons2[$k1] . "i";break;
				}
				switch ($k2)
				{
					// Exception in exception - word "švec"
					case "ec": $s = ($s == "Švec" ? "Šev" : $s2) . $k1;break;
					case "ek": $s = $s2 . $k1;break;
					case "ěk": if(isset(self::$carons1[$k3{0}])) $s = $s3 . self::$carons1[$k3{0}] . "k";break;
				}

				if(is_int(strpos("hkrdtngbflmpvw", $k1))) $s .= "a";
				if(is_int(strpos("žščřcjszx",      $k1))) $s .= "e";

			}

			if ( $gender == self::GENDER_FEMININE )
			{
				if($k1 == "a")
				{
					switch ($k2)
					{
						case "ďa": $s = $s2 . "di";break;
						case "ňa": $s = $s2 . "ni";break;
						case "ťa": $s = $s2 . "ti";break;
					}

					if(is_int(strpos("bdfkmnptvw", $k2{0}))) $s = $s1."y";
					if(is_int(strpos("cčjlřsšxzž", $k2{0}))) $s = $s1."i";
				}

				if($k1 == "á") $s = $s1 . "é";
			}
		}

		return implode(" ", $words);
	}


	public static function Dative($words, $gender)
	{
		$words = explode(" ", $words);

		foreach ($words as $i=>&$s)
		{
			$k1 = substr_u($s, -1);
			$k2 = substr_u($s, -2);
			$k3 = substr_u($s, -3);

			$s1 = substr_u($s, 0, -1);
			$s2 = substr_u($s, 0, -2);
			$s3 = substr_u($s, 0, -3);

			if ( $gender == self::GENDER_MASCULINE )
			{
				switch ($s)
				{
					case "Karel":
					case "Pavel":
					case "Havel":

					case "Datel":
					case "Osel":
					case "Mazel":
					case "Menzel":
						$s = $s2."l";
				}
				switch ($k1)
				{
					case "a": $s = $s1;break;
					case "ý": $s = $s1 . "ému";break;
					case "í": $s = $s1 . "ímu";break;
					case "ď":
					case "ť":
					case "ň": $s = $s1 . self::$carons2[$k1] . "i";break;
				}
				switch ($k2)
				{
					// Exception in exception - word "švec"
					case "ec": $s = ($s == "Švec" ? "Šev" : $s2) . $k1;break;
					case "ek": $s = $s2 . $k1;break;
					case "ěk": if(isset(self::$carons1[$k3{0}])) $s = $s3 . self::$carons1[$k3{0}] . "k";break;
				}

				if($i < count($words)-1)
				{
					if(is_int(strpos("hkrdtngbflmpvw", $k1))) $s .= "u";
					if(is_int(strpos("žščřcjszx",      $k1))) $s .= "i";
					if(is_int(strpos("aáeéioóuúůy",    $k1))) $s .= "ovi";
				}
				else
				{
					$s .= "ovi";
				}

			}

			if ( $gender == self::GENDER_FEMININE )
			{
				if($k1 == "a")
				{
					switch ($k2)
					{
						case "ga": $s = $s2 . "ze";break;
						case "ha": $s = $s2 . "ze";break;
						case "ka": $s = $s2 . "ce";break;
						case "ra": $s = $s2 . "ře";break;

						case "ďa": $s = $s2 . "dě";break;
						case "ňa": $s = $s2 . "ně";break;
						case "ťa": $s = $s2 . "tě";break;
					}

					if(is_int(strpos("dtnbfmpvw",  $k2{0}))) $s = $s1."ě";
					if(is_int(strpos("žščřcjlszx", $k2{0}))) $s = $s1."e";
				}

				if($k1 == "e") $s = $s1 . "i";

				if($k1 == "á") $s = $s1 . "é";
			}
		}

		return implode(" ", $words);
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

					case "Datel":
					case "Osel":
					case "Mazel":
					case "Menzel":
						$s = $s2."l";
				}
				switch ( $k1 )
				{
					case "a": $s = $s1 . "o";break;
					case "ď":
					case "ť":
					case "ň": $s = $s1 . $carons2[$k1] . "i";break;
					case "r": if(is_bool(strpos(self::$vowels, self::substr_u($s, -2, 1)))) $s = $s1 . "ř";break;
				}
				switch ( $k2 )
				{
					// Exception in exception - word "švec"
					case "ec": $s = ($s == "Švec" ? "Šev" : $s2) . "če";$k1=" ";break;
					case "ek": $s = $s2 . $k1;break;
					case "ěk": if(isset(self::$carons1[$k3{0}])) $s = $s3 . self::$carons1[$k3{0}] . "k";break;
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