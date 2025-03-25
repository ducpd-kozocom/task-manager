@extends('layout')

@section('title', 'Dashboard')

@section('content')
<section class="bg-gray-50 dark:bg-gray-900 min-h-screen py-8">
    <div class="flex flex-col px-6 mx-auto h-full">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Task Management</h1>
            <div class="flex space-x-2">
                <a href="{{ route('tasks.create') }}" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 focus:ring-4 focus:ring-primary-300">
                    <i class="fas fa-plus mr-2"></i> New Task
                </a>
            </div>
        </div>

        <!-- Task Filters -->
        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow mb-6">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('dashboard', ['filter' => 'my_tasks']) }}" class="px-3 py-1 {{ request('filter') == 'my_tasks' || !request('filter') ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md">My Tasks</a>
                <a href="{{ route('dashboard', ['filter' => 'assigned_to_me']) }}" class="px-3 py-1 {{ request('filter') == 'assigned_to_me' ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-700' }} rounded-md">Assigned to Me</a>
            </div>
        </div>

        <!-- Task List -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Title</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                        <th scope="col" class="px-6 py-3">Assigned To</th>
                        <th scope="col" class="px-6 py-3">Due Date</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks ?? [] as $task)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $task->title }}
                        </td>
                        <td class="px-6 py-4">
                            @if($task->status == 'pending')
                            <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @elseif($task->status == 'in-progress')
                            <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">In Progress</span>
                            @else
                            <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Completed</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($task->assignedUsers->count() > 0)
                                <div class="max-h-20 overflow-y-auto">
                                @foreach($task->assignedUsers->take(3) as $user)
                                    <div class="text-xs mb-1 {{ $user->id == Auth::id() ? 'font-semibold' : '' }}">
                                        {{ $user->name }}{{ $user->id == Auth::id() ? ' (You)' : '' }}
                                    </div>
                                @endforeach
                                @if($task->assignedUsers->count() > 3)
                                    <div class="text-xs text-gray-500">+{{ $task->assignedUsers->count() - 3 }} more</div>
                                @endif
                                </div>
                            @else
                                <span>Unassigned</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            {{ $task->due_date ? date('Y-m-d', strtotime($task->due_date)) : 'No due date' }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('tasks.show', $task->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-2">View</a>
                            <a href="{{ route('tasks.edit', $task->id) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-2">Edit</a>
                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td colspan="5" class="px-6 py-4 text-center">No tasks found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
