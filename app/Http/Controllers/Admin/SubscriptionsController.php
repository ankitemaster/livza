<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subscriptions;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input as Input;
use Session;

class SubscriptionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $sortby = $request->input('sort');
        $sortorder = $request->input('direction');

        $paginate = Subscriptions::paginate(10);

        if ($sortby && $sortorder) {
            $subs = Subscriptions::get();
            if ($sortorder == 'asc') {
                $subscription = $subs->sortBy($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            } else {
                $subscription = $subs->sortByDesc($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            }
        } else {
            $subscription = Subscriptions::get()->sortByDesc('subs_title', SORT_NATURAL | SORT_FLAG_CASE)->toArray();
        }
        $slice = array_slice($subscription, $perPage * ($page - 1), $perPage);

        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder,
        ));
        return view('admin.subscriptions.index', ['subs' => $slice, 'pagination' => $pagination]);
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
        return view('admin.subscriptions.create');
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
                'val-title' => 'required|min:3|max:30|unique:subscriptions,subs_title,',
                'val-gems' => 'required|integer|between:1,999999',
                'val-price' => 'required|integer|between:1,999999',
                'val-validity' => 'required',
            ],
            [
                'val-title.min' => trans('app.Title must be at least 3 characters.'),
                'val-title.max' => trans('app.Title may not be greater than 30 characters.'),
                'val-title.unique' => trans('app.Subscription with this title already added'),
                'val-gems.integer' => trans('app.Please provide valid gems count'),
                'val-price.integer' => trans('app.Please provide valid price'),
                'val-gems.between' => trans('app.Please provide valid gems count'),
                'val-price.between' => trans('app.Please provide valid price'),
                'val-title.required' => trans('app.Please provide title'),
                'val-validity.required' => trans('app.Please provide availability of prime duration.'),
                'val-price.required' => trans('app.Please provide prime account price.'),
                'val-gems.required' => trans('app.Please provide number of gems to be added for prime account.'),
            ]
        );

        $platform = trim($request->get('val-platform'));
        $subExists = Subscriptions::where('subs_title', 'like', $request->get('val-title'))->where('platform', $platform)->count();

        if ($subExists > 0) {
            $notification = array(
                'message' => 'Subscription title already exists',
                'alert-type' => 'error',
            );

        } else {
            $sub = new Subscriptions();
            $sub->subs_title = $request->get('val-title');
            $sub->subs_gems = $request->get('val-gems');
            $sub->subs_price = $request->get('val-price');
            $sub->subs_validity = $request->get('val-validity');
            $sub->platform = $request->get('val-platform');
            if ($sub->subs_validity == '1M') {
                $sub->subs_validity_days = 30;
            }
            if ($sub->subs_validity == '3M') {
                $sub->subs_validity_days = 90;
            }
            if ($sub->subs_validity == '6M') {
                $sub->subs_validity_days = 180;
            }
            if ($sub->subs_validity == '1Y') {
                $sub->subs_validity_days = 365;
            }

            if ($sub->save()) {
                $notification = array(
                    'message' => 'New Subscriptions added',
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
        return redirect('admin/subscriptions');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subscriptions  $subscriptions
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $subs = Subscriptions::find($id);
        if (!empty($subs)) {
            return view('admin.subscriptions.show', ['subs' => $subs]);
        } else {
            return view('errors.404');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Subscriptions  $subscriptions
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $sub = Subscriptions::find($id);
        return view('admin.subscriptions.edit', ['sub' => $sub, 'id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subscriptions  $subscriptions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'val-title' => 'required|min:3|unique:subscriptions,subs_title,' . $id . ',_id',
                'val-gems' => 'required|integer|between:1,999999',
                'val-price' => 'required|integer|between:1,999999',
                'val-validity' => 'required',
            ],
            [
                'val-title.unique' => trans('app.Subscription with this title already added'),
                'val-title.min' => trans('app.Title must be at least 3 characters.'),
                'val-title.max' => trans('app.Title may not be greater than 30 characters.'),
                'val-gems.integer' => trans('app.Please provide valid gems count'),
                'val-price.integer' => trans('app.Please provide valid price'),
                'val-gems.between' => trans('app.Please provide valid gems count'),
                'val-price.between' => trans('app.Please provide valid price'),
                'val-title.required' => trans('app.Please provide title'),
                'val-validity.required' => trans('app.Please provide availability of prime duration.'),
                'val-price.required' => trans('app.Please provide prime account price.'),
                'val-gems.required' => trans('app.Please provide number of gems to be added for prime account.'),
            ]
        );

        $sub = Subscriptions::findOrFail($id);
        $sub->subs_title = $request->get('val-title');
        $sub->subs_gems = $request->get('val-gems');
        $sub->subs_price = $request->get('val-price');
        $sub->platform = $request->get('val-platform');
        $sub->subs_validity = $request->get('val-validity');
        if ($sub->subs_validity == '1M') {
            $sub->subs_validity_days = 30;
        }
        if ($sub->subs_validity == '3M') {
            $sub->subs_validity_days = 90;
        }
        if ($sub->subs_validity == '6M') {
            $sub->subs_validity_days = 180;
        }
        if ($sub->subs_validity == '1Y') {
            $sub->subs_validity_days = 365;
        }

        $platform = trim($request->get('val-platform'));
        $subExists = Subscriptions::where('_id', '!=', $id)->where('subs_title', 'like', $request->get('val-title'))->where('platform', $platform)->count();

        if ($subExists > 0) {
            $notification = array(
                'message' => 'Subscription with this title already added',
                'alert-type' => 'error',
            );

        } else {
            if ($sub->save()) {
                $notification = array(
                    'message' => 'Subscriptions updated',
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
        return redirect('admin/subscriptions');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subscriptions  $subscriptions
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $sub = Subscriptions::find($id);
        if ($sub->delete()) {
            $notification = array(
                'message' => 'Subscriptions deleted',
                'alert-type' => 'success',
            );
        } else {
            $notification = array(
                'message' => 'Something went wrong',
                'alert-type' => 'error',
            );
        }

        session()->put('notification', $notification);
        return redirect('admin/subscriptions');
    }

    public function search(Request $request)
    {

        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $search = Input::post('search');
        $sortby = $request->input('sort');
        $sortorder = $request->input('direction');

        if ($search) {
            $paginate = Subscriptions::where('subs_title', 'like', "%$search%")->paginate(10);
            $subs = Subscriptions::where('subs_title', 'like', "%$search%")->get();
            if ($sortorder == 'asc') {
                $subscription = $subs->sortBy($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            } else {
                $subscription = $subs->sortByDesc($sortby, SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            }
        } else {
            $search = "";
            $paginate = Subscriptions::paginate(10);
            if ($sortorder == 'asc') {
                $subscription = Subscriptions::get()->sortBy('subs_title', SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            } else {
                $subscription = Subscriptions::get()->sortByDesc('subs_title', SORT_NATURAL | SORT_FLAG_CASE)->toArray();
            }
        }

        $slice = array_slice($subscription, $perPage * ($page - 1), $perPage);

        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder, 'search' => $search,
        ));

        return view('admin.subscriptions.index', ['subs' => $slice, 'pagination' => $pagination, 'search' => $search]);
    }
}
