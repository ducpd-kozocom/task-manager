@extends('layout')

@section('title', 'Task Details')

@section('content')
<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center px-6 py-8 mx-auto lg:py-0">
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-2xl xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                        Task Details
                    </h1>
                    <div>
                        @if($task->status == 'pending')
                        <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                        @elseif($task->status == 'in-progress')
                        <span class="px-3 py-1 text-sm rounded-full bg-blue-100 text-blue-800">In Progress</span>
                        @else
                        <span class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">Completed</span>
                        @endif
                    </div>
                </div>
                
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $task->title }}</h2>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $task->description }}</p>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Assigned To</p>
                        <div class="font-medium text-gray-900 dark:text-white">
                            @if($task->assignedUsers->count() > 0)
                                <ul class="list-disc pl-5">
                                @foreach($task->assignedUsers as $user)
                                    <li>{{ $user->name }}{{ $user->id == Auth::id() ? ' (You)' : '' }}</li>
                                @endforeach
                                </ul>
                            @else
                                <p>Unassigned</p>
                            @endif
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Due Date</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $task->due_date ? date('Y-m-d', strtotime($task->due_date)) : 'No due date' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Created By</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $task->creator->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Created At</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ date('Y-m-d H:i', strtotime($task->created_at)) }}</p>
                    </div>
                </div>
                
                <div class="flex gap-2 items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('dashboard') }}" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">
                        Back to Dashboard
                    </a>
                    <div class="flex gap-2">
                        <a href="{{ route('tasks.edit', $task->id) }}" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            Edit Task
                        </a>
                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                Delete Task
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
