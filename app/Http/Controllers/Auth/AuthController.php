<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Repositories\UserRepository;
use App\Http\Requests;
use App\Http\Requests\RegisterRequest;
use App\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{

    protected $userRepository;
    protected $redirect = '/blog';

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function bindGithub($currentUser, $githubData)
    {
        $currentUser->github_id = $githubData['github_id'];
        $currentUser->github_name = $githubData['name'];
        if (config('app.avatar') == $currentUser->avatar)
            $currentUser->avatar = $githubData['avatar'];

        $meta = $currentUser->meta;
        $meta['github'] = $githubData['url'];
        $currentUser->meta = $meta;

        $this->userRepository->clearAllCache();

        return $currentUser->save();
    }

    public function store(RegisterRequest $request)
    {
        if (!session()->has('githubData')) {
            return redirect('login');
        }

        $name = $request->get('name');
        $githubData = session('githubData');
        $user = new User();
        $user->name = $name;
        $user->email = $githubData['email'];
        $user->avatar = config('app.avatar');
        $user->register_from = 'github';
        $user->password = bcrypt($request->get('password'));
        if ($this->bindGithub($user, $githubData)) {
            auth()->loginUsingId($user->id);
            session()->forget('githubData');
            return redirect($this->redirect)->with('success', __('USE_GITHUB_SING_UP_SUCCESS'));
        } else {
            session()->forget('githubData');
            return redirect($this->redirect)->with('success', __('USE_GITHUB_SING_UP_FAIL'));
        }
    }

    public function registerFromGithub()
    {
        if (!session()->has('githubData')) {
            return redirect('login');
        }
        $githubData = array_merge(session('githubData'), request()->old());
        return view('auth.github_register', compact('githubData'));
    }


    public function handleGithubCallback()
    {
        $githubUser = Socialite::driver('github')->user();
        $user = User::where('github_id', $githubUser->id)->first();

       
        if (auth()->check()) {
            $currentUser = auth()->user();
          
            if ($currentUser->github_id) {
               
                if ($currentUser->github_id == $githubUser->id) {
                    return redirect($this->redirect);
                }
                else {
                    return redirect($this->redirect)->withErrors('Sorry,you have bind a different github account!');
                }
            } 
            else {
               
                if ($user) {
                    return redirect($this->redirect)->withErrors('Sorry,this github account has been bind to another account,is that you?');
                } 
                else {
                    if ($this->bindGithub($currentUser, $this->getDataFromGithubUser($githubUser))) {
                        return redirect($this->redirect)->with('success', __('BIND_GITHUB_SUCCESS'));
                    }
                    return redirect($this->redirect)->withErrors(BIND_GITHUB_FAIL);
                }
            }
        } 
        else {
           
            if ($user) {
                auth()->loginUsingId($user->id);
                return redirect($this->redirect)->with('success', __('LOGIN').__('SUCCESS'));
            } 
            else {
                $githubData = $this->getDataFromGithubUser($githubUser);
                session()->put('githubData', $githubData);
                return redirect()->route('github.register');
            }
        }
    }

    private function getDataFromGithubUser($githubUser)
    {
        $githubData['github_id'] = $githubUser->id;
        $githubData['email'] = $githubUser->email;
        $githubData['avatar'] = $githubUser->avatar;
        $githubData['name'] = $githubUser->nickname;
        $githubData['url'] = $githubUser->user['html_url'];
        return $githubData;
    }
}
