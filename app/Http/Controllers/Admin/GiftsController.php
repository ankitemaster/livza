<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gifts;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input as Input;
use Session;

class GiftsController extends Controller
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

        $paginate=Gifts::paginate(10);

        if ($sortby && $sortorder) {
            $gift = Gifts::get();
            if($sortorder == 'asc')
            {
                $gifts = $gift->sortBy($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
            else
            {
                $gifts = $gift->sortByDesc($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
        } else {
            $gifts = Gifts::get()->sortByDesc('created_at',SORT_NATURAL|SORT_FLAG_CASE)->toArray();
        }

        $slice = array_slice($gifts, $perPage * ($page - 1), $perPage);

        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder,
        ));

        return view('admin.gifts.index', ['gifts' => $slice,'pagination'=>$pagination]);
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
        return view('admin.gifts.create');
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
                'val-gifttitle' => 'required|min:3|max:30',
                'val-primegemscount' => 'required|integer|between:1,999999',
                'val-gemscount' => 'required|integer|between:1,999999',
                'val-icon' => 'required|image|mimes:png|max:2000'
            ],
            [
                'val-primegemscount.required' => trans('app.Gems count is required'),
                'val-gemscount.required' => trans('app.Gems count is required'),
                'val-primegemscount.integer' => trans('app.Please provide valid gems count'),
                'val-primegemscount.between' => trans('app.Please provide valid gems count'),
                'val-gemscount.integer' => trans('app.Please provide valid gems count'),
                'val-gemscount.between' => trans('app.Please provide gems count from 1 - 999999'),
                'val-primegemscount.between' => trans('app.Please provide valid gems count'),
                'val-gifttitle.required' => trans('app.Title is required'),
                'val-gifttitle.min' => trans('app.Title must be at least 3 characters.'),
                'val-gifttitle.max' => trans('app.Title may not be greater than 30 characters.'),
                'val-icon.required' => trans('app.Please upload gift icon.'),
                'val-icon.image' => trans('app.Invalid file format. Please Upload an png file'),
                'val-icon.mimes' => trans('app.Invalid file format. Please Upload an png file'),
            ]
        );

        $gift = new Gifts();
        $gift->gft_title = $request->get('val-gifttitle');
        $gift->gft_gems_prime = intval($request->get('val-primegemscount'));
        $gift->gft_gems = intval($request->get('val-gemscount'));

        if ($request->file('val-icon')) {
            $image = $request->file('val-icon');
            $name = time() . $image->getClientOriginalName();
            $image->move(public_path() . '/img/gifts/', $name);
            $data = $name;
            $gift->gft_icon = $data;
        }

        $gift_title = $request->get('val-gifttitle');
        $giftExists = Gifts::where('gft_title', $gift_title)->count();

        if ($giftExists > 0) {

            $notification = array(
                'message' => 'Gift already exists',
                'alert-type' => 'error',
            );
        } else {

            if ($gift->save()) {
                $notification = array(
                    'message' => 'New Gift Created',
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

        return redirect('admin/gifts');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Gifts  $gifts
     * @return \Illuminate\Http\Response
     */
    public function show(Gifts $gifts)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Gifts  $gifts
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $gift = Gifts::find($id);
        return view('admin.gifts.edit', ['gift' => $gift, 'id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Gifts  $gifts
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'val-gifttitle' => 'required|min:3|max:30',
                'val-primegemscount' => 'required|integer|between:1,999999',
                'val-gemscount' => 'required|integer|between:1,999999',
                'val-icon' => 'image|mimes:png|max:2000'
            ],
            [
                'val-primegemscount.required' => trans('app.Gems count is required'),
                'val-gemscount.required' => trans('app.Gems count is required'),
                'val-primegemscount.integer' => trans('app.Please provide valid gems count'),
                'val-primegemscount.between' => trans('app.Please provide valid gems count'),
                'val-gemscount.integer' => trans('app.Please provide valid gems count'),
                'val-gemscount.between' => trans('app.Please provide valid gems count'),
                'val-gifttitle.required' => trans('app.Title is required'),
                'val-gifttitle.min' => trans('app.Title must be at least 3 characters.'),
                'val-gifttitle.max' => trans('app.Title may not be greater than 30 characters.'),
                'val-icon.image' => trans('app.Invalid file format. Please Upload an png file'),
            ]
        );

        $giftExists = Gifts::where('_id', '!=', $id)->where('gft_title', $request->get('val-gifttitle'))->count();

        if ($giftExists > 0) {
            $notification = array(
                'message' => 'Gift already exists',
                'alert-type' => 'error',
            );
        } else {
            $gift = Gifts::findOrFail($id);
            $gift->gft_title = $request->get('val-gifttitle');
            $gift->gft_gems_prime = intval($request->get('val-primegemscount'));
            $gift->gft_gems = intval($request->get('val-gemscount'));

            if ($request->file('val-icon')) {
                $image = $request->file('val-icon');
                $name = time() . $image->getClientOriginalName();
                $image->move(public_path() . '/img/gifts/', $name);
                $data = $name;
                $gift->gft_icon = $data;
            }

            if ($gift->save()) {
                $notification = array(
                    'message' => 'Gift updated',
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
        return redirect('admin/gifts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Gifts  $gifts
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $gift = Gifts::find($id);
        if ($gift->delete()) {
            $notification = array(
                'message' => 'Gift deleted',
                'alert-type' => 'success',
            );
        } else {
            $notification = array(
                'message' => 'Something went wrong',
                'alert-type' => 'error',
            );
        }

        session()->put('notification', $notification);
        return redirect('admin/gifts');
    }

    public function search(Request $request)
    {

        $search = Input::post('search');
        $sortby = $request->input('sort');
        $sortorder = $request->input('direction');

        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        
        if ($search) {
            $paginate=Gifts::where('gft_title', 'like', "%$search%")->paginate(10);

            $gift = Gifts::where('gft_title', 'like', "%$search%")->get();
            if($sortorder == 'asc')
            {
                $gifts = $gift->sortBy($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
            else
            {
                $gifts = $gift->sortByDesc($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
        } else {
            $paginate=Gifts::paginate(10);
            $search = "";
            $gifts = Gifts::get()->sortByDesc($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
        }

        $slice = array_slice($gifts, $perPage * ($page - 1), $perPage);

        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder,'search' => $search
        ));

        return view('admin.gifts.index', ['gifts' => $slice,'pagination'=>$pagination,'search' => $search]);
    }
}
