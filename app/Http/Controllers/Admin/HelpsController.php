<?php
namespace App\Http\Controllers\Admin;
use App\Models\Helps;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input as Input;
use Session;
class HelpsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $sortby = Input::post('sort');
        $sortorder = Input::post('direction');
        $paginate=Helps::paginate(10);
        if ($sortby && $sortorder) {
            $help = Helps::get();
            if($sortorder == 'asc')
            {
                $helps = $help->sortBy($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
            else
            {
                $helps = $help->sortByDesc($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
        } else {
            $helps = Helps::get()->sortByDesc('updated_at',SORT_NATURAL|SORT_FLAG_CASE)->toArray();
        }
        $slice = array_slice($helps, $perPage * ($page - 1), $perPage);

        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder,
        ));

        return view('admin.helps.index', ['helps' => $slice,'sortby' => $sortby, 'sortorder' => $sortorder,'pagination'=>$pagination]);
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
        }
        return view('admin.helps.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //print_r($request->summernoteInput);exit;
        $this->validate(
            $request,
            [
                'val-title' => 'required|min:3|max:30|unique:helps,help_title,',
                'summernoteInput' => 'required',
            ],
            [
                'summernoteInput.required' => trans('app.Description is required'),
                'val-title.required' => trans('app.Title is required'),
                'val-title.min' => trans('app.Title must be at least 3 characters.'),
                'val-title.max' => trans('app.Title may not be greater than 30 characters.'),
                'val-title.unique' => trans('app.Query with this title already added'),
            ]
        );
        $helpExists = Helps::where('help_title', 'like', $request->get('val-title'))->count();
        if ($helpExists > 0) {
            $notification = array(
                'message' => 'Help title already exists',
                'alert-type' => 'error',
            );
        } else {
            $help = new Helps();
            $help->help_title = $request->get('val-title');
            $help->help_descrip = $request->summernoteInput;
            if ($help->save()) {
                $notification = array(
                    'message' => 'New help query added successfully',
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
        return redirect('admin/helps');
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Helps  $helps
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $helps = Helps::find($id);
        if(!empty($helps)){
            return view('admin.helps.show', ['helps' => $helps]);
        }
        else{
            return view('errors.404');
        }
        
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Helps  $helps
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $help = Helps::find($id);
        return view('admin.helps.edit', ['help' => $help, 'id' => $id]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Helps  $helps
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'val-title' => 'required|min:3|max:30|unique:helps,help_title,' . $id . ',_id',
                'summernoteInput' => 'required',
            ],
            [
                'summernoteInput.required' => trans('app.Description is required'),
                'val-title.required' => trans('app.Title is required'),
                'val-title.min' => trans('app.itle must be at least 3 characters.'),
                'val-title.max' => trans('app.Title may not be greater than 30 characters.'),
                'val-title.unique' => trans('app.Query with this title already added'),
            ]
        );
        $helpExists = Helps::where('_id', '!=', $id)->where('help_title', 'like', $request->get('val-title'))->count();
        if ($helpExists > 0) {
            $notification = array(
                'message' => 'Help title already exists',
                'alert-type' => 'error',
            );
        } else {
            $help = Helps::findOrFail($id);
            $help->help_title = $request->get('val-title');
            $help->help_descrip = $request->summernoteInput;
            if ($help->save()) {
                $notification = array(
                    'message' => 'Help query updated successfully',
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
        return redirect('admin/helps');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Helps  $helps
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $help = Helps::find($id);
        if ($help->delete()) {
            $notification = array(
                'message' => 'Help query deleted successfully',
                'alert-type' => 'success',
            );
        } else {
            $notification = array(
                'message' => 'Something went wrong',
                'alert-type' => 'error',
            );
        }
        session()->put('notification', $notification);
        return redirect('admin/helps');
    }
    public function search()
    {
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $search = Input::post('search');
        $sortby = Input::post('sort');
        $sortorder = Input::post('direction');

        if ($search) {
            $paginate=Helps::where('help_title', 'like', "%$search%")
            ->paginate(10);

            $help = Helps::where('help_title', 'like', "%$search%")
                ->get();
            if($sortorder == 'asc')
            {
                $helps = $help->sortBy($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
            else
            {
                $helps = $help->sortByDesc($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
        } else {
            $search = "";
            $paginate=Helps::paginate(10);
            $helps = Helps::get()->sortByDesc('updated_at',SORT_NATURAL|SORT_FLAG_CASE)->toArray();
        }
        $slice = array_slice($helps, $perPage * ($page - 1), $perPage);

        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder,'search' => $search
        ));
        return view('admin.helps.index', ['helps' => $slice,'sortby' => $sortby, 'sortorder' => $sortorder,'pagination'=>$pagination,'search' => $search]);
    }
}
