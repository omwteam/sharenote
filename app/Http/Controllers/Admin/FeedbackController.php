<?php

namespace App\Http\Controllers\Admin;

use App\Feeds;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;

class FeedbackController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $list = Feeds::all();
        return view('feedback.index',['list'=>$list]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create(Request $request)
    {
        //
        $action = $request->input('action');
        return view('feedback.create',['action'=>$action]);
    }


    public function store(Request $request)
    {
        //
        $this->validate($request, [
                'title' => 'required|max:60',
                'content' => 'required|max:1000',
            ]);

        $params = $request->input();
        $params['ip'] = $request->ip();
        if (Auth::check()) {
            $params['user_id'] = Auth::id();
        }
        if (!isset($params['action'])) {
            $params['action'] = 'root';
        }
        $flag = Feeds::create($params);
        if (null != $flag) {
            return redirect(route('prompt'))->with(['message'=>'提交成功，感谢您的反馈！','url' =>route($params['action']), 'jumpTime'=>2,'status'=>true]);
        }
        return back()->withInput();


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Feeds  $feeds
     * @return \Illuminate\Http\Response
     */
    public function show(Feeds $feeds)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Feeds  $feeds
     * @return \Illuminate\Http\Response
     */
    public function edit(Feeds $feeds)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Feeds  $feeds
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feeds $feeds)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Feeds  $feeds
     * @return \Illuminate\Http\Response
     */
    public function destroy(Feeds $feeds)
    {
        //
    }
}
