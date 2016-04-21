<?php

namespace Webaccess\ProjectSquareLaravel\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Webaccess\ProjectSquareLaravel\Models\User;

class LoginController extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function login()
    {
        return view('projectsquare::auth.login', [
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
        ]);
    }

    public function authenticate()
    {
        if (Auth::attempt([
            'email' => Input::get('email'),
            'password' => Input::get('password'),
        ], Input::get('remember_token'))) {
            return redirect()->intended('/');
        }

        return redirect()->route('login')->with([
            'error' => trans('projectsquare::login.error_login_or_password'),
        ]);
    }

    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }

    public function forgotten_password()
    {
        return view('projectsquare::auth.password', [
            'error' => ($this->request->session()->has('error')) ? $this->request->session()->get('error') : null,
            'message' => ($this->request->session()->has('message')) ? $this->request->session()->get('message') : null,
        ]);
    }

    public function forgotten_password_handler()
    {
        $userEmail = Input::get('email');

        try {
            $newPassword = $this->generateNewPassword();
            if ($user = User::where('email', '=', $userEmail)->first()) {
                $user->password = bcrypt($newPassword);
                $user->save();
                $this->sendNewPasswordToUser($newPassword, $userEmail);
                Session::flash('message', 'Un email contenant votre nouveau mot de passe vous a été envoyé sur votre adresse.');
            } else {
                throw new \Exception('Utilisateur non trouvé');
            }
        } catch(\Exception $e) {
            Session::flash('error', $e->getMessage());
        }

        return Redirect::route('forgotten_password');
    }

    private function sendNewPasswordToUser($newPassword, $userEmail)
    {
        Mail::send('projectsquare::emails.password', array('password' => $newPassword), function($message) use($userEmail) {

            $message->to($userEmail)
                ->from('no-reply@projectsquare.fr')
                ->subject('[projectsquare] Votre nouveau mot de passe');
        });
    }

    private function generateNewPassword($length = 8)
    {
        $chars = 'abcdefghkmnpqrstuvwxyz23456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }
}
