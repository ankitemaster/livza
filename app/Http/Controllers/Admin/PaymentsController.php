<?php
namespace App\Http\Controllers\Admin;

use Auth;
use App\Models\Payments;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input as Input;
use Session;

class PaymentsController extends Controller
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
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $sortby = $request->input('sort');
        $sortorder = $request->input('direction');

        $paginate=Payments::paginate(10);

        if ($sortby && $sortorder) {
            $transcation = Payments::get();
            if($sortorder == 'asc')
            {
                $transcations = $transcation->sortBy($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
            else
            {
                $transcations = $transcation->sortByDesc($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
        }
        else{
            $transcations = Payments::get()->sortByDesc('pymt_on',SORT_NATURAL|SORT_FLAG_CASE)->toArray();
        }
        $slice = array_slice($transcations, $perPage * ($page - 1), $perPage);

        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder,
        ));
        return view('admin.payments.index', ['transcations' => $slice,'pagination'=>$pagination]);
    }

    public function search(Request $request)
    {
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $search = Input::post('search');
        $sortby = Input::post('sort');
        $sortorder = Input::post('direction');

        
        $paginate=Payments::paginate(10);
        if ($search) {
            $temp = explode('-',$search);
            $start = Carbon::createFromDate($temp[0], $temp[1], $temp[2])->startOfDay();
            $end = Carbon::createFromDate($temp[0], $temp[1], $temp[2])->endOfDay();
            $paginate=Payments::whereBetween('pymt_on', array($start, $end))->paginate(10);
        }
        
        if ($search) {
            $transcation = Payments::whereBetween('pymt_on', array($start, $end))->get();
            if($sortorder == 'asc')
            {
                $transcations = $transcation->sortBy($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
            else
            {
                $transcations = $transcation->sortByDesc($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
        } else {
            $search = "";
            if($sortorder == 'asc')
            {
                $transcations = Payments::get()->sortBy($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
            else
            {
                $transcations = Payments::get()->sortByDesc($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
        }
        
        $slice = array_slice($transcations, $perPage * ($page - 1), $perPage);

        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder,'search' => $search,
        ));

        return view('admin.payments.index', ['transcations' => $slice,'pagination'=>$pagination,'search'=>$search]);
    }
}
