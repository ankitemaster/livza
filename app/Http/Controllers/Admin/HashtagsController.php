<?php
namespace App\Http\Controllers\Admin;
use App\Models\Hashtags;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input as Input;
use Session;
class HashtagsController extends Controller
{
    public function index(Request $request)
    {
        if (Auth::guest()) {
            return view('auth.login');
        }
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        $sortby = $request->input('sort');
        $sortorder = $request->input('direction');
        $paginate=Hashtags::paginate(10);
        if ($sortby && $sortorder) {
            $allhashtags = Hashtags::get();
            if($sortorder == 'asc')
            {
                $hashtags = $allhashtags->sortBy($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
            else
            {
                $hashtags = $allhashtags->sortByDesc($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
        } else {
            $hashtags = Hashtags::get()->sortByDesc('created_at',SORT_NATURAL|SORT_FLAG_CASE)->toArray();
        }
        $slice = array_slice($hashtags, $perPage * ($page - 1), $perPage);
        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder,
        ));
        return view('admin.hashtags.index', ['hashtags' => $slice,'pagination'=>$pagination]);
    }

    public function create()
    {
        if (Auth::guest()) {
            return view('auth.login');
        }
        return view('admin.hashtags.create');
    }

    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                "hashtag-topic" => ['required','min:3','max:30', 'regex:/^(?=.{2,140}$)(|\x{ff03}){1}([0-9_\p{L}]*[_\p{L}][0-9_\p{L}]*)$/u']
                
            ],
            [
                'hashtag-topic.required' => trans('app.Topic is required'),
                'hashtag-topic.min' => trans('app.Topic must be at least 3 characters.'),
                'hashtag-topic.max' => trans('app.Topic may not be greater than 30 characters.'),
                'hashtag-topic.regex' => trans('app.Topic is not valid'),
            ]
        );

        $hashtag = new Hashtags();
        $hashtag->topic = $request->get('hashtag-topic');
        $hashtag_topic = $request->get('hashtag-topic');
        $topicExists = Hashtags::where('topic', $hashtag_topic)->count();
        if ($topicExists > 0) {
            $notification = array(
                'message' => 'Topic already exists',
                'alert-type' => 'error',
            );
        } else {
            if ($hashtag->save()) {
                $notification = array(
                    'message' => 'New Hashtag Created',
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
        return redirect('admin/hashtags');
    }

    public function edit($id)
    {
        $hashtag = Hashtags::find($id);
        return view('admin.hashtags.edit', ['hashtag' => $hashtag, 'id' => $id]);
    }

    public function update(Request $request, $id)
    {
        $this->validate(
            $request,
            [
                'hashtag-topic' => 'required|min:3|max:30',   
            ],
            [
                'hashtag-topic.required' => trans('app.Topic is required'),
                'hashtag-topic.min' => trans('app.Topic must be at least 3 characters.'),
                'hashtag-topic.max' => trans('app.Topic may not be greater than 30 characters.'),
            ]
        );

        $topicExists = Hashtags::where('_id', '!=', $id)->where('topic', $request->get('hashtag-topic'))->count();
        if ($topicExists > 0) {
            $notification = array(
                'message' => 'Topic already exists',
                'alert-type' => 'error',
            );
        } else {
            $hashtag = Hashtags::findOrFail($id);
            $hashtag->topic = $request->get('hashtag-topic');
            if ($hashtag->save()) {
                $notification = array(
                    'message' => 'Hashtag updated',
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
        return redirect('admin/hashtags');
    }

    public function destroy($id)
    {
        $hashtag = Hashtags::find($id);
        if ($hashtag->delete()) {
            $notification = array(
                'message' => 'Hashtag deleted',
                'alert-type' => 'success',
            );
        } else {
            $notification = array(
                'message' => 'Something went wrong',
                'alert-type' => 'error',
            );
        }
        session()->put('notification', $notification);
        return redirect('admin/hashtags');
    }

    public function search(Request $request)
    {
        $search = Input::post('search');
        $sortby = $request->input('sort');
        $sortorder = $request->input('direction');
        $page = \Illuminate\Pagination\Paginator::resolveCurrentPage();
        $perPage = 10;
        if ($search) {
            $paginate=Hashtags::where('topic', 'like', "%$search%")->paginate(10);
            $hashtag = Hashtags::where('topic', 'like', "%$search%")->get();
            if($sortorder == 'asc')
            {
                $hashtags = $hashtag->sortBy($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
            else
            {
                $hashtags = $hashtag->sortByDesc($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
            }
        } else {
            $paginate=Hashtags::paginate(10);
            $search = "";
            $hashtags = Hashtags::get()->sortByDesc($sortby,SORT_NATURAL|SORT_FLAG_CASE)->toArray();
        }
        $slice = array_slice($hashtags, $perPage * ($page - 1), $perPage);
        $pagination = $paginate->appends(array(
            'sort' => $sortby, 'direction' => $sortorder,'search' => $search
        ));
        return view('admin.hashtags.index', ['hashtags' => $slice,'pagination'=>$pagination,'search' => $search]);
    }
}