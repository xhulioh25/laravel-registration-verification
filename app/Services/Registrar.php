<?php namespace App\Services;

use App\User;
use GuzzleHttp\Subscriber\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Laracasts\Flash\Flash;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function validator(array $data)
    {
        $rules = array(
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6');

        return Validator::make($data,$rules);
/*
 * If you want to limit the target group of the persons that can register based on their e-mail address, use the following block:
 *
 * ******
 * $rules = array(
 *          'name' => 'required|max:255',
 *          'lastname' => 'required|max:255',
 *          'email' => 'required|email|max:255|unique:users|regex:/(.*)cit\.edu\.al$/i',
 *          'password' => 'required|confirmed|min:6');
 *      $messages = array(
 *          'email.regex' => 'We appreciate your interest on using our Online System. However at the moment we offer this service only to @domain.com clients!'
 *      );
 *      return Validator::make($data,$rules, $messages);
 *
 *
 * */


    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    public function create(array $data)
    {

        $confirmation_code = str_random(30);

        $data = array_add($data, 'conf',$confirmation_code);

        Flash::message('Welcome ' .$data["name"]. '. Thanks for registering. We have sent you a validation link in your e-mail address!');
        Mail::send('emails.verify', $data, function($message) use ($data) {
            $message->to($data['email'], $data['name'])
                ->subject('Verify your email address');
        });
        return User::create([
            'name' => $data['name'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'confirmation_code' => $confirmation_code
        ]);



    }



}
