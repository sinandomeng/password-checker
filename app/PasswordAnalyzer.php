<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Hash;

class PasswordAnalyzer extends Model
{
    public function register($password)
    {
        $hashedPassword = Hash::make($password);

        $password = new PasswordAnalyzer;
		$password->password = $hashedPassword;
        $password->save();
        
        return true;
    }
}
