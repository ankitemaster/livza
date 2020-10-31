<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gems;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input as Input;
use Session;

class GemsController extends Controller
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

        $paginate = Gems::paginate(10);

        if ($sortby && $sortorder) {
            $gem = Gems::get();
            if ($sortorder == 'asc') {
                $gems = $gem->sortBy($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            } else {
                $gems = $gem->sortByDesc($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            }
        } else {
            $gems = Gems::get()->sortByDesc('created_at', SORT_NATURAL | SORT_FLAG_CASE)->toArray();
        }

        $slice = array_slice($gems, $perPage * ($page - 1), $perPage);

        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder,
        ));

        return view('admin.gems.index', ['gems' => $slice, 'pagination' => $pagination]);
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
        return view('admin.gems.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'val-gemstitle' => 'required|string|min:3|max:30|unique:gems,gem_title,',
                'val-gemscount' => 'required|integer|between:1,999999|unique:gems,gem_count',
                'val-price' => 'required|between:1,999999',
                'val-icon' => 'required|image|mimes:jpeg,png,jpg|max:2000',
            ],
            [
                'val-gemstitle.min' => trans('app.Package name must be at least 3 characters.'),
                'val-gemstitle.required' => trans('app.Please provide gem package name.'),
                'val-gemscount.required' => trans('app.Please provide gems count.'),
                'val-price.required' => trans('app.Please provide gem package price.'),
                'val-icon.required' => trans('app.Please upload gem icon.'),
                'val-icon.image' => trans('app.Invalid file format. Please Upload an image file'),

                'val-gemstitle.max' => trans('app.Package name may not be greater than 30 characters.'),
                'val-gemstitle.unique' => trans('app.Package name already exists'),
                'val-gemscount.unique' => trans('app.The package with this gems already added'),
                'val-gemscount.integer' => trans('app.Please provide valid gems count'),
                'val-price.float' => trans('app.Please provide valid price'),
                'val-gemscount.between' => trans('app.Please provide valid gems count'),
                'val-price.between' => trans('app.Please provide valid price'),
            ]
        );

        $platform = trim($request->get('val-gemstitle'));
        $gemExists = Gems::where('gem_title', 'like', $request->get('val-gemstitle'))->where('platform', $platform)->count();

        if ($gemExists > 0) {
            $notification = array(
                'message' => 'Package name already exists',
                'alert-type' => 'error',
            );
        } else {

            $gem = new Gems();
            $gem->gem_title = $request->get('val-gemstitle');
            $gem->gem_count = $request->get('val-gemscount');
            $gem->gem_price = $request->get('val-price');
            $gem->platform = $request->get('val-platform');
            if ($request->file('val-icon')) {
                $image = $request->file('val-icon');
                $name = time() . $image->getClientOriginalName();
                $image->move(public_path() . '/img/gems/', $name);
                $data = $name;
                $gem->gem_icon = $data;
            }

            if ($gem->save()) {
                $notification = array(
                    'message' => 'New Gem Package Created',
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
        return redirect('admin/gems');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Gems  $gems
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $gem = Gems::find($id);
        if (!empty($gem)) {
            return view('admin.gems.show', ['gem' => $gem]);
        } else {
            return view('errors.404');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Gems  $gems
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gem = Gems::find($id);
        return view('admin.gems.edit', ['gem' => $gem, 'id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Gems  $gems
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'val-gemstitle' => 'required|unique:gems,gem_title,' . $id . ',_id',
                'val-gemscount' => 'required|unique:gems,gem_count,' . $id . ',_id',
                'val-price' => 'required|between:1,999999',
                'val-icon' => 'image|mimes:jpeg,png,jpg|max:2000',
            ],
            [
                'val-gemstitle.unique' => trans('app.Package name already exists'),
                'val-gemscount.unique' => trans('app.The package with this gems already added'),
                'val-gemstitle.required' => trans('app.Please provide gem package name.'),
                'val-gemscount.required' => trans('app.Please provide gems count.'),
                'val-price.required' => trans('app.Please provide gem package price.'),
                'val-icon.image' => trans('app.Invalid file format. Please Upload an image file'),
            ]
        );

        $gem = Gems::findOrFail($id);
        $gem->gem_title = $request->get('val-gemstitle');
        $gem->gem_count = $request->get('val-gemscount');
        $gem->gem_price = $request->get('val-price');
        $gem->platform = $request->get('val-platform');

        if ($request->file('val-icon')) {
            $image = $request->file('val-icon');
            $name = time() . $image->getClientOriginalName();
            $image->move(public_path() . '/img/gems/', $name);
            $data = $name;
            $gem->gem_icon = $data;
        }

        if ($gem->save()) {
            $notification = array(
                'message' => 'Gem Package updated',
                'alert-type' => 'success',
            );
        } else {
            $notification = array(
                'message' => 'Something went wrong',
                'alert-type' => 'error',
            );
        }
        session()->put('notification', $notification);
        return redirect('admin/gems');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Gems  $gems
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gem = Gems::find($id);
        if ($gem->delete()) {
            $notification = array(
                'message' => 'Gem package deleted',
                'alert-type' => 'success',
            );
        } else {
            $notification = array(
                'message' => 'Something went wrong',
                'alert-type' => 'error',
            );
        }

        session()->put('notification', $notification);
        return redirect('admin/gems');
    }

    public function search(Request $request)
    {
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;

        $sortby = $request->input('sort');
        $sortorder = $request->input('direction');
        $search = Input::post('search');

        if ($search) {
            $paginate = Gems::where('gem_title', 'like', "%$search%")->paginate(10);
            $gem = Gems::where('gem_title', 'like', "%$search%")->orderBy('created_at', 'DESC')->get();
            if ($sortorder == 'asc') {
                $gems = $gem->sortBy($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            } else {
                $gems = $gem->sortByDesc($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            }
        } else {
            $search = "";
            $paginate = Gems::paginate(10);
            if ($sortorder == 'asc') {
                $gems = Gems::get()->sortBy($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            } else {
                $gems = Gems::get()->sortByDesc($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            }
        }
        $slice = array_slice($gems, $perPage * ($page - 1), $perPage);

        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder, 'search' => $search,
        ));

        return view('admin.gems.index', ['gems' => $slice, 'pagination' => $pagination, 'search' => $search]);
    }
}
