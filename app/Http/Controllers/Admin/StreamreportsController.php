<?php
namespace App\Http\Controllers\Admin;

use App\Models\Accounts;
use App\Models\Streamreports;
use App\Models\Streams;
use App\Models\Settings;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input as Input;
use Session;

class StreamreportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function reportlist(Request $request)
    {

        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();

        $sortby = $request->input('sort');
        $sortorder = $request->input('direction');

        $perPage = 10;

        $totalreports = Streamreports::groupBy('stream_id')->get();

        $totalreports_count = count($totalreports);

        $reports = Streamreports::raw(function ($collection) use ($page, $perPage) {
            return $collection->aggregate([
                [
                    '$sort' => [
                        'rept_on' => -1,
                    ],
                ],
                [
                    '$group' => [
                        '_id' => '$stream_id',
                        'gps' => [
                            '$push' => '$$ROOT',
                        ],
                    ],
                ],
                [
                    '$replaceRoot' => [
                        'newRoot' => [
                            '$arrayElemAt' => [
                                '$gps', 0,
                            ],
                        ],
                    ],
                ],
                ['$skip' => ($page - 1) * $perPage],
                ['$limit' => $perPage],
            ]);
        });

        $pagination = new \Illuminate\Pagination\LengthAwarePaginator($reports, $totalreports_count, $perPage, $page, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
        ]);

        $reportcounts = array();
        foreach ($reports as $report) {
            $idd = new \MongoDB\BSON\ObjectID($report->stream_id);
            $noofreports = Streamreports::where('stream_id', $idd)->count();
            $offset = (string)$idd;
           $reportcounts[$offset] = $noofreports;
        }

        return view('admin.reports.reportlist', ['reports' => $reports, 'reportcounts' => $reportcounts, 'pagination' => $pagination]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::guest()) {
            return view('auth.login');
        } else {
            $set_det = Settings::orderBy('_id', 'desc')->first();
            $titlee = $set_det->report_titles;
            foreach ($titlee as $key => $value) {
                $report[] = $value['title'];
            }
            $title = $report;
        }
        return view('admin.reports.create', ['title' => $title]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $setting = Settings::orderBy('_id', 'desc')->first();
        $this->validate(
            $request,
            [
                'summernoteInput' => 'required',
            ],
            [
                'summernoteInput.required' => trans('app.Please enter report titles'),
            ]
        );
        $tit = $request->summernoteInput;
        $temp = explode(',', $tit);

        $titleStack = array();
        foreach ($temp as $key => $value) {
            if ($value != "" && $value != null && $value != "undefined") {
                $title[$key]['title'] = $value;
                array_push($titleStack, $value);
            }
        }

        $titleStack = array_filter($titleStack);

        if (count($titleStack) == 0) {
            $notification = array(
                'message' => 'Enter a valid report titles',
                'alert-type' => 'error',
            );
            session()->put('notification', $notification);
            return redirect('admin/reports/create');
        }

        if (count($titleStack) !== count(array_unique($titleStack))) {
            $notification = array(
                'message' => 'Report title already exists',
                'alert-type' => 'error',
            );
            session()->put('notification', $notification);
            return redirect('admin/reports/create');
        } else {
            $setting->report_titles = $title;
            $setting->save();
            $notification = array(
                'message' => 'Report titles updated',
                'alert-type' => 'success',
            );
            session()->put('notification', $notification);
            return redirect('admin/reports/create');
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Reportdescrip  $reportdescrip
     * @return \Illuminate\Http\Response
     */
    public function reportdestroy($id)
    {
        $idd = new \MongoDB\BSON\ObjectID($id);
        $report = Streamreports::where('stream_id', $idd);
        if ($report->delete()) {
            $notification = array(
                'message' => 'User\'s Report deleted',
                'alert-type' => 'success',
            );
        } else {
            $notification = array(
                'message' => 'Something went wrong',
                'alert-type' => 'error',
            );
        }
        session()->put('notification', $notification);
        return redirect('admin/reports/reportlist');
    }
    public function reportsearch()
    {
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();

        $perPage = 10;

        $search = Input::post('search');

        if($search){
            $stream = Streams::where('title', 'like', "%$search%")->first();
            $stream_id = new \MongoDB\BSON\ObjectID($stream['_id']);
            $totalreports = Streamreports::where('stream_id', $stream_id)->groupBy('stream_id')->get();
            $totalreports_count = count($totalreports);
            $reports = Streamreports::raw(function ($collection) use ($page, $perPage,$stream_id) {
                return $collection->aggregate([
                    [
                        '$sort' => [
                            'reported_at' => -1,
                        ],
                    ],
                    [
                        '$match' => [
                            'stream_id' => $stream_id,
                        ],
                    ],
                    [
                        '$group' => [
                            '_id' => '$stream_id',
                            'gps' => [
                                '$push' => '$$ROOT',
                            ],
                        ],
                    ],
                    [
                        '$replaceRoot' => [
                            'newRoot' => [
                                '$arrayElemAt' => [
                                    '$gps', 0,
                                ],
                            ],
                        ],
                    ],
                    ['$skip' => ($page - 1) * $perPage],
                    ['$limit' => $perPage],
                ]);
            });
        }
        else{
            $totalreports = Streamreports::groupBy('user_id')->get();
            $totalreports_count = count($totalreports);
            $reports = Streamreports::raw(function ($collection) use ($page, $perPage) {
                return $collection->aggregate([
                    [
                        '$sort' => [
                            'reported_at' => -1,
                        ],
                    ],
                    [
                        '$group' => [
                            '_id' => '$user_id',
                            'gps' => [
                                '$push' => '$$ROOT',
                            ],
                        ],
                    ],
                    [
                        '$replaceRoot' => [
                            'newRoot' => [
                                '$arrayElemAt' => [
                                    '$gps', 0,
                                ],
                            ],
                        ],
                    ],
                    ['$skip' => ($page - 1) * $perPage],
                    ['$limit' => $perPage],
                ]);
            });

        }

        $pagination = new \Illuminate\Pagination\LengthAwarePaginator($reports, $totalreports_count, $perPage, $page, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
        ]);

        $reportcounts = array();
        foreach ($reports as $report) {
            $idd = new \MongoDB\BSON\ObjectID($report->stream_id);
            $noofreports = Streamreports::where('stream_id', $idd)->count();
            $offset = (string)$idd;
           $reportcounts[$offset] = $noofreports;
        }

        return view('admin.reports.reportlist', ['reports' => $reports, 'reportcounts' => $reportcounts, 'pagination' => $pagination, 'search' => $search,]);
        
    }

    public function show($id)
    {
        $idd = new \MongoDB\BSON\ObjectID($id);
        $reports = Streamreports::where('stream_id', $idd)->orderBy('created_at', 'desc')->get();
        if(!empty(count($reports)))
        {
            return view('admin.reports.show', ['reports' => $reports,'id' => $id]);
        }
        else{
            return view('errors.404');
        }
        
    }
    public function changestatus($id,$status,$rid)
    {
        $acc = Accounts::findOrFail($id);
        $acc->acct_status = intval($status);
        $acc->save();
        return redirect('admin/reports/show/'.$rid);
    }
    public function changestatuslist($id,$status)
    {
        $acc = Accounts::findOrFail($id);
        $acc->acct_status = intval($status);
        $acc->save();
        return redirect('admin/reports/reportlist');
    }
    public function changestreamstatus($id,$status,$rid)
    {
        $acc = Streams::findOrFail($id);
        $acc->active_status = intval($status);
        $acc->save();
        return redirect('admin/reports/show/'.$rid);
    }
}
