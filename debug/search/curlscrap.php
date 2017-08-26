<?php

error_reporting(1);
include("simple_html_dom.php");

function strip_tags_content($text, $tags = '', $invert = FALSE) {
    /*
      This function removes all html tags and the contents within them
      unlike strip_tags which only removes the tags themselves.
     */
    //removes <br> often found in google result text, which is not handled below
    $text = str_ireplace('<br>', '', $text);

    preg_match_all('/<(.+?)[\s]*\/?[\s]*>/si', trim($tags), $tags);
    $tags = array_unique($tags[1]);

    if (is_array($tags) AND count($tags) > 0) {
        //if invert is false, it will remove all tags except those passed a
        if ($invert == FALSE) {
            return preg_replace('@<(?!(?:' . implode('|', $tags) . ')\b)(\w+)\b.*?>.*?</\1>@si', '', $text);
            //if invert is true, it will remove only the tags passed to this function
        } else {
            return preg_replace('@<(' . implode('|', $tags) . ')\b.*?>.*?</\1>@si', '', $text);
        }
        //if no tags were passed to this function, simply remove all the tags
    } elseif ($invert == FALSE) {
        return preg_replace('@<(\w+)\b.*?>.*?</\1>@si', '', $text);
    }

    return $text;
}

function file_get_contents_curl($url) {
    /*
      This is a file_get_contents replacement function using cURL
      One slight difference is that it uses your browser's idenity
      as it's own when contacting google.
     */

//    $ch = curl_init();
//    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
//    curl_setopt($ch, CURLOPT_HEADER, 0);
//    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//    curl_setopt($ch, CURLOPT_URL, $url);
//    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
//    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
//    $data = curl_exec($ch);
//    curl_close($ch);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
}

//Set query if any passed
$q = 'site:https://microsoft.com+market';
//Obtain the first page html with the formated url
$data = file_get_contents_curl('http://www.google.com/search?hl=en&q=' . $q);
/*
  create a simple_html_dom object from the retreived string
  you could also perform file_get_html("http://...") instead of
  file_get_contents_curl above, but it wouldn't change the default
  User-Agent
 */

$html = str_get_html($data);


$result = array();
$i = 0;
$linkObjs = $html->find('h3.r a');
foreach ($linkObjs as $linkObj) {
    $title = trim($linkObj->plaintext);
    $link = trim($linkObj->href);
    if (!preg_match('/^https?/', $link) && preg_match('/q=(.+)&amp;sa=/U', $link, $matches) && preg_match('/^https?/', $matches[1])) {
        $link = $matches[1];
    } else if (!preg_match('/^https?/', $link)) { // skip if it is not a valid link
        continue;
    }
    $descr = $html->find('span.st', $i); // description is not a child element of H3 therefor we use a counter and recheck.
    $result[] = array(
        'title' => $title,
        'link' => $link,
//        'desc' => $descr
    );
    $i++;
}
echo '<pre>';
print_r($result);
exit;

//foreach ($html->find('li.g') as $g) {
//    /*
//      each search results are in a list item with a class name 'g'
//      we are seperating each of the elements within, into an array
//
//      Titles are stored within <h3><a...>{title}</a></h3>
//      Links are in the href of the anchor contained in the <h3>...</h3>
//      Summaries are stored in a div with a classname of 's'
//     */
//    $h3 = $g->find('h3.r', 0);
//    $s = $g->find('div.s', 0);
//    $a = $h3->find('a', 0);
//    $result[] = array('title' => strip_tags($a->innertext),
//        'link' => $a->href,
//        'description' => strip_tags_content($s->innertext));
//}
//print_r($result);
//if ($_GET['serialize'] == '1') {
//    /*
//      if you pass serialize=1 to the script
//      it will echo out a serialized string
//      which can be unserialized back to an
//      array on a receiving script
//     */
//    echo serialize($result);
//} else {
//    /*
//      Otherwise it prints out the array structure so that it
//      is more human readible. You could instead perform a
//      foreach loop on the variable $result so that you can
//      organize the html output, or insert the data into a database
//     */
//    echo "<textarea style='width: 1024px; height: 600px;'>";
//    print_r($result);
//    echo "</textarea>";
//}
//Cleans up the memory 
$html->clear();
//exit();
?>