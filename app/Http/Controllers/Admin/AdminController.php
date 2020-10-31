<?php

namespace App\Http\Controllers\Admin;

use App\Classes\MyClass;
use App\Http\Controllers\Admin\Controller;
use App\Models\Admin;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Agent;
use Redirect;
use Session;
use Validator;

class AdminController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function profile()
    {
        if (Auth::guest()) {
            return view('auth.login');
        } else {
            $admin_det = Admin::orderBy('_id', 'desc')->first();
        }
        return view('admin/site/profile', ['details' => $admin_det]);
    }

    public function profileupdate(Request $request)
    {
        $this->validate($request, [
            'val-username' => 'required|max:30',
            'val-email' => 'required',
        ],
            [
                'val-username.required' => trans('app.Username should not be empty'),
                'val-username.max' => trans('app.Maximum 30 characters are allowed'),
                'val-email.required' => trans('app.Email should not be empty'),
            ]);
        $admin = Admin::orderBy('_id', 'desc')->first();
        $admin->name = $request->get('val-username');
        $admin->email = $request->get('val-email');
        $admin->save();
        $notification = array(
            'message' => 'Updated successfully',
            'alert-type' => 'success',
        );
        session()->put('notification', $notification);
        return redirect('admin/profile');
    }

    public function resetpass()
    {

        return view('admin/site/resetpassword');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    public function passupdate(Request $request)
    {
        $v = Validator::make([], []);
        $this->validate($request, [
            'val-oldpassword' => 'required',
            'val-password' => 'required|min:6|different:val-oldpassword',
            'val-confirm-password' => 'required|same:val-password',
        ],
            [
                'val-oldpassword.required' => trans('app.Enter your Old Password'),
                'val-password.required' => trans('app.Provide your new password'),
                'val-password.min' => trans('app.Password length should be min 6 character'),
                'val-password.different' => trans('app.Your new password should not be same as old password'),
                'val-confirm-password.required' => trans('app.Re-enter your confirm password'),
                'val-confirm-password.same' => trans('app.Confirm password is wrong'),
            ]);

        $admin = Admin::orderBy('_id', 'desc')->first();
        if (!Hash::check($request->get('val-oldpassword'), $admin->password)) {
            $v->errors()->add('val-oldpassword', 'Provide correct old password.');
            return Redirect::back()->withErrors($v)->withInput();
        }

        $admin->password = Hash::make($request->get('val-password'));
        $admin->save();

        $notification = array(
            'message' => 'Password Changed successfully',
            'alert-type' => 'success',
        );
        session()->put('notification', $notification);
        return redirect('admin/resetpassword');

    }

    /* invite friends & earn gems */
    public function inviteearn()
    {
        $myClass = new MyClass();
        $agent = new Agent();
        $settings = $myClass->site_settings();
        $social = json_decode($settings->social_links);
        $platform = $agent->platform(); // detect platform
        echo $platform;
        die;
        if ($platform == "iOS") {
            $url = $social->ioslink;
            return Redirect::to($url);
        } else {
            $url = $social->androidlink;
            return Redirect::to($url);
        }
    }
}
