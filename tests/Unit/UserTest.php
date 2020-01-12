<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;
use Illuminate\Support\Facades\Log;

class UserTest extends TestCase
{
     // Roll back data persistence in the database.
     use DatabaseTransactions;
 
     public function testCreateUser()
     {
         // Create three App\User instances...
         $users = factory(\App\User::class,3)->create();
     
         Log::info('TEST: User Create == '.$users); 
         echo 'TEST: User Create SUCCESS';
         echo "\n";
       
         $this->assertTrue(true);
     }
 
}
