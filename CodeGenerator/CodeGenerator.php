<?php
	chdir(__DIR__);


	$variables = file_get_contents("_variables.scss");
	
	preg_match_all('/\$fa-var-(.*)\: \"(.*)\"\;/', $variables, $matches);

	$resultArray = array();
	foreach ($matches[1] as $k=>$v) {
		$resultArray[$matches[2][$k]] = $v;
	}
	
	print_r($resultArray);
	die();
	
	
	
	
	$cheatsheet = file_get_contents("http://fontawesome.io/cheatsheet/");
	$cheatsheet = trim($cheatsheet);
	$cheatsheet = str_replace(array("\t","\n"), "", $cheatsheet);
	
	
	
	
	preg_match_all("/<i class=\"fa fa-fw\" aria-hidden=\"true\" title=\"(.*?)\">(.*?)<\/i>(.*?)\<span class/", $cheatsheet,$results);
	
	$resultArray = array();
	
	foreach ($results[2] as $k => $v) {
		$resultArray[str_replace(";","",trim(str_replace("&#x","\u",$v)))] = trim($results[3][$k]);
	}
	
	ksort($resultArray);
	
	print_r($resultArray);
	die();
	
	//type:  fontAwesomeUnicodeStrings = @[@"\uf000",.....];
	$unicodeStrings = "fontAwesomeUnicodeStrings = @[%s];";
	$tmp = array();
	foreach ($resultArray as $k => $v) {
		$tmp[] = '@"'.$k.'"';
	}
	$unicodeStrings = sprintf($unicodeStrings,implode($tmp,", "));
	

	
	

	$dictionaryString = "\t\ttmp[@\"%s\"] = @(%s);\n";	
	$dictionaryStringFull = "";
	
	$enumType = array();
	
	foreach ($resultArray as $v) {
		//https://stackoverflow.com/questions/5546120/php-capitalize-after-dash
		$fatype = "FAIcon".substr($v, 2);
		$fatype = implode('-', array_map('ucfirst', explode('-', $fatype)));
		$fatype = str_replace("-", "", $fatype);
		$dictionaryStringFull .= sprintf($dictionaryString,$v,$fatype);				
		$enumType[] = "\t".$fatype;
	}



	//replacing enum strings
	$NSStringHeader = file_get_contents("../NSString+FontAwesome.h");
	//setup enum strings
	$enumTypeString = "//php-generator-enum-start\n".implode(",\n", $enumType)."\n\t//php-generator-enum-end";	
	//replace and put new header info in
	$NSStringHeaderNew = preg_replace("/\/\/php-generator-enum-start(.*)\/\/php-generator-enum-end/s",$enumTypeString,$NSStringHeader);
	//replace in file
	file_put_contents("../NSString+FontAwesome.h",$NSStringHeaderNew);


	//replacing dict strings
	$NSStringBody = file_get_contents("../NSString+FontAwesome.m");
	//setup dictionary types
	$dictionaryString = "//php-generator-dictionary-start\n".$dictionaryStringFull."\t\t//php-generator-dictionary-end";	
	//new body
	$NSStringBodyNew = preg_replace("/\/\/php-generator-dictionary-start(.*)\/\/php-generator-dictionary-end/s",$dictionaryString,$NSStringBody);
	//replace in file
	file_put_contents("../NSString+FontAwesome.m",$NSStringBodyNew);

	//replacing array strings
	$NSStringBody = file_get_contents("../NSString+FontAwesome.m");
	//setup dictionary types
	$unicodeArrayString = "//php-generator-unicode-array-start\n\t\t".$unicodeStrings."\n\t\t\t//php-generator-unicode-array-end";	
	//new body
	$NSStringBodyNew = preg_replace("/\/\/php-generator-unicode-array-start(.*)\/\/php-generator-unicode-array-end/s",$unicodeArrayString,$NSStringBody);
	//replace in file
	file_put_contents("../NSString+FontAwesome.m",$NSStringBodyNew);
	
	file_put_contents("log.log", print_r($resultArray,true));
	
	
	
?>