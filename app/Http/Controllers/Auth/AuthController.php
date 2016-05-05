<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Api\UserApi;
use Illuminate\Http\Request;
use App\Libraries\GoogleJWTDecoder;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

  private $userApi;

  public function __construct(UserApi $userApi)
  {
    $this->userApi = $userApi;

    # setting up the middleware of this controller
    $this->middleware('jwt.auth', ['only' => ['getUser']]);
  }

  /**
  * this function should get the google JWTAuth
  * then parse it. Get the google-id, email, and name.
  * then search it on our users table, and store them if they dont exist
  */
  public function authenticate(Request $request, GoogleJWTDecoder $googleJwtDecoder)
  {
    /*
    $googleIdToken = $request->input('google-id-token');

    # check for the google id token whether its valid or not
    # if valid, it will return the user public data
    $result = $googleJwtDecoder->getUserPublicData($googleIdToken);

    if (!$result) {
      return response()->json([
        'error' =>  'token is invalid'
      ], 401);
    }

    # if the token is found, search to our internal database whether the user
    # is already registered or not.
    if (!$user = $this->userApi->getByGoogleId($result->google_id)) {
      # if not registered, then register it.
      $user = $this->userApi->newUser((array) $result);
    }

    # generate the new JWT token for this user (this is our own jwt, not belongs to google anymore)
    */
    # i disabled the google id login for this development, return the jwt directly
    # whatever its be
    $user = \App\User::first();
    $token = \JWTAuth::fromUser($user);

    return response()->json(compact('user', 'token'));
  }

  public function getUser()
  {
    # this will get the user data
    $user = \JWTAuth::parseToken()->authenticate();

    return response()->json(compact('user'));
  }
}
