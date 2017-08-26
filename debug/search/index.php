<?php
include("simple_html_dom.php");

$error = '';
$resultCount = 1;
$arrResult = $arrSearchEngines = $linkObjs = array();

$arrSearchEngines[] = 'http://www.google.com/search?hl=en&tbo=d&site=&source=hp&q='; //for google
$arrSearchEngines[] = 'http://www.bing.com/search?hl=en&tbo=d&site=&source=hp&q='; // for bing
$arrSearchEngines[] = 'http://hotbot.com/search/?query='; //for hotbot 
$arrSearchEngines[] = 'https://www.googleapis.com/customsearch/v1?key='; // for google custom search
$arrSearchEngines[] = 'https://uk.search.yahoo.com/search?p='; // For yahoo

$i = $j = 0;
$isCustomSearch = 1;
$typeDesc = $descr = '';
if (!empty($_POST)) {
    if (!empty($_POST['keyword'])) {
        $resultCount = isset($_POST['resultsize']) && !empty($_POST['resultsize']) ? $_POST['resultsize'] : 1; // setting default value as 1;
        $in = $_POST['keyword'];
        $in = "$in";
        $forBing = "$in";
        $in = str_replace(' ', '+', $in); // replace space with +
        //-------CAN BE CHANGED. PLEASE APPEND THE + SIGN AT THE END OF ANY URL
        $q = 'site:https://www.facebook.com/groups+'; // Can be changed anytime as per requirement

        SEARCH:
        /**
         * For Google
         */
        $q = str_replace('/', '%2F', $q);
        $url = $arrSearchEngines[0] . $q . $in . '&num=' . $resultCount . '';

        $html = file_get_html($url);

        if ($html) {
            // For Google
            $typeDesc = 'google';
            $linkObjs = $html->find('h3.r a');
        } else {
            /**
             * For BING
             */
            $q = str_replace('/', '%2F', $q);
            $q = str_replace(':', '%3A', $q);
            $url = $arrSearchEngines[1] . $q . $forBing . '&count=' . $resultCount . '';

            $html = file_get_html($url);

            if ($html) {
                // For Bing
                $typeDesc = 'bing';
                $linkObjs = $html->find('h2 a');
            } else {
                /**
                 * For Hotbot
                 */
                $q = str_replace('/', '%2F', $q);
                $q = str_replace(':', '%3A', $q);
                $url = $arrSearchEngines[2] . $q . $in . '';

                $html = file_get_html($url);

                if ($html) {
                    // For Hotbot
                    $typeDesc = 'hotbot';
                    $linkObjs = $html->find('h3 a');
                } else {
                    /**
                     * For Yahoo
                     */
                    $q = str_replace('/', '%2F', $q);
                    $q = str_replace(':', '%3A', $q);
                    $url = $arrSearchEngines[4] . $q . $in . '&pz=' . $resultCount . '';
                    $html = file_get_html($url);

                    if ($html) {
                        //For yahoo
                        $typeDesc = 'yahoo';
                        $linkObjs = $html->find('h3 a');
                    } else {
                        //Google Custom Search
                        $isCustomSearch = 2;
                        $url = $arrSearchEngines[3] . $googleKey . '&cx=' . $googleClientId . '&q=' . $in . '&num=' . $resultCount . '';
                        $html = file_get_html($url);
                        if ($html) {
                            $googleCSR = json_decode($html);
                            if (count($googleCSR) > 0) {
                                foreach ($googleCSR->items as $items) {
                                    $arrResult[$j]['title'] = $items->title;
                                    $arrResult[$j]['link'] = $items->link;
                                    $arrResult[$j]['desc'] = $items->htmlSnippet;
                                    $j++;
                                }
                            }
                        } else {
                            $error = 'Your daily limit has been reached! Try again tomorrow';
                        }
                    }
                }
            }
        }

        if ($isCustomSearch == 1) { // If result does not come from google custom search
            if (count($linkObjs) > 0) {
                foreach ($linkObjs as $linkObj) {
                    $title = trim($linkObj->plaintext);
                    $link = trim($linkObj->href);
                    // if it is not a direct link but url reference found inside it, then extract
                    if (!preg_match('/^https?/', $link) && preg_match('/q=(.+)&amp;sa=/U', $link, $matches) && preg_match('/^https?/', $matches[1])) {
                        $link = $matches[1];
                    } else if (!preg_match('/^https?/', $link)) { // skip if it is not a valid link
                        continue;
                    }

                    // description is not a child element of H3 therefore we use a counter and recheck.
                    switch ($typeDesc) {
                        case 'bing':
                            $descr = $html->find('div.b_caption p', $i);
                            break;
                        case 'google':
                            $descr = $html->find('span.st', $i);
                            break;
                        case 'hotbot':
                            $descr = $html->find('div.description', $i);
                            break;
                        case 'yahoo':
                            $descr = $html->find('div.compText p', $i);
                            break;
                    }
                    $i++;
                    $arrResult[$j]['title'] = $title;
                    $arrResult[$j]['link'] = $link;
                    $arrResult[$j]['desc'] = $descr;
                    $j++;
                }
            } else {
                goto SEARCH;
            }
        }
    } else {
        $error = 'Please enter a keyword';
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="//suite.social/assets/css/bootstrap.min.css">
		
<style type="text/css">

body {
    color: #fff;
    background-color: #1f1f1f;
}

.container {
   padding: 0px;
}

.input-group .form-control {
    width: 97%;
}

.form-control {
    height: 48px;
    font-size: 18px;
}

.well {
    background-color: #1f1f1f;
    margin-top: 30px;
    border: 1px solid #616161;
}

.table-bordered {
    border: 1px solid #616161;
}

.table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    border-bottom: 1px solid #616161;
}

.table-bordered>tbody>tr>td {
    border: 1px solid #616161;
}

.table-bordered>thead>tr>th {
    border: 1px solid #616161;
}

</style>		
		

    </head>
    <body>
	
        <div class="container">
            <!--<div class="col-md-12">-->
                <form method="POST" action="" class="form">
                    <div class="panel">
                        <!--<div class="panel-heading"><b>Search and Like Pages</b> - For more results, <a href="//suite.social/pro">GO PRO!</a></div>-->
                        <div class="panel-body">
<?php if (isset($error) && !empty($error)) { ?>
                                <div class="row">
                                    <div class="alert alert-danger">
    <?php echo $error; ?>
                                    </div>
                                </div>
<?php } ?>
						<!--***** LOGIN PRO CODE *****-->
						<div class="row">
                            <div class="input-group">
                                <input name="keyword" class="form-control input-lg" placeholder="Keyword" type="text">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="submit" id="btn_scraper">Search!</button>
                                </span>
                            </div><!-- /input-group -->
                            <br>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <label>Record Per Page </label>
                                        <select name="resultsize" class="form-control">
                                            <option value="1" selected="">1</option>
                                            <option value="5" >5</option>
                                            <option value="10" >10</option>
                                            <option value="20" >20</option>
                                            <option value="30" >30</option>
                                            <option value="40" >40</option>
                                            <option value="50" >50</option>
                                            <option value="100" >100</option>
                                        </select>
                                    </div><!-- /input-group -->
                                </div>
                        </div><br/>
						<!--***** LOGIN PRO END *****-->
                            <div class="row">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php if (isset($arrResult) && count($arrResult) > 0) { ?>
                                            <?php foreach ($arrResult as $arrResult) { ?>
                                                <tr>
                                                    <td><b><?php echo $arrResult['title']; ?></b></td>
                                                    <td><?php echo $arrResult['desc']; ?></td>
                                                    <td><a class="btn btn-sm btn-primary" target="_blank" onclick="PopupCenter(this.href, 'newwin', '800', '600'); return false;" href="<?php echo $arrResult['link']; ?>">VIEW</a></td>
                                                </tr>
        <?php
    }
    ?>
                                        <?php } else {
                                            ?>
                                            <tr>
                                                <td colspan="3">No Result Found (Search keyword to get results)</td>
                                            </tr>
    <?php
}
?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </form>
            <!--</div>-->
        </div>

        <!--Scripts-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="//suite.social/assets/javascript/iframeResizer.contentWindow.min.js" defer></script>
        <script>
                                                function PopupCenter(url, title, w, h) {
                                                    // Fixes dual-screen position                         Most browsers      Firefox
                                                    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
                                                    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

                                                    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
                                                    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

                                                    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
                                                    var top = ((height / 2) - (h / 2)) + dualScreenTop;
                                                    var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

                                                    // Puts focus on the newWindow
                                                    if (window.focus) {
                                                        newWindow.focus();
                                                    }
                                                }
																								
			var level = document.location.search.replace(/\?/,'') || 0;
			$('#nested').attr('href','frame.nested.html?'+(++level));
			
        </script>

		<script>
			var iFrameResizer = {
					messageCallback: function(message){
						alert(message,parentIFrame.getId());
					}
				}
		</script>		
		
    </body>
</html>