<?php

$data = array("http://link1.com" =>"<body><p>Dog</p><p>Dog</p><p>Cat</p><p>Fish</p><p>Fish</p><p>Fish</p><p>Fish</p><p>Fish</p><a href=\"https://link2.com\">second website</a><a href=\"https://link3.com\">third website</a></body>","https://link2.com"=>"<body><p>Dog</p><p>Dog</p><p>Dog</p><p>Dog</p><p>Dog</p><p>Cat</p><p>Fish</p><a href=\"https://link2.com\">second website</a></body>","https://link3.com"=>"<body><p>Dog</p><p>Dog</p><p>Dog</p><p>Cat</p><p>Fish</p><p>Cat</p><p>Cat</p><p>Cat</p><p>Cat</p><a href=\"https://link2.com\">second website</a></body>");
$links = array();
//input : passed data for one url output: reads other urls find in data calculate word count.
function getWordCount($value,$weburl){
	global $data,$wordCount,$ParsedLinks,$links;
	

	// store parsed url in array for reference
	$ParsedLinks[] = $weburl;
	
	//remove parsed website links
    if(!empty($links)){
		if (($key = array_search($weburl, $links)) !== false) {
			unset($links[$key]);
		}
	}
	
	// get all words and store their count 
    preg_match_all('/<p>(.*?)<\/p>/s', $value, $matches);
    $wordCount[$weburl] = array_count_values($matches[1]);
    
	// find links and store into array
	preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $value, $match);
	foreach($match[0] as $urls){
		if (!in_array($urls, $links) && !in_array($urls, $ParsedLinks)) {
			array_push($links,$urls);
		}
		else{
			continue;
		}
	}


	//recursive call for website
	if(!empty($links)){
		foreach($links as $link){
			getWordCount($data[$link],$link);
		}
	}
	return $wordCount;
}

// print website as per their rank
function rankWiseResult($crwaler,$word){
	$rank = array();
    foreach($crwaler as $key => $value){
        foreach($value as $name => $count){
            if(strtolower($name) == strtolower($word)){
                $rank[$key] = $count;
                break;
            }
        
        }
	}
	arsort($rank);
	return $rank;
}

// reads input
$word = readline("Enter a word: ");

$crwaler =  getWordCount($data['http://link1.com'],'http://link1.com');
$result = rankWiseResult($crwaler,$word);
if(!empty($result))
	print_r($result);
else
	echo "Word not Found";
?>