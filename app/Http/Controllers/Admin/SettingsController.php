<?php

namespace App\Http\Controllers\Admin;

use App\Models\Settings;
use Auth;
use Illuminate\Http\Request;
use Session;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function socialsettings()
    {
        if (Auth::guest()) {
            return view('auth.login');
        } else {
            $set_det = Settings::orderBy('_id', 'desc')->first();
            $social = json_decode($set_det->social_links);
        }
        return view('admin.settings.socialsettings', ['details' => $social]);
    }

    public function socialsettingsupdate(Request $request)
    {
        $setting = Settings::orderBy('_id', 'desc')->first();

        $sociallink['androidlink'] = $request->get('val-androidlink');
        $sociallink['ioslink'] = $request->get('val-ioslink');
        $sociallink['facebooklink'] = $request->get('val-facebooklink');
        $sociallink['twitterlink'] = $request->get('val-twitterlink');
        $sociallink['instagramlink'] = $request->get('val-instagramlink');

        $setting->social_links = json_encode($sociallink);

        $setting->save();
        $notification = array(
            'message' => trans('app.Social links updated successfully'),
            'alert-type' => trans('app.success'),
        );
        session()->put('notification', $notification);
        return redirect('admin/socialsettings');
    }

    public function purchasesettings()
    {
        if (Auth::guest()) {
            return view('auth.login');
        } else {
            $set_det = Settings::orderBy('_id', 'desc')->first();
        }
        return view('admin.settings.purchasesettings', ['set_det' => $set_det]);
    }

    public function purchaseupdate(Request $request)
    {
        
        $setting = Settings::orderBy('_id', 'desc')->first();
        $setting->license_token = $request->get('val-license_token');
        $setting->adskey = $request->get('val-adskey');
        $setting->videoadskey = $request->get('val-video-adskey');
        $setting->adsenable = $request->get('val-adsenable');

        $setting->save();
        $notification = array(
            'message' => trans('app.Purchase details updated'),
            'alert-type' => trans('app.success'),
        );
        session()->put('notification', $notification);
        return redirect('admin/purchasesettings');
    }

    public function mainsettings()
    {
        if (Auth::guest()) {
            return view('auth.login');
        } else {
            $set_det = Settings::orderBy('_id', 'desc')->first();
            $app_locations = array();
            if($set_det->locations != ""){
                $app_locations = explode(',',$set_det->locations);
            }
            
        }

        return view('admin.settings.mainsettings', ['details' => $set_det,'app_locations'=>$app_locations]);
    }

    public function mainsettingsupdate(Request $request)
    {
        $this->validate(
            $request,
            [
                'val-sitename' => 'required|min:3',
                'val-metaname' => 'required|min:3',
                'val-copyrights' => 'required|min:5',
                'val-gems' => 'required|integer|between:0,999999',
                'val-invitegems' => 'required|integer|between:1,999999',
                'val-adsgems' => 'required|integer|between:1,999999',
                'val-contact_emailid' => 'email',
                'val-gems_commision_per' => 'required|integer|between:0,99',
                'val-show-videoads' => 'required|integer',
                'val-calls_debits' => 'required|integer',

            ],
            [
                'val-copyrights.required' => trans('app.Please provide copyrights'),
                'val-invitegems.required' => trans('app.Please provide gems count for invite friends'),
                'val-adsgems.required' => trans('app.Please provide gems count for watchig ads name'),
                'val-contact_emailid.required' => trans('app.Please provide contact emailid'),

                'val-sitename.required' => trans('app.Please provide site name'),
                'val-sitename.min' => trans('app.Provide minimum 3 characters for your site name'),
                'val-metaname.min' => trans('app.Meta title is too short'),
                'val-metaname.required' => trans('app.Please provide meta title'),
                'val-copyrights.min' => trans('app.Copyrights is too short'),
                'val-contact_emailid.email' => trans('app.Please provide valid email id'),
                'val-gems.integer' => trans('app.Please provide valid gems count'),
                'val-gems.between' => trans('app.Please provide valid gems count'),
                'val-invitegems.integer' => trans('app.Please provide valid gems count'),
                'val-invitegems.between' => trans('app.Please provide valid gems count'),
                'val-adsgems.integer' => trans('app.Please provide valid gems count'),
                'val-adsgems.between' => trans('app.Please provide valid gems count'),
                'val-gems_commision_per.required' => 'Please provide the valid gifts commission',
                'val-gems_commision_per.integer' => 'Please provide the valid gifts commission',
                'val-gems_commision_per.between' => 'Please provide the valid gifts commission',
                'val-show-videoads.integer' => trans('app.Enter a valid schedule time for video ad'),
                'val-show-videoads.required' => trans('app.Enter a valid schedule time for video ad'),
                'val-calls_debits.integer' => trans('app.Enter no of gems for reduction'),
                'val-calls_debits.required' => trans('app.Enter no of gems for reduction'),
                //'val-location.required' => trans('app.Please choose your locations'),
            ]
        );

        
        $setting = Settings::orderBy('_id', 'desc')->first();
        $setting->sitename = $request->get('val-sitename');
        $setting->meta_title = $request->get('val-metaname');
        $setting->page_title = $request->summernoteInputtitle;
        $setting->contact_emailid = $request->get('val-contact_emailid');
        $setting->copyrights = $request->get('val-copyrights');
        $setting->notification_key = $request->summernoteInput;
        $setting->welcome_message = $request->welcome_message;
        $setting->google_analytics = $request->summernoteInputgoogle;
        $setting->money_conversion = $request->get('val-money');
        $setting->video_ads = $request->get('val-videoads');
        $setting->schedule_video_ads = $request->get('val-show-videoads');
        $setting->gems_commision_per = $request->get('val-gems_commision_per');
        $setting->calls_debits = intval($request->get('val-calls_debits'));
        if ($request->get('val-location')) {
            $setting->locations = implode(',', $request->get('val-location'));
        }

        $reduction['sub'] = $request->get('val-red-sub');
        $reduction['unsub'] = $request->get('val-red-unsub');
        if(empty($request->get('val-red-sub')))
        {
            $reduction['sub'] = "";
        }
        if(empty($request->get('val-red-unsub')))
        {
            $reduction['unsub'] = "";
        }

        
        $gem_reduction = $reduction;
        $setting->gem_reduction = $gem_reduction;

        if ($request->get('val-gems') == '') {
            $setting->signup_credits = '0';
        } else {
            $setting->signup_credits = $request->get('val-gems');
        }
        if ($request->get('val-invitegems') == '') {
            $setting->invite_credits = '0';
        } else {
            $setting->invite_credits = $request->get('val-invitegems');
        }

        if ($request->get('val-adsgems') == '') {
            $setting->ads_credits = '0';
        } else {
            $setting->ads_credits = $request->get('val-adsgems');
        }

        if ($setting->save()) {
            $notification = array(
                'message' => 'Main settings updated',
                'alert-type' => 'success',
            );
        } else {
            $notification = array(
                'message' => 'Something went wrong',
                'alert-type' => 'error',
            );
        }
        session()->put('notification', $notification);
        return redirect('admin/mainsettings');
    }

    public function logosettings()
    {
        if (Auth::guest()) {
            return view('auth.login');
        } else {
            $set_det = Settings::orderBy('_id', 'desc')->first();
        }
        return view('admin.settings.logosettings', ['details' => $set_det]);
    }

    public function logosettingsupdate(Request $request)
    {
        $this->validate(
            $request,
            [
                'val-logo' => 'image|mimes:jpeg,png,jpg|max:2000',
                'val-darklogo' => 'image|mimes:jpeg,png,jpg|max:2000',
                'val-favicon' => 'image|mimes:jpeg,png,jpg|max:2000',
            ],
            [
                'val-logo.max' => trans('app.Logo image size must be less than 2mb'),
                'val-logo.image' => trans('app.Invalid file format. Please Upload an Image file'),
                'val-darklogo.max' => trans('app.Dark Logo image size must be less than 2mb'),
                'val-darklogo.image' => trans('app.Invalid file format. Please Upload an Image file'),
                'val-favicon.image' => trans('app.Invalid file format. Please Upload an Image file'),
                'val-favicon.max' => trans('app.Favicon size image must be less than 2mb'),
            ]
        );

        $setting = Settings::orderBy('_id', 'desc')->first();

        if ($request->file('val-logo')) {
            $image = $request->file('val-logo');
            $name = time() . $image->getClientOriginalName();
            $image->move(public_path() . '/img/logo/', $name);
            $data = $name;
            $setting->logo = $data;
        }

        if ($request->file('val-darklogo')) {
            $image = $request->file('val-darklogo');
            $name = time() . $image->getClientOriginalName();
            $image->move(public_path() . '/img/logo/', $name);
            $data = $name;
            $setting->darklogo = $data;
        }

        if ($request->file('val-favicon')) {
            $image = $request->file('val-favicon');
            $name = time() . $image->getClientOriginalName();
            $image->move(public_path() . '/img/logo/', $name);
            $data = $name;
            $setting->favicon = $data;
        }

        $setting->save();
        $notification = array(
            'message' => 'Logo settings updated',
            'alert-type' => 'success',
        );
        session()->put('notification', $notification);
        return redirect('admin/logosettings');
    }
    
    public function primesettings()
    {
        if (Auth::guest()) {
            return view('auth.login');
        } else {
            $set_det = Settings::orderBy('_id', 'desc')->first();
            $details = $set_det['prime_desc'];
        }
        return view('admin.settings.primesettings', ['details' => $details]);
    }

    public function primesettingsupdate(Request $request)
    {
        $this->validate(
            $request,
            [
                'val-price' => 'required|min:2',
                'val-duration' => 'required|min:3',
                'val-gems' => 'required|min:3',
            ],
            [
                'val-price.min' => trans('app.Provide minimum 2 characters'),
                'val-duration.min' => trans('app.Provide minimum 3 characters'),
                'val-gems.min' => trans('app.Provide minimum 3 characters'),
            ]
        );

        $setting = Settings::orderBy('_id', 'desc')->first();
        $details = $setting['prime_desc'];

        $det['prime_price'] = $request->get('val-price');
        $det['prime_availability'] = $request->get('val-duration');
        $det['no_of_gem'] = $request->get('val-gems');
        $det['prime_benefits'] = $details['prime_benefits'];

        $setting->prime_desc = $det;
        $setting->save();

        $notification = array(
            'message' => 'Prime benefits settings updated',
            'alert-type' => 'success',
        );
        session()->put('notification', $notification);
        return redirect('admin/primesettings');
    }

    public function sliderlist()
    {
        if (Auth::guest()) {
            return view('auth.login');
        } else {
            $set_det = Settings::orderBy('_id', 'desc')->first();
            $details = $set_det['prime_desc'];
            $data = $details['prime_benefits'];
        }
        return view('admin.settings.sliderlist', ['details' => $data]);
    }

    public function slidercreate()
    {
        if (Auth::guest()) {
            return view('auth.login');
        }
        return view('admin.settings.slidercreate');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sliderstore(Request $request)
    {

        $this->validate(
            $request,
            [
                'val-title' => 'required|min:3|max:30',
                'val-descrip' => 'required|min:3|max:250',
                'val-image' => 'required|image|mimes:png|max:2000',
            ],
            [
                'val-title.required' => trans('app.Slider Title  is required'),
                'val-title.min' => trans('app.Slider Title  must be at least 3 characters'),
                'val-title.max' => trans('app.Slider Title  may not be greater than 30 characters'),
                'val-descrip.required' => trans('app.Slider Description is required'),
                'val-descrip.min' => trans('app.Slider Description must be at least 3 characters.'),
                'val-descrip.max' => trans('app.Slider Description may not be greater than 250 characters.'),
                'val-image.required' => trans('app.Please provide slider image.'),
                'val-image.image' => trans('app.Invalid file format. Please Upload an png Image file'),
                'val-image.mimes' => trans('app.Invalid file format. Please Upload an png Image file'),

            ]
        );

        $setting = Settings::orderBy('_id', 'desc')->first();
        $details = $setting['prime_desc'];

        $sliders_count = $details['prime_benefits'];

        if (count($sliders_count) >= 10) {
            $notification = array(
                'message' => '10 slider added already, remove to add new sliders',
                'alert-type' => 'error',
            );
        } else {
            $det['prime_price'] = $details['prime_price'];
            $det['prime_availability'] = $details['prime_availability'];
            $det['no_of_gem'] = $details['no_of_gem'];

            $data['id'] = strval(rand('10', '100000'));
            $data['title'] = $request->get('val-title');
            $data['description'] = $request->get('val-descrip');

            if ($request->file('val-image')) {
                $image = $request->file('val-image');
                $name = time() . $image->getClientOriginalName();
                $image->move(public_path() . '/img/slider/', $name);
                $data['image'] = $name;
            }

            $det['prime_benefits'] = $details['prime_benefits'];
            array_push($det['prime_benefits'], $data);

            $setting->prime_desc = $det;
            if ($setting->save()) {
                $notification = array(
                    'message' => 'New prime benefit slider added',
                    'alert-type' => 'success',
                );
            } else {
                $notification = array(
                    'message' => 'Something went wrong',
                    'alert-type' => 'error',
                );
            }
        }

        session()->put('notification', $notification);
        return redirect('admin/sliderlist');
    }

    public function slideredit($id)
    {
        $set_det = Settings::orderBy('_id', 'desc')->first();
        $details = $set_det['prime_desc'];
        $data = array_values($details['prime_benefits']);
        foreach ($data as $key => $value) {
            if ($value['id'] == $id) {
                $keys = $key;
            }
        }

        if (!isset($keys)) {
            return view('errors.404');
        }

        return view('admin.settings.slideredit', ['data' => $data[$keys], 'id' => $id]);
    }

    public function sliderupdate(Request $request, $id)
    {

        $this->validate(
            $request,
            [
                'val-title' => 'required|min:3|max:30',
                'val-descrip' => 'required|min:3|max:250',
                'val-image' => 'image|mimes:png|max:2000',
            ],
            [
                'val-title.required' => trans('app.Slider Title  is required'),
                'val-title.min' => trans('app.Slider Title  must be at least 3 characters'),
                'val-title.max' => trans('app.Slider Title  may not be greater than 30 characters'),
                'val-descrip.required' => trans('app.Slider Description is required '),
                'val-descrip.min' => trans('app.Slider Description must be at least 3 characters.'),
                'val-descrip.max' => trans('app.Slider Description may not be greater than 250 characters.'),
                'val-image.image' => trans('app.Invalid file format. Please Upload an png Image file'),
                'val-image.mimes' => trans('app.Invalid file format. Please Upload an png Image file'),

            ]
        );

        $setting = Settings::orderBy('_id', 'desc')->first();
        $details = $setting['prime_desc'];

        $det['prime_price'] = $details['prime_price'];
        $det['prime_availability'] = $details['prime_availability'];
        $det['no_of_gem'] = $details['no_of_gem'];

        $data = array_values($details['prime_benefits']);
        $det['prime_benefits'] = array();
        foreach ($data as $key => $value) {
            if ($value['id'] == $id) {
                $keys = $key;
            } else {
                $det['prime_benefits'][$key] = $value;
            }
        }

        $data[$keys]['title'] = $request->get('val-title');
        $data[$keys]['description'] = $request->get('val-descrip');

        if ($request->file('val-image')) {
            $image = $request->file('val-image');
            $name = time() . $image->getClientOriginalName();
            $image->move(public_path() . '/img/slider/', $name);
            $data[$keys]['image'] = $name;
        }

        $det['prime_benefits'][$keys] = $data;

        $det['prime_benefits'] = $data;

        $setting->prime_desc = $det;

        if ($setting->save()) {
            $notification = array(
                'message' => 'prime benefits updated successfully',
                'alert-type' => 'success',
            );
        } else {
            $notification = array(
                'message' => 'Something went wrong',
                'alert-type' => 'error',
            );
        }

        session()->put('notification', $notification);
        return redirect('admin/sliderlist');
    }

    public function sliderdestroy($id)
    {
        $setting = Settings::orderBy('_id', 'desc')->first();
        $details = $setting['prime_desc'];

        $det['prime_price'] = $details['prime_price'];
        $det['prime_availability'] = $details['prime_availability'];
        $det['no_of_gem'] = $details['no_of_gem'];

        $data = array_values($details['prime_benefits']);
        $det['prime_benefits'] = array();
        foreach ($data as $key => $value) {
            if ($value['id'] == $id) {
                $keys = $key;
            } else {
                $det['prime_benefits'][$key] = $value;
            }
        }
        $setting->prime_desc = $det;

        if ($setting->save()) {
            $notification = array(
                'message' => 'Benefit slider deleted',
                'alert-type' => 'success',
            );
        } else {
            $notification = array(
                'message' => 'Something went wrong',
                'alert-type' => 'error',
            );
        }

        session()->put('notification', $notification);
        return redirect('admin/sliderlist');
    }

    public function slidershow($id)
    {
        $setting = Settings::orderBy('_id', 'desc')->first();
        $details = $setting['prime_desc'];
        $data = array_values($details['prime_benefits']);
        foreach ($data as $key => $value) {
            if ($value['id'] == $id) {
                $keys = $key;
            }
        }
        if (isset($keys) && $keys >= 0) {
            return view('admin.settings.slidershow', ['data' => $data[$keys]]);
        } else {
            return view('errors.404');
        }
    }

    public function emailsettings()
    {
        if (Auth::guest()) {
            return view('auth.login');
        } else {
            $set_det = Settings::orderBy('_id', 'desc')->first();
            $smtp = json_decode($set_det->mail_settings);
        }
        return view('admin.settings.emailsettings', ['details' => $smtp]);
    }

    public function emailsettingsupdate(Request $request)
    {

        $this->validate(
            $request,
            [
                'val-driver' => 'required|min:3|max:30',
                'val-host' => 'required|min:3|max:80',
                'val-port' => 'required|integer',
                'val-username' => 'required',
                'val-password' => 'required',
                'val-encryption' => 'required',
            ],
            [
                'val-driver.required' => trans('app.Please provide mail driver name.'),
                'val-driver.required' => trans('app.mail driver must be at least 3 characters'),
                'val-driver.required' => trans('app.mail driver may not be greater than 30 characters'),
                'val-host.required' => trans('app.Please provide host name.'),
                'val-port.required' => trans('app.Please provide mail port number.'),
                'val-username.required' => trans('app.Please provide username name.'),
                'val-password.required' => trans('app.Please provide password name.'),

            ]
        );

        $setting = Settings::orderBy('_id', 'desc')->first();

        $link['driver'] = $request->get('val-driver');
        $link['host'] = $request->get('val-host');
        $link['port'] = $request->get('val-port');
        $link['username'] = $request->get('val-username');
        $link['password'] = $request->get('val-password');
        $link['encryption'] = $request->get('val-encryption');

        $setting->mail_settings = json_encode($link);

        $setting->save();
        $notification = array(
            'message' => 'Mail settings updated successfully',
            'alert-type' => 'success',
        );
        session()->put('notification', $notification);
        return redirect('admin/emailsettings');
    }

    public function notificationsettings()
    {
        if (Auth::guest()) {
            return view('auth.login');
        } else {
            $set_det = Settings::orderBy('_id', 'desc')->first();
        }
        return view('admin.settings.notificationsettings', ['appsettings' => $set_det]);
    }

    public function updatenotificationsettings(Request $request)
    {
        $this->validate(
            $request,
            [
                'val-key' => 'required|max:10',
                'val-cert' => 'required|max:10',
                'val-pass' => 'required|max:30',
            ],
            [
                'val-key.required' => trans('app.Key is required'),
                'val-cert.required' => trans('app.Certificate is required'),
                'val-pass.required' => trans('app.Password is required'),
                'val-key.max' => trans('app.Key is invalid'),
                'val-cert.max' => trans('app.Certificate is invalid'),
            ]
        );

        $setting = Settings::orderBy('_id', 'desc')->first();

        if ($request->file('val-key')) {
            $image = $request->file('val-key');
            $name = "key.pem";
            $image->move(public_path() . '/img/voip/', $name);
            $data = $name;
            $setting->voip_key = $data;
        }

        if ($request->file('val-cert')) {
            $image = $request->file('val-cert');
            $name = "cert.pem";
            $image->move(public_path() . '/img/voip/', $name);
            $data = $name;
            $setting->voip_cert = $data;
        }

        $setting->voip_passpharse = $request->get('val-pass');
        $setting->save();
        $notification = array(
            'message' => 'Notification settings updated',
            'alert-type' => 'success',
        );
        session()->put('notification', $notification);
        return redirect('admin/notificationsettings');
    }
}
