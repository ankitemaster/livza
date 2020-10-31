<?php
namespace App\Http\Controllers\Admin;

use App\Classes\MyClass;
use App\Models\Accounts;
use App\Models\Adminchats;
use App\Models\Devices;
use App\Models\Gems;
use App\Models\Payments;
use App\Models\Streamreports;
use App\Models\Streams;
use App\Models\Subscriptions;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if (Auth::guest()) {
            return view('auth.login');
        } else {
            $total_accounts = Accounts::count();
            if ($total_accounts == 0) {
                $female_per = 0;
                $random_per = 0;
                $sub_per = 0;
                $random_chats = 0;
                $subscribers = 0;
            } else {
                $female_accounts = Accounts::where(['acct_gender' => 'female'])->count();
                $female_per = round(($female_accounts / $total_accounts) * 100, 2);
                $random_chats = Accounts::where(['acct_live' => '2'])->orWhere(['acct_live' => '3'])->count();
                $random_per = round(($random_chats / $total_accounts) * 100, 2);
                $random_chats = Accounts::where(['acct_live' => '2'])->orWhere(['acct_live' => '3'])->count();
                $random_per = round(($random_chats / $total_accounts) * 100, 2);
                $subscribers = Accounts::where(['acct_membership' => 'sub'])->count();
                $sub_per = round(($subscribers / $total_accounts) * 100, 2);
            }
            $total_transcations = Payments::count();
            $todaystarts = Carbon::now()->startOfDay();
            $todayends = Carbon::now()->endOfDay();
            $today_transcations = Payments::whereBetween(
                'pymt_on',
                array(
                    $todaystarts,
                    $todayends,
                )
            )->count();
            $membership_transcations = Payments::where(['pymt_type' => 'membership'])->count();
            $gems_transcations = Payments::where(['pymt_type' => 'gems'])->count();
            if ($total_transcations == 0) {
                $membership_transt_per = 0;
                $gems_transt_per = 0;
                $today_transt_per = 0;
            } else {
                $membership_transt_per = round(($membership_transcations / $total_transcations) * 100, 2);
                $gems_transt_per = round(($gems_transcations / $total_transcations) * 100, 2);
                $today_transt_per = round(($today_transcations / $total_transcations) * 100, 2);
            }
            $user_from_countries = Accounts::groupBy('acct_location')->get(['acct_location']);
            $total_countries = count($user_from_countries);
            $peopleby = Accounts::raw()->aggregate([
                [
                    '$group' => [
                        '_id' => '$acct_location',
                        'region_count' => array('$sum' => 1),
                    ],
                ],
                [
                    '$sort' => [
                        'region_count' => -1,
                    ],
                ],
                ['$limit' => 1],
            ]);
            $mostused_country = " ";
            foreach ($peopleby as $people) {
                $mostused_country = $people->_id;
            }
            $mostused_country_users = Accounts::where(['acct_location' => $mostused_country])->count();
            if ($total_accounts == 0) {
                $mostused_per = 0;
            } else {
                $mostused_per = round(($mostused_country_users / $total_accounts) * 100, 2);
            }

            $user_report = Streamreports::count();
            if ($user_report == 0) {
                $user_report_per = 0;
            } else {
                $today_user_report_per = Streamreports::whereBetween(
                    'rept_on',
                    array(
                        $todaystarts,
                        $todayends,
                    )
                )->count();
                $user_report_per = round(($today_user_report_per / $user_report) * 100, 2);
            }

            $total_streams = Streams::count();
            $today_stream = Streams::whereBetween(
                'created_at',
                array(
                    $todaystarts,
                    $todayends,
                )
            )->count();
            if ($total_streams == 0) {
                $total_streams = 0;
                $today_stream_per = 0;
            } else {

                $today_stream_per = round(($today_stream / $total_streams) * 100, 2);
            }

            $todaystarts = Carbon::now()->startOfDay();
            $todayends = Carbon::now()->endOfDay();
            /* user with more gifts & gems */
            $leaderboard = Accounts::where('acct_status', '=', 1)
            ->orderBy('acct_gifts', 'desc')
            ->orderBy('acct_gems', 'desc')
            ->limit(10)->get();
            return view('admin/dashboard', compact([
                'total_accounts', 'female_per', 'random_chats', 'random_per', 'subscribers', 'sub_per',
                'total_transcations', 'membership_transcations',
                'gems_transcations', 'total_countries', 'mostused_country',
                'mostused_per', 'membership_transt_per', 'gems_transt_per', 'today_transt_per', 'leaderboard', 'user_report', 'user_report_per','total_streams','today_stream_per'
            ]));
        }
    }

    public function getRatio($num1, $num2)
    {
        for ($i = $num2; $i > 1; $i--) {
            if (($num1 % $i) == 0 && ($num2 % $i) == 0) {
                $num1 = $num1 / $i;
                $num2 = $num2 / $i;
            }
        }
        return "$num1:$num2";
    }

    public function getdashboarddata()
    {
        $total_accounts = Accounts::count();
        $female_accounts = Accounts::where(['acct_gender' => 'female'])->count();
        $female_per = round(($female_accounts / $total_accounts) * 100, 2);
        $random_chats = Accounts::where(['acct_live' => '2'])->orWhere(['acct_live' => '3'])->count();
        $random_per = round(($random_chats / $total_accounts) * 100, 2);
        $random_chats = Accounts::where(['acct_live' => '2'])->orWhere(['acct_live' => '3'])->count();
        $random_per = round(($random_chats / $total_accounts) * 100, 2);
        $subscribers = Accounts::where(['acct_membership' => 'sub'])->count();
        $sub_per = round(($subscribers / $total_accounts) * 100, 2);
        $total_transcations = Payments::count();
        $todaystarts = Carbon::now()->startOfDay();
        $todayends = Carbon::now()->endOfDay();
        $today_transcations = Payments::whereBetween(
            'pymt_on',
            array(
                $todaystarts,
                $todayends,
            )
        )->count();
        $membership_transcations = Payments::where(['pymt_type' => 'membership'])->count();
        $gems_transcations = Payments::where(['pymt_type' => 'gems'])->count();
        if ($total_transcations == 0) {
            $total_transcations = 1;
        }
        $membership_transt_per = round(($membership_transcations / $total_transcations) * 100, 2);
        $gems_transt_per = round(($gems_transcations / $total_transcations) * 100, 2);
        $today_transt_per = round(($today_transcations / $total_transcations) * 100, 2);
        $user_from_countries = Accounts::groupBy('acct_location')->get(['acct_location']);
        $total_countries = count($user_from_countries);
        $peopleby = Accounts::raw()->aggregate([
            [
                '$group' => [
                    '_id' => '$acct_location',
                    'region_count' => array('$sum' => 1),
                ],
            ],
            [
                '$sort' => [
                    'region_count' => -1,
                ],
            ],
            ['$limit' => 1],
        ]);
        foreach ($peopleby as $people) {
            $mostused_country = $people->_id;
        }
        $mostused_country_users = Accounts::where(['acct_location' => $mostused_country])->count();
        $mostused_per = round(($mostused_country_users / $total_accounts) * 100, 2);

        $user_report = Streamreports::count();
        $today_user_report_per = Streamreports::whereBetween(
            'rept_on',
            array(
                $todaystarts,
                $todayends,
            )
        )->count();
        $user_report_per = round(($today_user_report_per / $user_report) * 100, 2);

        $total_streams = Streams::count();
            $today_stream = Streams::whereBetween(
                'created_at',
                array(
                    $todaystarts,
                    $todayends,
                )
            )->count();
            if ($total_streams == 0) {
                $total_streams = 0;
                $today_stream_per = 0;
            } else {

                $today_stream_per = round(($today_stream / $total_streams) * 100, 2);
            }

        $val = array('total_accounts' => $total_accounts, 'female_per' => $female_per, 'random_chats' => $total_streams, 'random_per' => $random_per, 'subscribers' => $subscribers, 'total_transcations' => $total_transcations, 'membership_transcations' => $membership_transcations, 'gems_transcations' => $gems_transcations, 'total_countries' => $total_countries, 'mostused_country' => $mostused_country, 'mostused_per' => $mostused_per, 'membership_transt_per' => $membership_transt_per, 'gems_transt_per' => $gems_transt_per, 'today_transt_per' => $today_transt_per, 'sub_per' => $sub_per, 'user_report' => $user_report, 'user_report_per' => $user_report_per,'today_stream_per'=>$today_stream_per);

        return json_encode($val);
    }

    public function chart()
    {
        if (Auth::guest()) {
            return view('auth.login');
        } else {

            //subscription

            $data = Subscriptions::where('purchase_count', '<>', 0)
                ->orderBy('purchase_count', 'desc')
                ->limit(7)->get();

            $sub_title = array();
            $i = 0;
            foreach ($data as $key => $val) {
                $sub_title[$i] = $val['subs_title'];
                $i++;
            }

            $sub_data = array();
            $i = 0;
            foreach ($data as $key => $val) {
                $sub_data[$i] = $val['purchase_count'];
                $i++;
            }

            //gems

            $gemdata = Gems::where('purchase_count', '<>', 0)
                ->orderBy('purchase_count', 'desc')
                ->limit(7)->get();

            $gem_title = array();
            $j = 0;
            foreach ($gemdata as $key => $val) {
                $gem_title[$j] = $val['gem_title'];
                $j++;
            }

            $gem_data = array();
            $j = 0;
            foreach ($gemdata as $key => $val) {
                $gem_data[$j] = $val['purchase_count'];
                $j++;
            }

            //accounts

            for ($i = 7; $i >= 0; $i--) {
                $mystring[] = date("M d", strtotime("-" . $i . "days"));
                $year = date("Y", strtotime("-" . $i . "days"));
                $month = date("m", strtotime("-" . $i . "days"));
                $date = date("d", strtotime("-" . $i . "days"));
                $start = Carbon::create($year, $month, $date, 0, 0, 0);
                $end = Carbon::create($year, $month, $date, 23, 59, 59);

                $mvaluee[] = Accounts::where('acct_createdat', '>', $start)
                    ->where('acct_createdat', '<', $end)
                    ->where('acct_gender', '=', 'male')
                    ->count();

                $fvaluee[] = Accounts::where('acct_createdat', '>', $start)
                    ->where('acct_createdat', '<', $end)
                    ->where('acct_gender', '=', 'female')
                    ->count();
            }

            //transaction

            for ($i = 7; $i >= 0; $i--) {
                $tran_label[] = date("M d", strtotime("-" . $i . "days"));
                $year = date("Y", strtotime("-" . $i . "days"));
                $month = date("m", strtotime("-" . $i . "days"));
                $date = date("d", strtotime("-" . $i . "days"));
                $start = Carbon::create($year, $month, $date, 0, 0, 0);
                $end = Carbon::create($year, $month, $date, 23, 59, 59);

                $gvaluee[] = Payments::where('pymt_on', '>', $start)
                    ->where('pymt_on', '<', $end)
                    ->where('pymt_type', 'like', 'gems')
                    ->count();

                $svaluee[] = Payments::where('pymt_on', '>', $start)
                    ->where('pymt_on', '<', $end)
                    ->where('pymt_type', 'like', 'membership')
                    ->count();
            }

            return view('admin.chart', ['sub_title' => json_encode($sub_title), 'sub_data' => json_encode($sub_data), 'gem_title' => json_encode($gem_title), 'gem_data' => json_encode($gem_data), 'acc_date' => json_encode($mystring), 'macc_data' => json_encode($mvaluee), 'facc_data' => json_encode($fvaluee), 'gtran_data' => json_encode($gvaluee), 'stran_data' => json_encode($svaluee), 'tran_label' => json_encode($tran_label)]);
        }
    }

    public function notification()
    {
        if (Auth::guest()) {
            return view('auth.login');
        } else {
            return view('admin/notification');
        }
    }

    public function sendalert(Request $request, $dtype)
    {
        $myClass = new MyClass();

        if ($dtype == 'ios') {
            $this->validate(
                $request,
                [
                    'msg' => 'required',
                ],
                [
                    'msg.required' => trans('app.Enter some text.'),

                ]
            );

            $mes = $request->get('msg');
        } else {
            $this->validate(
                $request,
                [
                    'msgg' => 'required',
                ],
                [
                    'msgg.required' => trans('app.Enter some text.'),

                ]
            );

            $mes = $request->get('msgg');
        }

        if ($dtype == 'ios') {
            $devices = Devices::where('device_type', '0')->get();
        } else {
            $devices = Devices::where('device_type', '1')->get();
        }

        $devicetoken = array();
        foreach ($devices as $device) {
            array_push($devicetoken, $device->voip_token);
        }            
        
        if (!empty($devicetoken)) {

            
            if ($dtype == 'ios') {

                
                foreach ($devices as $device) {
                   if (!empty($device->fcm_token)){
                     $user_token = array();
                     array_push($user_token,$device->fcm_token);

                    $usernotification = $myClass->ios_pust_notification($user_token,$mes, 'all');
                }
                }

                
            }

            else
            {
                try {
                    $usernotification = $myClass->pust_notification($devicetoken, $mes, 'all');
                } catch (\Throwable $th) {
                    //throw $th;
                }
            }
            
            $message = new Adminchats();
            $message->msg_type = 'text';
            $message->msg_from = 'admin';
            $message->msg_platform = $dtype;
            $message->msg_to = 'all';
            $message->msg_data = $mes;
            $message->msg_at = time();
            $message->save();
        }

        return redirect(url()->previous());
    }
}
