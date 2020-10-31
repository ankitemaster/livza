<?php
namespace App\Classes;
use Session;
use App\Models\Accounts;
use App\Models\Settings;
class MyClass
{
    public static function site_settings()
    {
        $siteSettings = Settings::first();
        return $siteSettings;
    }

    public static function default_userimage()
    {
        $siteSettings = Settings::first();
        return $siteSettings->default_usr_img;
    }

    public function pust_notification($token, $title, $to='one')
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $siteSettings = Settings::first();
        $notification = [
            'message' => $title,
            'sound' => true,
            'scope' => 'admin'
        ];
        $extraNotificationData = ["message" => $notification,'sound' => "default"];
        if($to == 'all')
        {
            $fcmNotification = [
                'registration_ids' => $token,
                'data' => $notification,
                'priority' => 'high'
            ];
        }
        else{
            $fcmNotification = [
                'to'=>$token,
                'data' => $notification,
                'priority' => 'high'
            ];
        }
        $headers = [
            'Authorization: key='.$siteSettings->notification_key,
            'Content-Type: application/json'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        if(json_decode($result)->success == 1){
            return true;   
        }
    }
    
    public function ios_pust_notification($token, $title, $to='one')
    {
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $siteSettings = Settings::first();
        $notification = [
            'message' => $title,
            'scope' => 'admin'
        ];
        $notification_data = array('notification_data' =>$notification );
        $extraNotificationData = ["message" => $notification,'sound' => "default"];
        $fcmNotification = [
            'registration_ids' => $token,
            'content_available' => true,
            "notification" => [
                "body" => $title,
                "title" => 'Randou Team',
            ],
            'data' => $notification_data,
            'priority' => 'high'
        ];
        $headers = [
            'Authorization: key='.$siteSettings->notification_key,
            'Content-Type: application/json'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        $result = curl_exec($ch);
        curl_close($ch);
        if(json_decode($result)->success == 1) {
            return true;    
        }
    }
}
?>