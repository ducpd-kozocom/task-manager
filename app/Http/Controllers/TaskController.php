<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Http\Requests\TaskRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Services\TaskService;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->input('filter', 'my_tasks');
        $userId = Auth::id();

        $tasks = $this->taskService->getFilteredTasks($filter, $userId);

        return view('dashboard', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('tasks.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TaskRequest $request)
    {
        $task = new Task();
        $result = $this->taskService->saveTask($request, $task);

        if ($result['success']) {
            return redirect()->route('dashboard')->with('success', $result['message']);
        } else {
            return redirect()->back()->withInput()->with('error', $result['message']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        Gate::authorize('view', $task);
        $task->load('creator', 'assignedUsers');
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        Gate::authorize('update', $task);
        $users = User::where('id', '!=', Auth::id())->get();
        return view('tasks.edit', compact('task', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);

        $result = $this->taskService->saveTask($request, $task);

        if ($result['success']) {
            return redirect()->route('dashboard')->with('success', $result['message']);
        } else {
            return redirect()->back()->withInput()->with('error', $result['message']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $this->taskService->deleteTask($task);

        return redirect()->route('dashboard')->with('success', 'Task deleted successfully');
    }
}
