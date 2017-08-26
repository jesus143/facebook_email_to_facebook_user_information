<?php 

	function facebook_search($query, $type = 'all') {
	    $url = 'http://www.facebook.com/search/'.$type.'/?q='.$query; 
	    $user_agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36';

	    $c = curl_init();
	    curl_setopt_array($c, array(
	        CURLOPT_URL             => $url,
	        CURLOPT_USERAGENT       => $user_agent,
	        CURLOPT_RETURNTRANSFER  => TRUE,
	        CURLOPT_FOLLOWLOCATION  => TRUE,
	        CURLOPT_SSL_VERIFYPEER  => FALSE
	    ));
	    $data = curl_exec($c); 
	    echo $data; 
	}

	facebook_search('mrjesuserwinsuarez@gmail.com');
