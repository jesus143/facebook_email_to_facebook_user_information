<?php 
class FacebookEmailConverter {

	 /*
	    Search Facebook without authorization
	    Query
	        user name, e-mail, phone, page etc
	    Types of search
	        all, people, pages, places, groups, apps, events
	    Result
	        Array with facebook page names ( facebook.com/{page} )
	    By      57ar7up
	    Date    2016
	*/

	 public $email_list;   
	 public $response;   
	 public $fullName;   
	 public $error;
	 private $facebookAccessToken;
	 public $query_param = [1,5,10,20,30,40,50,100];
	 public $query_limit = '';

    function __construct($facebookAccessToken='EAAUgZCUpPcDkBAAFp8gXwcEi5LYFZAezfZCqh9J0lfHxEiwMQa1ZA6XjQI0XZCZCiufkzqc0jb8bi4JvFEWZCZC2LQhFbQHiTrKfmCZBKZAIgAwRibgLlKvSmyIoiBguiV5rZCTwQc36chZA3KYCnBiia92ifi0OqaoZBrbCVlJSR5eGL4gZDZD')
    {
        $this->facebookAccessToken = $facebookAccessToken;
    }

    private function facebook_search($query, $type = 'all') {


        $randomNumber = 1;
//        print "random number $randomNumber";
//        print $_SERVER['HTTP_USER_AGENT'];
	    $url = 'http://www.facebook.com/search/'.$type.'/?q='.$query;
//         $user_agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/48.0.2564.109 Safari/537.36';
	    $user_agent = \Campo\UserAgent::random(); //'Mozilla/5.0 (X11; Ubuntu; Linux i686; rv:40.0) Gecko/20100101 Firefox/40.0';
//        print $user_agent;
        //
        // //
//        $user_agent =  'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.' . $randomNumber . ' Safari/537.36Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.2 Safari/537.36Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.' . $randomNumber . ' Safari/537.36'; //'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36';

//        $agents = array(
//            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10.7; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
//            'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.9) Gecko/20100508 SeaMonkey/2.0.4',
//            'Mozilla/5.0 (Windows; U; MSIE 7.0; Windows NT 6.0; en-US)',
//            'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_7; da-dk) AppleWebKit/533.21.1 (KHTML, like Gecko) Version/5.0.5 Safari/533.21.1'
//
//        );

        $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,";
        $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5";
        $header[] = "Cache-Control: max-age=0";
        $header[] = "Connection: keep-alive";
        $header[] = "Keep-Alive: 300";
        $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $header[] = "Accept-Language: en-us,en;q=0.5";
        $header[] = "Pragma: ";

	    $ch = curl_init();

       curl_setopt($ch, CURLOPT_URL, $url);
       // curl_setopt($ch, CURLOPT_PROXY, '188.255.12.241:8081');
       // curl_setopt($ch, CURLOPT_PROXY, '216.100.88.229:8080');
       // curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//       curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//       curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
//       curl_setopt($ch,CURLOPT_USERAGENT,$agents[array_rand($agents)]);
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
       curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

	    // curl_setopt_array($c, array(
	    //     CURLOPT_URL             => $url, 
	    //     CURLOPT_PROXY           => '216.100.88.229:8080',
 	    //        CURLOPT_HEADER          =>  true,
	    //     CURLOPT_USERAGENT       => $user_agent,
	    //     CURLOPT_RETURNTRANSFER  => TRUE,
	    //     CURLOPT_FOLLOWLOCATION  => TRUE,
	    //     CURLOPT_SSL_VERIFYPEER  => FALSE
	    // ));
 
	    $data = curl_exec($ch);
        curl_close($ch);
//	      echo strip_tags($data);

	    // print "<hr>";


	    preg_match_all('/href=\"https:\/\/www.facebook.com\/(([^\"\/]+)|people\/([^\"]+\/\d+))[\/]?\"/', $data, $matches);


//	    print "<pre>";
//            print "<br> results:";
//	        print_r($matches);
//        print "</pre>";

        // print " total results " . count($matches[3]) . '<br>';
    	// print_r($matches[3]);

	    if(count($matches[3]) > 0) { 
	    	// print " <br> !empty";
		    if($matches[3][0] != FALSE){                // facebook.com/people/name/id

		    	 print " <br> inside false ";
		        $pages = array_map(function($el){
		            return explode('/', $el)[0];
		        }, $matches[3]);

		    }



	    	// print " <br> inside false ";
	        // facebook.com/name
	        $pages = $matches[2];


 
	        // print_r($pages); 
    		return array_filter(array_unique($pages));  // Removing duplicates and empty values

	    } else { 
	    	// print " empty "; 
	    	return []; 
	    }

	}
  
 	protected function getFacebookSearchResult($email_list, $query_limit=1)
 	{  		
 			$data = []; 	 
		 	$emails = explode(',', $email_list);  
		 	$counter = 0;
		 	$counterLimit = 1;

		 	foreach($emails as $email) {
		 	    if($counterLimit <= $query_limit) {

                    $data[$counter]['email'] = $email;
                    $userName = $this->facebook_search($email, 'all');

                    if (!empty($userName)) {
                        $data[$counter]['facebook_username'] = $userName[0];
                    } else {
                        $data[$counter]['facebook_username'] = 'not available';
                    }

                    // check if email exist online
                    $status = $this->checkIfEmailExist($email);
                    if ($status == true) {
                        $data[$counter]['is_exist'] = 'email exist';
                    } else {
                        $data[$counter]['is_exist'] = 'email not exist';
                    }


                    $counter++;
                    $counterLimit++;
                } else {
		 	        break;
                }
			}	 

			return $data;		
 	}
 
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	
	public function run()
	{


	    if(true) {
            $emailList = '';
            $response = '';
            $fullName = '';
            $query_limit = '';

            if (isset($_POST['submit'])) {

                $emailList = (!empty($_POST['email_list']) ? $_POST['email_list'] : null);
                $fullName = (!empty($_POST['full_name']) ? $_POST['full_name'] : null);
                $query_limit = (!empty($_POST['query_limit']) ? $_POST['query_limit'] : 1);

                if (empty($fullName)) {
                    $this->error .= 'Please provide your full name.';
                }

                if (empty($emailList)) {
                    if (!empty($this->error)) {
                        $this->error .= '<br>';
                    }
                    $this->error .= 'Please provide your list of email.';
                }

                if(empty($this->error)) {
                    // Get username, profile and name from facebook
                    $response = $this->getFacebookSearchResult($emailList, $query_limit);

                    // export response to csv and store in public/csv
                    $this->exports_data_to_csv($response, $fullName);
                }
            }

            $this->fullName = $fullName;
            $this->response = $response;
            $this->email_list = $emailList;
            $this->query_limit = $query_limit;
//            $_SESSION['response'] = $this->response;

        }
	}


	 	public function exports_data_to_csv($emails, $fileName) {
		 	$data_array = [];
 
		 	foreach($emails as $email) {
		 		$data_array[]  = array_values($email); 
		 	}
 
			$csv = "Email,Username,Status \n";//Column headers
			foreach ($data_array as $record){
			    $csv.= $record[0].','.$record[1].','.$record[2]."\n"; //Append data to csv
			} 

			$csv_handler = fopen ('public/csv/' . str_replace(' ', '', ucwords($fileName)) . '.csv','w');
			fwrite ($csv_handler,$csv);
			fclose ($csv_handler);
 
        }

        protected function  checkIfEmailExist($email)
        {

			$curl = curl_init();

			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://api.fullcontact.com/v2/person.json?email=" . $email . "&X-FullContact-APIKey=9fdfef4d3d305663",
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 30,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"MerchantID_\"\r\n\r\nMS3709347\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"Pos_\"\r\n\r\nJSON\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data; name=\"PostData_\"\r\n\r\n31a54136a4d151febc7579b78c3e832883272c81ab366322e1a08b138cf5004389caf0f71bd65aef0867a5fe9ad52cd50a857c847c3a9f44f1ab5e4a11cbfc498a7f20db5184e961704d9ef30beb38fe828775e4d25b4dbe04147404f812f5321bb6d047df0580a838f28d5deed92492b83e54fa8afc4226add6fa0cf4bf551fb4c7a5d9ae50d6d2b6f6d282f4fdb212877592afe8335458106905d0bbf1af0e0ced4f06c715cc8475323a3a95f43d9c15a8071320f00b4502b0b617aa5acb56cd0e3fbae13a9e2e9d45915f4381462bf192ffadcba90434167638ac3ce6234a91180e344647d706ec26a732774eb59a69a64d772bfbee18a6d11080bfa10d51d2bb6f41853a9ba2e0b807dba93adb9816b2e2134ab301e7f72285e2286a651433722ec12b3c3146df6231916f3143ad5a0f57cdbf84c18aac1f6af82aed30fd00bb2b4f496080b8c3ff3d56f3f5a5753de5b919ac3a71d085f14c25593677ba7fd2c031049c10b527e8d96e65fd2bca5485e3ac0c3444425be0f4a676bcab13858626e12c4c223b09d1cecac0deb6b8868bb5b84696e7d2270fde6c10d27e46903dae21c9d54d9c14d05516df2e6768c170988d9d97f3c345ba725fe75dccb21b22ecf023b498923f4ad4ec2c2a6f52b49db1ee7ccd943e785c198ebe840dff5b3082984e0d7b109741bc1269a6363d3b8d302163683109aebdf59e84152a12\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
			  CURLOPT_HTTPHEADER => array(
			    "authorization: Basic bXJqZXN1c2Vyd2luc3VhcmV6QGdtYWlsLmNvbTpyZXBsYWNlbWVudDE=",
			    "cache-control: no-cache",
			    "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
			    "postman-token: 2c8f87ba-ce3a-a817-2b77-aa2d30a5a799",
			    "x-fullcontact-apikey: 9fdfef4d3d305663"
			  ),
			));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {

				return false; 

			} else {

				$response = json_decode($response, true); 

				if($response['status'] == 200) {
					return true;
				} else {
					return false;
				} 		
			}
        }

        public function getFacebookIdByFacebookUsername($facebookUsername)
        {
            $ch = curl_init();
            $curlConfig = array(
                CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36',
                CURLOPT_URL => "https://findmyfbid.com",
                CURLOPT_POST => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POSTFIELDS => array(
                    'url' => 'https://www.facebook.com/' . $facebookUsername
                )
            );

            curl_setopt_array($ch, $curlConfig);
            $c_result = curl_exec($ch);

            $userId = str_replace('{"id":', '', $c_result);
            $userId = str_replace('}', '', $userId);

            return $userId;
        }

        public function getFacebookUserFullNameByFacebookUserId($FacebookUserId)
        {
            $res = '';

            $ch4 = curl_init();
            curl_setopt($ch4, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch4, CURLOPT_URL, "https://graph.facebook.com/" . $FacebookUserId . "?access_token=" . $this->facebookAccessToken);
            curl_setopt($ch4, CURLOPT_SSL_VERIFYPEER, false);

            if(!$result = curl_exec($ch4))
            {
                echo curl_error($ch4);
            } else {

                $res = json_decode($result, true);
            }

            curl_close($ch4);

            return $res['name'];

        }
        public function getFacebookUseProfilePicByFacebookUserId($FacebookUserId)
        {
             return "//graph.facebook.com/$FacebookUserId/picture";
        }
        public function getDisplayUiCSVFiles()
        {
            $dir = "public/csv";
            $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $url = str_replace('/csv-view.php', '', $actual_link);
            $counter = 1;
            if (is_dir($dir)){
                if ($dh = opendir($dir)){
                    while (($file = readdir($dh)) !== false){
                        if($file != '..' and $file != '.') {
                            echo $counter . ".)  <a href='$url/public/csv/$file'>$file</a><br>";
                            $counter++;
                        }
                    }
                    closedir($dh);
                }
            }
        }
}
