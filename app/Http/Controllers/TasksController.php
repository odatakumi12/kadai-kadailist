<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;

class TasksController extends Controller
{

    public function index()
    {
        if (\Auth::check()) {
          // メッセージ一覧を取得 
        $user = \Auth::user();
        $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);     // 追加

        // メッセージ一覧ビューでそれを表示
        return view('dashboard', ['tasks' => $tasks,]); 
        }
        else{
            return redirect('dashboard');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (\Auth::check()) {
            $task = new Task;
            return view('tasks.create', ['task' => $task,]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (\Auth::check()) {
            $request->validate(['content' => 'required', 'status' => 'required|max:10',]);
            $user = \Auth::user();
        
        $task = new Task;
        $task->content = $request->content;
        $task->status = $request->status;
        $task->user_id = $user->id;
        $task->save();
        
         }
         // トップページへリダイレクトさせる
         return redirect('/');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         if (\Auth::check()) {
        $task = Task::findOrFail($id);

        if (\Auth::id() !== $task->user_id || is_null($task->user_id)) {
            // トップページへリダイレクト
            return redirect('/');
        }
        // メッセージ詳細ビューでそれを表示
        return view('tasks.show', ['task' => $task,]);
         }
         return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (\Auth::check()) {
        // idの値でメッセージを検索して取得
            $task = Task::findOrFail($id);
            return view('tasks.edit', ['task' => $task,]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (\Auth::check()) {
            $request->validate([ 'content' => 'required',   'status' => 'required|max:10',]);
        
        $task = Task::findOrFail($id);
        // メッセージを更新
        $task->content = $request->content;
        $task->status = $request->status;
        $task->save();
         }
          return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         if (\Auth::check()) {
       $task = Task::findOrFail($id);
        // メッセージを削除
        $task->delete();
         }
        return redirect('/');

    }
}
