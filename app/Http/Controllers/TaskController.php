<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $query = Task::where('user_id', auth()->id());

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $tasks = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable',
            'priority' => 'required|in:low,medium,high',
            'attachment' => 'nullable|file|max:2048'
        ]);
    
        $task = new Task($validated);
        $task->user_id = auth()->id();
    
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('attachments', 'public');
            $task->attachment_path = $path;
        }
    
        $task->save();
    
        return redirect()->route('tasks.index')->with('success', 'Task created successfully');
    }

    public function toggleComplete(Task $task)
    {
        $this->authorize('update', $task);
        
        $task->update([
            'is_completed' => !$task->is_completed
        ]);

        return redirect()->back()->with('success', 'Task status updated');
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        if ($task->attachment_path) {
            Storage::delete($task->attachment_path);
        }

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully');
    }
}