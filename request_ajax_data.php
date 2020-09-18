<?php

require __DIR__ . '/vendor/autoload.php';
use Carbon\Carbon;

function getClient()
{
    $client = new Google_Client();
    $client->setApplicationName('Google Calendar API PHP Quickstart');
    $client->setScopes(Google_Service_Calendar::CALENDAR);
    $client->setAuthConfig(__DIR__ . '/credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}



//  dd($new_time_data->toRfc3339String());
$client = getClient();
$service = new Google_Service_Calendar($client);
$calendarId = 'primary';

// header("content-type: application/json; charset=utf-8");

if ( empty($_POST['event_name']) || empty($_POST['event_mail']) || empty($_POST['event_phone']) || empty($_POST['event_day']) || empty($_POST['event_time']))
    {

    $response = json_encode(['error_status'=>'0','data'=>'Fill all fields !']);
    echo $response;
    exit(); 
    }

if(!(preg_match('/^[0-9]{6,}$/', $_POST['event_phone'])))
{
        $response = json_encode(['error_status'=>'0','data'=>'Invalid a phone number']);
        echo $response;
        exit();
}

$event = $service->events->insert($calendarId, $event);
$response = json_encode(['error_status'=>'1','data'=>'Event created successfully !!!']);
echo $response;