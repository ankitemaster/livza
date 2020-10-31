<?php

namespace App\Http\Controllers\Admin;

use App\Classes\MyClass;
use App\Models\Accounts;
use App\Models\Adminchats;
use App\Models\Devices;
use App\Models\Followings;
use App\Models\Streams;
use App\Models\Videochats;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input as Input;

class AccountsController extends Controller
{
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */

        public function __construct()
        {
            $this->middleware('auth');
        }

        public function index(Request $request)
        {

            $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
            $perPage = 10;
            $sortby = $request->input('sort');
            $sortorder = $request->input('direction');
            $search_for = "acct_name";
            $paginate = Accounts::where('acct_status', 1)->paginate(10);
            if ($sortby && $sortorder) {
                $account = Accounts::where('acct_status', 1)->get();
                if ($sortorder == 'asc') {
                    $accounts = $account->sortBy($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
                } else {
                    $accounts = $account->sortByDesc($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
                }
            } else {
                $accounts = Accounts::where('acct_status', 1)->get()->sortByDesc('acct_createdat', SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            }
            $slice = array_slice($accounts, $perPage * ($page - 1), $perPage);
            $pagination = $paginate->appends(array(
                'sort' => $sortby, 'direction' => $sortorder,'search_for' => $search_for
            ));
            return view('admin.accounts.index', ['accounts' => $slice, 'pagination' => $pagination,'search_for' => $search_for]);
        }

        public function pending(Request $request)
        {
            $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
            $perPage = 10;
            $sortby = $request->input('sort');
            $sortorder = $request->input('direction');
            $search_for = "acct_name";

            $paginate = Accounts::where('acct_status', '<>', 1)->paginate(10);
            if ($sortby && $sortorder) {
                $account = Accounts::where('acct_status', '<>', 1)->get();
                if ($sortorder == 'asc') {
                    $accounts = $account->sortBy($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
                } else {
                    $accounts = $account->sortByDesc($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
                }
            } else {
                $accounts = Accounts::where('acct_status', '<>', 1)->get()->sortByDesc('acct_createdat', SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            }

            $slice = array_slice($accounts, $perPage * ($page - 1), $perPage);

            $pagination = $paginate->appends(array(
                'sort' => $sortby, 'direction' => $sortorder,'search_for' => $search_for
            ));

            return view('admin.accounts.pending', ['accounts' => $slice, 'pagination' => $pagination,'search_for' => $search_for]);
        }

        public function search(Request $request)
        {
            $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
            $perPage = 10;
            $search = Input::post('search');
            $status = intval(Input::post('status'));
            $sortby = $request->input('sort');
            $sortorder = $request->input('direction');
            $search_for = $request->input('search_for');
            if(!$search_for){
                $search_for = "acct_name";
            }
            if ($search) {
                if($search_for === "acct_phoneno" ){
                    $search = intval($search);
                }
                if ($status == 0) {
                    if($search_for === "acct_phoneno" ){
                        $paginate = Accounts::where('acct_status', '!=', 1)->where($search_for,$search)->paginate(10);
                        $account = Accounts::where($search_for,$search)
                        ->where('acct_status', '!=', 1)
                        ->get();
                    }
                    else
                    {
                        $paginate = Accounts::where('acct_status', '!=', 1)->where($search_for, 'like', "%$search%")->paginate(10);
                        $account = Accounts::where($search_for, 'like', "%$search%")
                        ->where('acct_status', '!=', 1)
                        ->get();
                    }
                    if ($sortorder == 'asc') {
                        $accountss = $account->sortBy($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
                    } else {
                        $accountss = $account->sortByDesc($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
                    }
                } else {
                    if($search_for === "acct_phoneno" ){
                        $paginate = Accounts::where('acct_status', $status)->where($search_for, $search)->paginate(10);
                        $account = Accounts::where($search_for,$search)
                        ->where('acct_status', $status)
                        ->get();
                    }else{
                       $paginate = Accounts::where('acct_status', $status)->where($search_for, 'like', "%$search%")->paginate(10);
                       $account = Accounts::where($search_for, 'like', "%$search%")
                       ->where('acct_status', $status)
                       ->get();
                   }
                   if ($sortorder == 'asc') {
                    $accountss = $account->sortBy($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
                } else {
                    $accountss = $account->sortByDesc($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
                }
            }
        } else {
            $search = "";
            $paginate = Accounts::where('acct_status', $status)->paginate(10);
            $account = Accounts::where('acct_status', $status)->get();
            if ($sortorder == 'asc') {
                $accountss = $account->sortBy($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            } else {
                $accountss = $account->sortByDesc($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            }
        }
        $accounts = array_slice($accountss, $perPage * ($page - 1), $perPage);
        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder, 'search' => $search, 'status' => $status,'search_for' => $search_for
        ));
        if ($status == 1) {
            return view('admin.accounts.index', compact(['accounts', 'search', 'sortby', 'sortorder', 'pagination','search_for']));
        } else {
            return view('admin.accounts.pending', compact(['accounts', 'search', 'sortby', 'sortorder', 'pagination', 'search_for']));
        }
    }

        /**
         * Show the form for creating a new resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function getcount()
        {
            $acct_count = Accounts::count();
            return $acct_count;
        }

        public function changestatus($id, $status)
        {
            $active_stream = 1;
            $acc = Accounts::findOrFail($id);
            $acc->acct_status = intval($status);
            $acc->save();
            /* disabling the user streams */
            if ($status === "1") {
                $active_stream = 0;
            }
            $userstreams = Streams::where('publisher_id', new \MongoDB\BSON\ObjectID($id))->get();
            foreach ($userstreams as $stream) {
                $str = Streams::findOrFail($stream->_id);
                $str->active_status = $active_stream;
                $str->save();
            }
            if ($status == 0) {
                return redirect('admin/accounts/pending');
            } else {
                return redirect('admin/accounts');
            }
        }

        /**
         * Store a newly created resource in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            //
        }

        /**
         * Display the specified resource.
         *
         * @param  \App\Accounts  $accounts
         * @return \Illuminate\Http\Response
         */
        public function show($id)
        {
            $user_acc = Accounts::find($id);
            if (!empty($user_acc)) {
                $idd = new \MongoDB\BSON\ObjectID($id);
                $follower = Followings::where('user_id', $idd)->paginate(10);
                $followers = array();
                foreach ($follower as $value) {
                    $followers[] = Accounts::where('_id', $value->follower_id)->first();
                }
                $followers_count = Followings::where('user_id', $idd)->count();
                $following = Followings::where('follower_id', $idd)->paginate(10);
                $followings = array();
                foreach ($following as $value) {
                    $followings[] = Accounts::where('_id', $value->user_id)->first();
                }
                $followings_count = Followings::where('follower_id', $idd)->count();
                $stream_count = Streams::where('publisher_id', $idd)->count();
                $accounts = Accounts::find($idd);
                $device = Devices::where('user_id', '=', $id)->orderBy('notified_at', 'DESC')->paginate(10);
                $videocall = Videochats::where('user_id', '=', $id)->orderBy('lastchat_on', 'DESC')->paginate(10);
                $usrname = array();
                foreach ($videocall as $value) {
                    $usr = Accounts::where('_id', $value->partner_id)->first();
                    $usrname[$value->partner_id] = $usr->acct_name;
                }
                return view('admin.accounts.show', ['accounts' => $accounts, 'followings_count' => $followings_count, 'followers_count' => $followers_count, 'followings' => $followings, 'followers' => $followers, 'device' => $device, 'following' => $following, 'follower' => $follower, 'videocall' => $videocall, 'usrname' => $usrname,'stream_count'=>$stream_count]);
            } else {
                return view('errors.404');
            }
        }

        public function sendalert(Request $request, $id)
        {

            $this->validate(
                $request,
                [
                    'msg' => 'required',
                ],
                [
                    'msg.required' => trans('app.Enter some text.'),
                ]
            );
            $device = Devices::where('user_id', $id)->orderBy('notified_at', 'desc')->first();
            if (!empty($device)) {
                $myClass = new MyClass();
                $mes = $request->get('msg');
                if ($device->device_type == "1") {
                    try {
                        $usernotification = $myClass->pust_notification($device->device_token, $mes);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                } else {    
                   $user_token = array();
                   array_push($user_token,$device->fcm_token);
                   $usernotification = $myClass->ios_pust_notification($user_token,$mes);
               }
               $message = new Adminchats();
               $message->msg_type = 'text';
               $message->msg_from = 'admin';
               $message->msg_to = $id;
               $message->msg_data = $mes;
               $message->msg_at = time();
               $message->save();
           }
           return redirect(url()->previous());
       }

       public function follow($type, $id)
       {

        $idd = new \MongoDB\BSON\ObjectID($id);
        $accounts = Accounts::find($idd);
        if ($type == 'follower') {
            $follower = Followings::where('user_id', $idd)->paginate(10);
            $followers = array();
            foreach ($follower as $value) {
                $followers[] = Accounts::where('_id', $value->follower_id)->first();
            }
            $followers_count = Followings::where('user_id', $idd)->count();
            return view('admin.accounts.follow', ['accounts' => $accounts, 'followers_count' => $followers_count, 'data' => $followers, 'datarender' => $follower, 'type' => $type]);
        } else {
            $following = Followings::where('follower_id', $idd)->paginate(10);
            $followings = array();
            foreach ($following as $value) {
                $followings[] = Accounts::where('_id', $value->user_id)->first();
            }
            $followings_count = Followings::where('follower_id', $idd)->count();
            return view('admin.accounts.follow', ['accounts' => $accounts, 'followings_count' => $followings_count, 'data' => $followings, 'datarender' => $following, 'type' => $type]);
        }

    }
}
