<?php
namespace App\Http\Controllers\Admin;

use App\Models\Accounts;
use App\Models\Reports;
use App\Models\Settings;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input as Input;
use Session;

class ReportsController extends Controller
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

        $totalreports = Reports::groupBy('user_id')->get();

        $totalreports_count = count($totalreports);

        $reports = Reports::raw(function ($collection) use ($page, $perPage) {
            return $collection->aggregate([
                [
                    '$sort' => [
                        'rept_on' => -1,
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

        $pagination = new \Illuminate\Pagination\LengthAwarePaginator($reports, $totalreports_count, $perPage, $page, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
        ]);

        $reportcounts = array();
        foreach ($reports as $report) {

            $noofreports = Reports::where('user_id', $report->user_id)->count();
            $reportcounts[$report->user_id] = $noofreports;
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
        $report = Reports::where('user_id', $id);
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
            $user = Accounts::where('acct_name', 'like', "%$search%")->first();
            $user_id = $user['_id'];
            $totalreports = Reports::where('user_id', $user_id)->groupBy('user_id')->get();
            $totalreports_count = count($totalreports);
            $reports = Reports::raw(function ($collection) use ($page, $perPage,$user_id) {
                return $collection->aggregate([
                    [
                        '$sort' => [
                            'rept_on' => -1,
                        ],
                    ],
                    [
                        '$match' => [
                            'user_id' => $user_id,
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
        else{
            $totalreports = Reports::groupBy('user_id')->get();
            $totalreports_count = count($totalreports);
            $reports = Reports::raw(function ($collection) use ($page, $perPage) {
                return $collection->aggregate([
                    [
                        '$sort' => [
                            'rept_on' => -1,
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
            $noofreports = Reports::where('user_id', $report->user_id)->count();
            $reportcounts[$report->user_id] = $noofreports;
        }
        return view('admin.reports.reportlist', ['reports' => $reports, 'reportcounts' => $reportcounts, 'pagination' => $pagination]);
        
    }

    public function show($id)
    {
        $reports = Reports::where('user_id', $id)->orderBy('rept_on', 'desc')->get();
        
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
}
