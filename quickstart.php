<?php
require __DIR__ . '/vendor/autoload.php';

// if (php_sapi_name() != 'cli') {
//     throw new Exception('This application must be run on the command line.');
// }

/**
 * Returns an authorized API client.
 * @return Google_Client the authorized client object
 */
$locales = 'America/Los_Angeles';
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

// Validation 

// header("content-type: application/json; charset=utf-8");

if (empty($_GET['event_name']) || empty($_GET['event_mail']) || empty($_GET['event_phone']) || empty($_GET['event_day']) || empty($_GET['event_time'])) {

  $response = json_encode(['error_status' => '0', 'data' => 'Fill all fields !']);
  echo $response;
  exit();
}

if (!(preg_match('/^[0-9]{6,}$/', $_GET['event_phone']))) {
  $response = json_encode(['error_status' => '0', 'data' => 'Invalid a phone number !']);
  echo $response;
  exit();
}

$name  = $_GET['event_name'];
$email = $_GET['event_mail'];
$phone_numb = $_GET['event_phone'];

// Merge datetime fields
$day = $_GET['event_day'];
$time = $_GET['event_time'];
$duration = $_GET['event_duration'];
$start_date = date("c", strtotime($day . ' ' . $time));

// Add duration for event
$end_date = new DateTime($start_date);
$conv = $end_date->modify('+' . $duration . 'minutes');
$end_date = $conv->format("c");

// Get the API client and construct the service object.
$client = getClient();
$service = new Google_Service_Calendar($client);

// Event attributes
$event = new Google_Service_Calendar_Event(array(
  'summary' => $name,
  'description' => 'You can call number: ' . $phone_numb . " for more infos :)",
  'start' => array(
    'dateTime' => $start_date,
    'timeZone' => $locales,
  ),
  'end' => array(
    'dateTime' => $end_date,
    'timeZone' => $locales,
  ),
  'recurrence' => array(
    'RRULE:FREQ=DAILY;COUNT=1'
  ),
  'attendees' => array(
    array('email' => $email),
  ),
  'reminders' => array(
    'useDefault' => FALSE,
    'overrides' => array(
      array('method' => 'email', 'minutes' => 15),
      array('method' => 'email', 'minutes' => 30),
    ),
  ),
));


$calendarId = 'primary';


$optParams2 = [
  'sendNotifications' => true
];

// send api to Google
$event = $service->events->insert($calendarId, $event, $optParams2);

if (isset($event->id)) {

  $response = json_encode(['error_status' => '1', 'data' => 'Event created successfully !!!']);
  echo $response;
  exit();
}
