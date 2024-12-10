@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0">My Tasks</h5>
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Task
                    </a>
                </div>

                <div class="card-body">
                    <form action="{{ route('tasks.index') }}" method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Search tasks..." 
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-outline-primary">
                                Search
                            </button>
                        </div>
                    </form>

                    @if($tasks->isEmpty())
                        <div class="text-center py-5">
                            <h4 class="text-muted">No tasks found</h4>
                            <p class="text-muted">Start by creating a new task</p>
                        </div>
                    @else
                        @foreach($tasks as $task)
                            <div class="card mb-3 task-card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="card-title mb-1 {{ $task->is_completed ? 'text-muted text-decoration-line-through' : '' }}">
                                            {{ $task->title }}
                                        </h5>
                                        <span class="badge bg-{{ $task->priority === 'high' ? 'danger' : ($task->priority === 'medium' ? 'warning' : 'info') }} priority-badge">
                                            {{ ucfirst($task->priority) }}
                                        </span>
                                    </div>
                                    
                                    @if($task->description)
                                        <p class="card-text text-muted small mt-2">{{ $task->description }}</p>
                                    @endif

                                    @if($task->attachment_path)
                                        <div class="mt-2">
                                            <a href="{{ Storage::url($task->attachment_path) }}" 
                                               target="_blank"
                                               class="btn btn-outline-secondary btn-sm">
                                                <i class="fas fa-paperclip"></i> View Attachment
                                            </a>
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-end gap-2 mt-3 task-actions">
                                        <form action="{{ route('tasks.toggle-complete', $task) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-{{ $task->is_completed ? 'outline-success' : 'success' }} btn-sm">
                                                {{ $task->is_completed ? 'Mark Incomplete' : 'Mark Complete' }}
                                            </button>
                                        </form>

                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-outline-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this task?')">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <div class="mt-4">
                            {{ $tasks->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection