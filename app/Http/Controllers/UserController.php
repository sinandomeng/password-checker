<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rules\MustHaveUppercase;
use App\Rules\MustHaveLowercase;
use App\Rules\MustHaveNumberOrSymbol;
use App\PasswordAnalyzer;

class UserController extends Controller
{
    public function __construct()
    {
        // global model declaration
        $this->passwordModel = new PasswordAnalyzer();
        $this->passwordStrength = 0;
        $this->errorsArray = [];
    }

    public function clientRegistration(Request $request)
    {
        $data = $this->checkPassword($request->password);

        $passwordStrength = '';

        // check if there's an error
        if (!empty($data['errors'])) {
            // creating custom session
            $request->session()->flash('customError', $data['errors']);
        } else {
            // removing session
            $request->session()->flush();

            // checking the streng of the password then flush it to the session
            if ($this->passwordStrength === 1) {
                $passwordStrength = 'not-bad';
            } else if ($this->passwordStrength === 2) {
                $passwordStrength = 'good';
            } else if ($this->passwordStrength === 3) {
                $passwordStrength = 'strong';

                $this->passwordModel->register($request->password);
            } else {
                $passwordStrength = 'weak';
            }
    
            $request->session()->flash('passwordStrength', $passwordStrength);
        }
    
        return redirect('/')->withInput();
    }

    public function checkPassword($password)
    {
        if (strlen($password) < 8) {
            array_push($this->errorsArray, 'The password field must be at least 8 characters long.');
        } else {
            if (strlen($password) > 29) {
                array_push($this->errorsArray, 'The password must be less than or equal 30 characters long.');
            } else {
                $this->passwordStrength++;

                $this->regexValidation('whitespace', '/\s/', $password, 'The password field must not have space.');
                $this->regexValidation('casing', '/(?=\S*[A-Za-z])/', $password, 'The password field must have 1 uppercase and lowercase.');
                $this->regexValidation('numericSymbol', '/[0-9-!@$%^&*()_+|~=`{}\[\]:";\'<>?,.\/]/', $password, 'The password field must have 1 numeric or symbol character.');
            }
        }

        return [
            'result' => $this->passwordStrength,
            'errors' => $this->errorsArray
        ];
    }

    public function regexValidation($rule, $regex, $data, $message)
    {
        if ($rule === 'casing' || $rule === 'numericSymbol') {
            if (preg_match($regex, $data)) {
                $this->passwordStrength++;
            } else {
                array_push($this->errorsArray, $message);
            }
        } else if ($rule === 'whitespace') {
            if (preg_match('/\s/', $data)) {
                array_push($this->errorsArray, $message);
            }
        }
    }
}
