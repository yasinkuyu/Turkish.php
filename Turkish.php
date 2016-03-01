<?php

class Turkish { 

/*
 * Yasin Kuyu
 * twitter/yasinkuyu
 * 01/08/2014
 *
 * Turkish Suffix Library for PHP
 * Türkçe Çekim ve Yapım Ekleri
 *
 */

    public $VOWELS = "aıoöuüei";
    public $FRONT_VOWELS = "aıou";
    public $BACK_VOWELS = "eiöü";
    public $HARD_CONSONANTS = "fstkçşhp";
    public $DISCONTINIOUS_HARD_CONSONANTS = "pçtk";
    public $SOFTEN_DHC = "bcdğ";
    public $DISCONTINIOUS_HARD_CONSONANTS_AFTER_SUFFIX = "pçk";
    public $SOFTEN_DHC_AFTER_SUFFIX = "bcğ";

    public $MINOR_HARMONY = array( 
         "a" => "ı",
         "e" => "i",
         "ö" => "ü",
         "o" => "u",
         "ı" =>"ı",
         "i" => "i",
         "u" => "ı",
         "ü" => "ü"
    );

    public $MINOR_HARMONY_FOR_FUTURE = array(
        "a" => "a",
        "e" => "e",
        "ö" => "e",
        "o" => "a",
        "ı" => "a",
        "i" => "e",
        "u" => "a",
        "ü" => "e"
    );

    public $EXCEPTION_WORDS = array(
        "kontrol", "bandrol", "banal", "alpul", "ametal", "anormal", "amiral",
        "sadakat", "santral", "şefkat", "usul", "normal", "oryantal", "hakikat",
        "hayal", "saat", "kemal", "gol", "kalp", "metal", "faul", "mineral", "alkol",
        "misal", "meal", "oramiral", "tuğamiral", "orjinal", "koramiral", "general",
        "tümgeneral", "tuğgeneral", "korgeneral", "petrol", "liberal", "meral",
        "metrapol", "ekümenapol", "lokal", "lügat", "liyakat", "legal", "mentol",
        "beşamol", "meşgul", "meşekkat", "oval", "mahsul", "makul", "meraşal",
        "metaryal", "nasihat", "radikal", "moral", "dikkat", "rol", "sinyal",
        "sosyal", "total", "şevval", "sual", "spesiyal", "tuval", "turnusol", "hol",
        "tropikal", "zeval", "zelal", "terminal", "termal", "resul", "sadakat", "resital",
        "refakat", "pastoral", "hal", "müzikal", "müzikhol", "menkul", "mahmul", "maktul",
        "kolestrol", "kıraat", "ziraaat", "kapital", "katedral", "kabul", "kanaat", "jurnal",
        "kefal", "idrak", "istiklal", "integral", "final", "ekol", "emsal", "enternasyonal",
        "nasyonal", "enstrümantal", "harf", "cemal", "cemaat", "glikol", "karambol", "parabol", "kemal"
    );
    
    public $EXCEPTION_MISSING = array( 
        "isim" => "ism" ,
        "kasır" => "kasr" ,
        "kısım" => "kısm" ,
        "af" => "aff",
        "ilim" => "ilm",
        // "hatır": "hatr", # for daily usage only
        "boyun" => "boyn",
        "nesil" =>  "nesl",
        "koyun" => "koyn", // koyun (sheep) or koyun (bosom)? for koyun (sheep) there is no exception but for koyun (bosom) there is. aaaaargh turkish!!
        "karın" => "karn" // same with this, karın (your wife) or karın (stomach)? for karın (your wife) there is not a such exception
        //{ katli, katle, katli etc. it doesn't really have a nominative case but only with suffixes?
    );

    function lastVowel($word)
    {
		
        $word = $this->makeLower($word);

        $returndata = array();
        $vowel_count = 0;
   
		$letters = str_split($word);
		
		foreach ($letters as $letter)  
		{  
			if ($this->contains($this->FRONT_VOWELS, $letter))
			{
				$vowel_count++;
				$returndata = ["letter" => $letter, "tone" => "front" ];
			}
			else if ($this->contains($this->BACK_VOWELS, $letter))
			{
				$vowel_count++;
				$returndata = [ "letter" => $letter, "tone" => "back" ];
			}
		}
		
        // fake return for exception behaviour in Turkish
        if (in_array($word, $this->EXCEPTION_WORDS))
        {
            if ($returndata["letter"] == "o")
                $returndata = [ "letter"  => "ö", 'tone' => "back" ];
			
            else if ($returndata["letter"] == "a")
                $returndata = [ "letter"  => "e", 'tone' => "back" ];
			
            else if ($returndata["letter"] == "u")
                $returndata = [ "letter" => "ü", 'tone' => "back" ];
        }
        
        if (empty($returndata))
            $returndata = [ "letter" => "", "tone" => "back" ];

        $returndata["vowel_count"] = $vowel_count;
        
        return $returndata;
    }
        
    function lastLetter($word)
    {

        $word = $this->makeLower($word);

        $returndata = array("vowel" => false);
        $getLastLetter = substr($word, strlen($word) - 1);

        if ($getLastLetter == "'")
			$getLastLetter = substr($word, strlen($word) - 2)[0];  // charAt
		
        $returndata["letter"] = $getLastLetter;

        if ($this->contains($this->VOWELS, $getLastLetter))
        {
            $returndata["vowel"] = true;

            if ($this->contains($this->FRONT_VOWELS, $getLastLetter))
                $returndata["front_vowel"] = true;
            else
                $returndata["back_vowel"] = true;
        }

        else
        {
            $returndata["consonant"] = true;
			$returndata["discontinious_hard_consonant_for_suffix"] = false;

            if ($this->contains($this->HARD_CONSONANTS, $getLastLetter))
            {
				$returndata["hard_consonant"] = true;
			   
				
				if ($this->contains($this->DISCONTINIOUS_HARD_CONSONANTS_AFTER_SUFFIX, $getLastLetter))
				{
					$returndata["discontinious_hard_consonant_for_suffix"] = true;
					$getLastLetter = $this->SOFTEN_DHC_AFTER_SUFFIX[strrpos($this->DISCONTINIOUS_HARD_CONSONANTS_AFTER_SUFFIX, $getLastLetter)];
					$returndata["soften_consonant_for_suffix"] = $getLastLetter;
				}
			}
        }
       
        return $returndata;
    }

    function makeInfinitive($word)
    {
		$val = $this->lastVowel($word);
        return $val["tone"] == "front" ? $this->concat($word, "mak") :  $this->concat($word, "mek");
    }   
 
    function makePlural($word, $param)
    {
        if ($param["proper_noun"]) 
            $word .= "'";

        return $this->lastVowel($word)["tone"] == "front" ?  $this->concat($word, "lar") : $this->concat($word, "ler");
    }

    function makeAccusative($word, $param)
    {
        //firslty exceptions for o (he/she/it) 
        $lowerWord = $this->makeLower($word);

        if ($lowerWord == "o")
            return $param["proper_noun"] == true ? $this->fromUpperOrLower($word, "O'nu") : $this->fromUpperOrLower($word, "onu");
        else
        {
            if (array_key_exists($lowerWord, $this->EXCEPTION_MISSING) && $param["proper_noun"])
            {
                $word = $this->fromUpperOrLower($word, $this->EXCEPTION_MISSING[$lowerWord]);
                $lowerWord = $this->makeLower($word);
            }

            $getLastLetter = $this->lastLetter($word);
            $getLastVowel = $this->lastVowel($word);
			

            if ($param["proper_noun"]) 
                $word .= "'";

            if ($getLastLetter["vowel"])
                $word = $this->concat($word, "y");
			
            else if ($getLastLetter["discontinious_hard_consonant_for_suffix"] == true && $param["proper_noun"] == false)
            {
                if ($getLastVowel["vowel_count"] > 1)
                    $word = $this->concat(substr($word, 0, strlen($word) - 1), $getLastLetter["soften_consonant_for_suffix"]);
            }

           $word = $this->concat($word, $this->MINOR_HARMONY[$getLastVowel["letter"]]); //ToDo check
        }

        return $word;
    }
  
    function makeDative($word, $param)
    {
        
        //firslty exceptions for ben (I) and you (sen)
        $returndata;
        $lowerWord = $this->makeLower($word);

        if ($param["proper_noun"])
            $word .= "'";

        if ($lowerWord == "ben" && $param["proper_noun"] == false)
            $returndata = $this->fromUpperOrLower($word, "bana");
        else if ($lowerWord == "sen" && $param["proper_noun"] == false)
            $returndata = $this->fromUpperOrLower($word, "sana");
        else
        {
            if (array_key_exists($lowerWord, $this->EXCEPTION_MISSING) &&  $param["proper_noun"] == false)
            {
                $word = $this->fromUpperOrLower($word, $this->EXCEPTION_MISSING[$lowerWord]);
                $lowerWord = $this->makeLower($word);
            }
            
            $getLastLetter = $this->lastLetter($word);
            $getLastVowel = $this->lastVowel($word);
            
            if ($getLastLetter["vowel"] == true)
                $word = $this->concat($word, "y");
			
            else if ($getLastLetter["discontinious_hard_consonant_for_suffix"] == true)
            {
                if ($getLastVowel["vowel_count"] > 1 && $param["proper_noun"] == false)
                    $word = $this->concat(substr($word, 0, strlen($word) - 1), $getLastLetter["soften_consonant_for_suffix"]);
            }

            if ($getLastVowel["tone"] == "front")
                $word = $this->concat($word, "a");
            else
                $word = $this->concat($word, "e");

            $returndata = $word;
        }

        if ($this->isUpper($returndata))
            $returndata = $this->makeUpper($returndata);

        return $returndata;
    }

    function makeGenitive($word, $param)
    {
        $getLastLetter = $this->lastLetter($word);
        $getLastVowel = $this->lastVowel($word);
        $lowerWord = $this->makeLower($word);
		 
        if ($param["proper_noun"] == true)
            $word .= "'";
        
        if (array_key_exists($lowerWord, $this->EXCEPTION_MISSING))
        {
            $word = $this->fromUpperOrLower($word, $this->EXCEPTION_MISSING[$lowerWord]);
            $lowerWord = $this->makeLower($word);
        }
		
        if ($getLastLetter["vowel"])
            $word = $this->concat($word, "n");
		
        else if ($getLastLetter["discontinious_hard_consonant_for_suffix"] == true && $param["proper_noun"] == false)
        {
            if ($getLastVowel["vowel_count"] > 1)
                $word = $this->concat(substr($word, 0, strlen($word) - 1), $getLastLetter["soften_consonant_for_suffix"]); //ToDo: Check
        }
		
        $letter = $getLastVowel["letter"];
		
        $word = $this->concat($word, $this->MINOR_HARMONY[$letter]); 
        $word = $this->concat($word, "n");

        return $word;
    }

    function makeAblative($word, $param)
    {

        $getLastLetter = $this->lastLetter($word);
        $getLastVowel = $this->lastVowel($word);

        if ($param["proper_noun"])
            $word .= "'";

        if ($this->contains($this->HARD_CONSONANTS, $getLastLetter["letter"]))
            $word = $this->concat($word, "t");
        else
            $word = $this->concat($word, "d");

        if ($getLastVowel["tone"] == "front")
            $word = $this->concat($word, "an");
        else
            $word = $this->concat($word, "en");

        return $word;
    }
    
    function makeLocative($word, $param)
    {

        $getLastLetter = $this->lastLetter($word);
        $getLastVowel = $this->lastVowel($word);

        if ($param["proper_noun"])
            $word .= "'";

        if ($this->contains($this->HARD_CONSONANTS, $getLastLetter["letter"])) 
            $word = $this->concat($word, "t");
        else
            $word = $this->concat($word, "d");

        if ($getLastVowel["tone"] == "front")
            $word = $this->concat($word, "a");
        else
            $word = $this->concat($word, "e");

        return $word;
    }

    // İyelik ekleri
    function possessiveAffix($word, $param)
    {

        $person = $param["person"];
        $quantity = $param["quantity"];
            
        $getLastLetter = array();
        $getLastVowel = array();

        if ($person != "3" && $quantity != "plural") // Todo
        {
            $getLastLetter = $this->lastLetter($word);
            $getLastVowel = $this->lastVowel($word);
            
            if (isset($param["proper_noun"]) && $param["proper_noun"])
                $word .= "'";
			
            else if ($getLastLetter["discontinious_hard_consonant_for_suffix"])
            {
                if ($getLastVowel["vowel_count"] > 1)
                    $word = $this->concat(substr($word, 0, strlen($word) - 1), $getLastLetter["soften_consonant_for_suffix"]);

                if (array_key_exists($this->makeLower($word), $this->EXCEPTION_MISSING)) // Todo
                    $word = $this->fromUpperOrLower($word, $this->EXCEPTION_MISSING[makeLower($word)]);
            }
        }

        $getLastLetter = $this->lastLetter($word);
        $getLastVowel = $this->lastVowel($word);
        
        $lastLetterIsVowel = $this->contains($this->VOWELS, $getLastLetter["letter"]); // Todo: bool

        $letter = $getLastVowel["letter"];
        $minorHarmonyLetter = $this->MINOR_HARMONY[$letter];
        
        if ($quantity == "singular")
        {
            if ($lastLetterIsVowel == false)
                $word = $this->concat($word, $minorHarmonyLetter);

            if ($person == "1")
                $word = $this->concat($word, "m");

            else if ($person == "2")
                $word = $this->concat($word, "n");
        }
        else
        {
            if ($person == "1")
            {
                if ($lastLetterIsVowel == false)
                    $word = $this->concat($word, $minorHarmonyLetter);

                $word = $this->concat($word, "m");
                $word = $this->concat($word, $minorHarmonyLetter);
                $word = $this->concat($word, "z");
            }
            else if ($person == "2")
            {
                if ($lastLetterIsVowel == false)
                    $word = $this->concat($word, $minorHarmonyLetter);

                $word = $this->concat($word, "n");
                $word = $this->concat($word, $minorHarmonyLetter);
                $word = $this->concat($word, "z");
            }
            else
            {
                if ($this->makeLower($word) == "ism")
                    $word = $this->fromUpperOrLower($word, "isim");
                
                $word = $this->makePlural($word, array ( "proper_noun" => true ) ); // check
                $word = $this->concat($word, $minorHarmonyLetter);
            }

        }
        
        return $word;
    }

    function isUpper($word) {
		
		$word = str_replace("ı", "i", $word);
		$word = str_replace("İ", "I", $word);
		$word = str_replace("ş", "s", $word);
		$word = str_replace("Ş", "S", $word);
		$word = str_replace("ğ", "g", $word);
		$word = str_replace("Ğ", "G", $word);
		$word = str_replace("ü", "u", $word);
		$word = str_replace("Ü", "U", $word);
		$word = str_replace("ç", "c", $word);
		$word = str_replace("Ç", "C", $word);
		$word = str_replace("ö", "o", $word);
		$word = str_replace("Ö", "O", $word);

        return strtoupper($word) === $word;
    }   

    function makeLower($word) {
 		$word = str_replace("İ", "i", $word);
		$word = str_replace("I", "ı", $word);
		return strtolower($word);
    } 

    function makeUpper($word) {
		
		$word = str_replace("i", "İ", $word);
		$word = str_replace("ı", "I", $word);
		
		return strtoupper($word);
    } 

    function concat($word, $str) {
        return $this->isUpper($word) ? $word . $this->makeUpper($str) : $word . $str; 
    } 

    function fromUpperOrLower($newWord, $refWord) {
        
        $returndata = null;

        if (isUpper(substr($refWord, strlen($refWord) - 1)))
            $returndata = $this->makeUpper($newWord);
        else
        {
            if (isUpper(substr($refWord, 0))) // Todo
                $returndata = $this->makeUpper(substr($newWord, 0)) . $this->makeLower(substr($newWord, 1, strlen($newWord) - 1));  // Todo
            else
                $returndata = $this->makeLower($newWord);
        }
          
        return $returndata;
    }  
 
	function contains($string, $substring) {
		
        $pos = strpos($string, $substring);
 
        if($pos === false) {
			return false;
        }
        else {
			return true;
        }
 
	}

}
