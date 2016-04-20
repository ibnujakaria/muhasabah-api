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
      * eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjEsImlzcyI6Imh0dHA6XC9cL2xvY2FsaG9zdCIsImlhdCI6MTQ2MTExODMzNCwiZXhwIjoxNDYzNTM3NTM0LCJuYmYiOjE0NjExMTgzMzQsImp0aSI6Ijk0ZjdiYzI1MmJiZjYxZjA2N2QzNTNjNzcwODQwZjc0In0.Ue3PMTIDeVQBJZW0W5Z7g7EUdy8A5KrqjLHOFcY79UA
      */
      $user = \App\User::first();

      echo \JWTAuth::fromUser($user);
    }
}
