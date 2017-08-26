<?php

//require_once 'db_connect.php';
//if form is submitted
if (!$_POST) {

    $validator = array('success' => false, 'messages' => array());

    //Get Image From Facebook User Name Starts
    $profile_url = 'https://www.facebook.com/jesuserwin.suarez.9';//$_POST['fb_link'];

    /* Getting user id */
//    $url = 'https://findmyfbid.com';
//    $data = array('url' => $profile_url);

    // use key 'http' even if you send the request to https://...
//    $options = array(
//        'http' => array(
//            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
//            'method' => 'POST',
//            'content' => http_build_query($data),
//        ),
//    );
//    $context = stream_context_create($options);
//    $result = file_get_contents($url, false, $context);

    function getData($data) {
        $dom = new DOMDocument;
        libxml_use_internal_errors(true);
        $dom->loadHTML($data);
        $divs = $dom->getElementsByTagName('code');
        foreach ($divs as $div) {
            return $div->nodeValue;
        }
    }

//    $fb_link = $profile_url; //$_POST['fb_link'];
//    $string = ltrim($_POST['fb_link'], substr($fb_link, 0, strrpos($fb_link, '/')));
//    $f_url = 'https://www.facebook.com/' . $string;


//    echo $f_url;
    $ch = curl_init();
    $curlConfig = array(
        CURLOPT_URL => "https://findmyfbid.com/",
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => array(
            'url' => 'https://www.facebook.com/jesuserwin.suarez.9'
        )
    );
    curl_setopt_array($ch, $curlConfig);
    $c_result = curl_exec($ch);

    print strip_tags($c_result);


    $uid = getData($c_result);  // User ID


    print " id " . $uid;

    exit;
    $imgurl = 'http://graph.facebook.com/' . $uid . '/picture?height=1024';
//    $img = '/assets/image/user/' . $uid . ".jpg";
//    file_put_contents($img, file_get_contents($imgurl));
    //End

    $name = $_POST['name'];
    $messanger_link = 'https://messenger.com/t/' . $string;
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $notes = $_POST['notes'];
    $active = $_POST['active'];

//    $sql = "INSERT INTO members (name, contact,fb_link,messanger_link,image, address,notes, active) VALUES ('$name', '$contact','$fb_link','$messanger_link','$imgurl', '$address','$notes', '$active')";
//    $query = $connect->query($sql);
//
//    if ($query === TRUE) {
//        $validator['success'] = true;
//        $validator['messages'] = "Successfully Added";
//    } else {
//        $validator['success'] = false;
//        $validator['messages'] = "Error while adding the member information";
//    }
//
//    // close the database connection
//    $connect->close();

    echo json_encode($validator);
}