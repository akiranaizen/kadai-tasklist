<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    
    public function index()
    {
        $data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザの投稿の一覧を作成日時の降順で取得
            // （後のChapterで他ユーザの投稿も取得するように変更しますが、現時点ではこのユーザの投稿のみ取得します）
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }

        // Welcomeビューでそれらを表示
        return view('welcome', $data);
    
        /* $tasks = Task::all();
        
           return view('tasks.index',['tasks' => $tasks,]); /* vew では、$tasks(左側)で呼ぶ 右は生成したやつ */
    }

    
    public function create()
    {
        $task = new Task;  /* Taskモデルのインスタンス生成 */
        
        return view('tasks.create',['task' => $task]);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
            ]);
        
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            'status' => $request->status,
            'content' => $request->content,
        ]);
        
        return redirect('/');
    }

    
    public function show($id)
    {
        $task = Task::findOrFail($id);
            if (\Auth::id() === $task->user_id) {   
                return view('tasks.show',['task' => $task,]);
            }
        
        return redirect('/');
    }

    
    public function edit($id)
    {
        $task = Task::findOrFail($id);
            if (\Auth::id() === $task->user_id) { 
                return view('tasks.edit',['task' => $task,]);
            }
        return redirect('/');
    }

    
    public function update(Request $request, $id)
    {
        
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
            ]);
            
        $task = Task::findOrFail($id);
            
        if (\Auth::id() === $task->user_id) {   
            $task->status = $request->status;
            $task->content = $request->content;
            $task->save();
        
        }
        
        return redirect('/');
    }

    
    public function destroy($id)
    {
        // idの値で投稿を検索して取得
        $task = \App\Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は、投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
        }
        
        return redirect('/');
    }
}
