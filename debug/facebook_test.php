<?php

/**
 * Access token will expire in 60 days
 * https://www.slickremix.com/facebook-60-day-user-access-token-generator
 * https://developers.facebook.com/tools/accesstoken/
 * App page https://developers.facebook.com/apps/1443647129088057/dashboard/ by jesus
 */
require_once  '../api/vendor/autoload.php'; // change path as needed

$accessToken         = 'EAAUgZCUpPcDkBAAFp8gXwcEi5LYFZAezfZCqh9J0lfHxEiwMQa1ZA6XjQI0XZCZCiufkzqc0jb8bi4JvFEWZCZC2LQhFbQHiTrKfmCZBKZAIgAwRibgLlKvSmyIoiBguiV5rZCTwQc36chZA3KYCnBiia92ifi0OqaoZBrbCVlJSR5eGL4gZDZD';
$userId              = '100000954625049';
$appId               = '1443647129088057';
$appSecret           = '65dc6764b9d4f2d4fe5cd31cf5cd718d';
$defaultGraphVersion = 'v2.10';


$fb = new \Facebook\Facebook([
    'app_id' => $appId,
    'app_secret' => $appSecret,
    'default_graph_version' => $defaultGraphVersion,
    //'default_access_token' => '{access-token}', // optional
]);

try {
    // Get the \Facebook\GraphNodes\GraphUser object for the current user.
    // If you provided a 'default_access_token', the '{access-token}' is optional.
    $response = $fb->get('/' . $userId, $accessToken);
} catch(\Facebook\Exceptions\FacebookResponseException $e) {
    // When Graph returns an error
    echo 'Graph returned an error: ' . $e->getMessage();
    exit;
} catch(\Facebook\Exceptions\FacebookSDKException $e) {
    // When validation fails or other local issues
    echo 'Facebook SDK returned an error: ' . $e->getMessage();
    exit;
}
$user = $response->getGraphUser();

echo '<br>Logged in as ' . $user->getName();
