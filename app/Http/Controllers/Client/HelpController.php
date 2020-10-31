<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Helps;
use App\Models\Settings;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class HelpController extends Controller
{
    public function contact()
    {
        $siteSettings = Settings::select('social_links', 'meta_title', 'logo', 'sitename', 'google_analytics', 'copyrights', 'darklogo')->first();
        $social = json_decode($siteSettings->social_links);
        $privacyTerms = Helps::where('help_title', "Privacy Policy")->first();
        $helpTerms = Helps::whereNotIn('help_title', ["Privacy Policy"])->get();
        return view('client.contact', compact('privacyTerms', 'helpTerms', 'siteSettings', 'social'));
    }

    public function privacy()
    {
        $siteSettings = Settings::select('social_links', 'meta_title', 'logo', 'sitename', 'google_analytics', 'copyrights', 'darklogo')->first();
        $social = json_decode($siteSettings->social_links);
        $privacyTerms = Helps::where('help_title', "Privacy Policy")->first();
        return view('client.privacy', compact('privacyTerms', 'siteSettings', 'social'));
    }

    public function terms()
    {
        $siteSettings = Settings::select('social_links', 'meta_title', 'logo', 'sitename', 'google_analytics', 'copyrights', 'darklogo')->first();
        $social = json_decode($siteSettings->social_links);
        $helpTerms = Helps::whereNotIn('help_title', ["Privacy Policy", "FAQ"])->get();
        return view('client.terms', compact('helpTerms', 'siteSettings', 'social'));
    }

    public function faq()
    {
        $siteSettings = Settings::select('social_links', 'meta_title', 'logo', 'sitename', 'google_analytics', 'copyrights', 'darklogo')->first();
        $social = json_decode($siteSettings->social_links);
        $faqTerms = Helps::where('help_title', "FAQ")->first();
        return view('client.faq', compact('faqTerms', 'siteSettings', 'social'));
    }

    public function contactmail(Request $request)
    {
        $siteSettings = Settings::select('contact_emailid', 'copyrights', 'meta_title', 'darklogo', 'sitename', 'mail_settings', 'google_analytics')->first();

        /* contact details */
        $contact_firstname = $request->input('mail_firstname');
        $contact_lastname = $request->input('mail_lastname');
        $contact_email = $request->input('mail_id');
        $contact_phone = $request->input('mail_phone');
        $contact_message = $request->input('mail_message');
        $smtp = json_decode($siteSettings->mail_settings);

        $this->validate(
            $request,
            [
                'mail_firstname' => 'required|min:3|max:30',
                'mail_lastname' => 'required|min:3|max:30',
                'mail_id' => 'required|email',
                'mail_phone' => 'required',
                'mail_message' => 'required|min:3|max:500',

            ],
            [
                'mail_firstname.required' => trans('app.Firstname is required'),
                'mail_lastname.required' => trans('app.Lastname is required'),
                'mail_id.required' => trans('app.Email is required'),
                'mail_phone.required' => trans('app.Phone number is required'),
                'mail_message.required' => trans('app.Message is required'),
                'mail_id.email' => trans('app.Email is not valid'),
                'mail_firstname.min' => trans('app.Firstname must be at least 3 characters.'),
                'mail_firstname.max' => trans('app.Firstname may not be greater than 30 characters.'),
                'mail_lastname.min' => trans('app.Lastname must be at least 3 characters.'),
                'mail_lastname.max' => trans('app.Lastname may not be greater than 30 characters.'),
                'mail_message.min' => trans('app.Email must be at least 3 characters.'),
                'mail_message.max' => trans('app.Email may not be greater than 30 characters.'),
            ]
        );

        $ssl = '';
        if ($smtp->encryption == 1) {
            $ssl = 'ssl';
        } else {
            $ssl = 'tls';
        }

        $config = array(
            'driver' => $smtp->driver,
            'host' => $smtp->host,
            'port' => $smtp->port,
            'encryption' => $ssl,
            'from' => [
                'address' => $siteSettings->contact_emailid,
                'name' => $siteSettings->sitename,
            ],
            'username' => $smtp->username,
            'password' => $smtp->password,
            'sendmail' => '/usr/sbin/sendmail -bs',
            'pretend' => false,
        );
        Config::set('mail', $config);

        $subject = 'Contact Mail';

        $mail_data = [
            'name' => $contact_firstname . ' ' . $contact_lastname,
            'email_from' => $smtp->username,
            'email_to' => $contact_email,
            'phone' => $contact_phone,
            'comments' => $contact_message,
            'subject' => 'Contact Mail',
            'siteSettings' => $siteSettings,
            'darklogo' => url('/') . '/public/img/logo/' . $siteSettings->darklogo,
        ];

        try {
            $mail_status = Mail::send('email.contactus', $mail_data, function ($message) use ($mail_data) {
                $message->to($mail_data['email_to'])
                    ->from($mail_data['email_from'], $mail_data['name'])
                    ->subject($mail_data['subject']);
            });
        } catch (\Swift_TransportException $e) {
            /*  print_r($e);
            die; */
        }

        return redirect('contact');

    }
}
