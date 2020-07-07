<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Response;
use App\SystemImages;
use Illuminate\Support\Facades\Log;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';

    public function redirectTo(){
        //user type
        $user_role = Auth::user()->user_role;
        switch ($user_role) {
            case 1:
                return 'admin/principal';
              break;
            case 2:
                return '/principal';
              break;
            case 3:
                return '/adviser';
              break;
            case 4:
                return '/teacher';
              break;
            case 5:
                return '/student';
              break;
        }
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request) {
      Auth::logout();
      return redirect('/login');
    }
}
