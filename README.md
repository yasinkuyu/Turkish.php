Turkish.php
==========

### Turkish Suffix Library for PHP

## Composer Install 
	composer require yasinkuyu/turkish
	
	require_once __DIR__ . '/vendor/autoload.php';
    		
## Manual installation
	require 'Turkish.php';

## Using
    
		$tr = new Turkish; 

		echo $tr->makeGenitive("Öykü", array ( "proper_noun" => true ) ), PHP_EOL;
		echo $tr->makeDative("Fatma", array ( "proper_noun" => true ) ), PHP_EOL;

		echo $tr->makeDative("Yasin", array ( "proper_noun" => true ) ), PHP_EOL;
		echo $tr->makeDative("ALİ", array ( "proper_noun" => true ) ), PHP_EOL;
		echo $tr->makeAblative("Ali", array ( "proper_noun" => true ) ), PHP_EOL;
		echo $tr->makeAccusative("Kaliningrad", array ( "proper_noun" => true ) ), PHP_EOL;

		echo $tr->makeGenitive("ağaç", array ( "proper_noun" => false ) ), PHP_EOL;
		echo $tr->makeAccusative("erik", array ( "proper_noun" => false ) ), PHP_EOL;
		echo $tr->makeAccusative("Erik", array ( "proper_noun" => true ) ), PHP_EOL;

		echo $tr->possessiveAffix("kavanoz", array ( "person" => "1", "quantity" => "singular" ) ), PHP_EOL;
		echo $tr->possessiveAffix("kavanoz", array (  "person" => "2", "quantity" => "singular") ), PHP_EOL;
		echo $tr->possessiveAffix("kavanoz", array (  "person" => "3", "quantity" => "singular") ), PHP_EOL;

		echo $tr->possessiveAffix("halter", array (  "person" => "1", "quantity" => "plural") ), PHP_EOL;
		echo $tr->possessiveAffix("halter", array (  "person" => "2", "quantity" => "plural") ), PHP_EOL;
		echo $tr->possessiveAffix("halter", array (  "person" => "3", "quantity" => "plural") ), PHP_EOL;

		echo $tr->possessiveAffix("Kenya", array (  "person" => "3", "quantity" => "plural") ), PHP_EOL;

        
# Output
    
    Öykü'nün 
    Fatma'ya 
    Yasin'e 
    ALİ'YE 
    Ali'den 
    Kaliningrad'ı
    ağacın
    eriği
    Erik'i
    kavanozum
    kavanozun
    kavanozu
    halterimiz
    halteriniz
    halterleri
    Kenyaları 

## Turkish Grammar
 * Turkish is a highly agglutinative language, i.e., Turkish words have many grammatical suffixes or endings that determine meaning. Turkish vowels undergo vowel harmony. When a suffix is attached to a stem, the vowel in the suffix agrees in frontness or backness and in roundedness with the last vowel in the stem. Turkish has no gender.
 * [More Info](http://en.wikipedia.org/wiki/Turkish_grammar)

## Author
 * Yasin Kuyu
 * [Follow me at Twitter](http://twitter.com/yasinkuyu)

  
      C# Version
      https://github.com/yasinkuyu/Turkish.cs
      
      JavaScript Version
      https://github.com/yasinkuyu/Turkish.js
      
      Python Version
      https://github.com/miklagard/Turkish-Suffix-Library
