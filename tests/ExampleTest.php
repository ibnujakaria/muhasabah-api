<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
      /**
      * eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsImlhdCI6MTQ2MTEyMDA4MSwiZXhwIjoxNDYzNTM5MjgxLCJuYmYiOjE0NjExMjAwODEsImp0aSI6ImI3MzRlMDY0NGRjZGRjNWFhY2Y0YmM2OTEwYWQyZmY5In0.8v4wWUrmD00dOVDuiJBsPa509zXx9fTSGZLd0QB1pUA
      */
      $user = \App\User::first();

      echo \JWTAuth::fromUser($user);
    }
}
