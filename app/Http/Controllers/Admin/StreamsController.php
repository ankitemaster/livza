<?php
namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Streams;
use App\Models\Accounts;
use App\Models\Streamreports;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input as Input;
use Session;

class StreamsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (Auth::guest()) {
            return view('auth.login');
        }
        $filterby = $request->input('status');
        $active_status = 0;
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $sortby = $request->input('sort');
        $sortorder = $request->input('direction');
        
        if($filterby === "blocked"){
            $active_status = 1;
        }

        $paginate = Streams::where('active_status', $active_status)->paginate(10);

        if ($sortby && $sortorder) {
            $streams = Streams::where('active_status',$active_status);
            if($sortorder == 'asc')
            {
                $streams = $streams->orderBy($sortby,'asc')->paginate(10);
            }
            else
            {
                $streams = $streams->orderBy($sortby,'desc')->paginate(10);
            }
        }
        else{
            $streams = Streams::where('active_status',$active_status)->orderBy('created_at','desc')->paginate(10);
        }
        //$slice = array_slice($streams, $perPage * ($page - 1), $perPage);

        $reportcounts = array();
        if(!empty($streams)){
            foreach ($streams as $report) {
                $idd = new \MongoDB\BSON\ObjectID($report['_id']);
                $noofreports = Streamreports::where('stream_id', $idd)->count();
                $offset = (string)$idd;
               $reportcounts[$offset] = $noofreports;
            }
        }

        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder,'filterby'=>$filterby
        ));
        return view('admin.streams.index', ['streams' => $streams,'pagination'=>$pagination,'reportcounts'=>$reportcounts,'filterby'=>$filterby]);
    }

    public function search(Request $request)
    {   

        $filterby = $request->input('status');
        $active_status = 0;
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;

        $sortby = $request->input('sort');
        $sortorder = $request->input('direction');
        $search = Input::post('search');

        if($filterby === "blocked"){
            $active_status = 1;
        }

        if ($search) {
            $paginate = Streams::where('title', 'like', "%$search%")->where('active_status',$active_status)->paginate(10);
            $streams = Streams::where('title', 'like', "%$search%")->where('active_status',$active_status );
            if($sortorder == 'asc')
            {
                $streams = $streams->orderBy($sortby,'asc')->paginate(10);
            }
            else
            {
                $streams = $streams->orderBy($sortby,'desc')->paginate(10);
            }
        } else {
            $search = "";
            $paginate = Streams::where('active_status',$active_status)->paginate(10);
            if ($sortorder == 'asc') {
                $streams = Streams::where('active_status',$active_status)->orderBy($sortby,'asc')->paginate(10);
            } else {
                $streams = Streams::where('active_status',$active_status)->orderBy($sortby,'desc')->paginate(10);
            }
        }
        //$slice = array_slice($streams, $perPage * ($page - 1), $perPage);

        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder, 'search' => $search,'filterby'=>$filterby
        ));

        $reportcounts = array();
        if(!empty($streams)){
            foreach ($streams as $report) {
                $idd = new \MongoDB\BSON\ObjectID($report['_id']);
                $noofreports = Streamreports::where('stream_id', $idd)->count();
                $offset = (string)$idd;
               $reportcounts[$offset] = $noofreports;
            }
        }

        return view('admin.streams.index', ['streams' => $streams, 'pagination' => $pagination, 'search' => $search,'reportcounts'=>$reportcounts,'filterby'=>$filterby]);
    }

    public function changestatus($id, $status)
    {
        $acc = Streams::findOrFail($id);
        $acc->active_status = intval($status);
        $acc->save();
        if ($status == 0) {
            return redirect('admin/streams?status=active');
        } else {
            return redirect('admin/streams?status=blocked');
        }
    }

    public function userstreams($id, Request $request)
    {
        if (Auth::guest()) {
            return view('auth.login');
        }
        $publisher_id = new \MongoDB\BSON\ObjectID($id);
        $active_status = 0;
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $sortby = $request->input('sort');
        $sortorder = $request->input('direction');
        $user_acc = Accounts::find($publisher_id);
        

        $paginate = Streams::where('publisher_id', $publisher_id)->paginate(10);

        if ($sortby && $sortorder) {
            $streams = Streams::where('publisher_id',$publisher_id)->get();
            if($sortorder == 'asc')
            {
                $streams = $streams->sortBy($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
            else
            {
                $streams = $streams->sortByDesc($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
        }
        else{
            $streams = Streams::where('publisher_id',$publisher_id)->get()->sortByDesc('created_at',SORT_NATURAL|SORT_FLAG_CASE)->toArray();
        }
        $slice = array_slice($streams, $perPage * ($page - 1), $perPage);

        $reportcounts = array();
        if(!empty($streams)){
            foreach ($streams as $report) {
                $idd = new \MongoDB\BSON\ObjectID($report['_id']);
                $noofreports = Streamreports::where('stream_id', $idd)->count();
                $offset = (string)$idd;
               $reportcounts[$offset] = $noofreports;
            }
        }

        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder
        ));
        return view('admin.streams.index', ['streams' => $slice,'pagination'=>$pagination,'reportcounts'=>$reportcounts,'pid'=>$id,'usr_name'=>$user_acc->acct_name]);
    }

    public function streamsearch(Request $request)
    {   

        $active_status = 0;
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;

        $sortby = $request->input('sort');
        $sortorder = $request->input('direction');
        $search = Input::post('search');
        $id = new \MongoDB\BSON\ObjectID($request->input('pid'));
        $user_acc = Accounts::find($id);

        if ($search) {
            $paginate = Streams::where('title', 'like', "%$search%")->where('publisher_id',$id)->paginate(10);
            $streams = Streams::where('title', 'like', "%$search%")->where('publisher_id',$id )->orderBy('created_at', 'DESC')->get();
            if ($sortorder == 'asc') {
                $streams = $streams->sortBy($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            } else {
                $streams = $streams->sortByDesc($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            }
        } else {
            $search = "";
            $paginate = Streams::where('publisher_id',$id)->paginate(10);
            if ($sortorder == 'asc') {
                $streams = Streams::where('publisher_id',$id)->get()->sortBy($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            } else {
                $streams = Streams::where('publisher_id',$id)->get()->sortByDesc($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            }
        }
        $slice = array_slice($streams, $perPage * ($page - 1), $perPage);

        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder, 'search' => $search, 'id' => $id
        ));

        $reportcounts = array();
        if(!empty($streams)){
            foreach ($streams as $report) {
                $idd = new \MongoDB\BSON\ObjectID($report['_id']);
                $noofreports = Streamreports::where('stream_id', $idd)->count();
                $offset = (string)$idd;
               $reportcounts[$offset] = $noofreports;
            }
        }

        return view('admin.streams.index', ['streams' => $slice, 'pagination' => $pagination, 'search' => $search,'reportcounts'=>$reportcounts,'pid'=>$id,'usr_name'=>$user_acc->acct_name]);
    }
}