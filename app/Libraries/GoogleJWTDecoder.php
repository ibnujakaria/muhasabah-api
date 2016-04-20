<?php
namespace App\Libraries;

class GoogleJWTDecoder
{
  public function getUserPublicData($googleIdToken)
  {
    $curl = new \Curl\Curl();
    $curl->post('https://www.googleapis.com/oauth2/v3/tokeninfo', [
      'id_token'  =>  $googleIdToken
    ]);

    if (isset($curl->response->error_description)) {
      return null;
    }

    if ($curl->response->aud !== env('GOOGLE_SERVER_KEY')) {
      return null;
    }

    # if the app reaches this line of code, then the google id token is
    # correct. We simply return the result in an std::class
    $response = (object) [
      'google_id' =>  $curl->response->sub,
      'email' =>  $curl->response->email,
      'name'  =>  $curl->response->name,
      'avatar'=>  $curl->response->picture
    ];

    return $response;
  }
}
