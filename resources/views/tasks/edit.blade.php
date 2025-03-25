@extends('layout')

@section('title', 'Edit Task')

@section('content')
<section class="bg-gray-50 dark:bg-gray-900">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto lg:py-0">
        <div class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-2xl xl:p-0 dark:bg-gray-800 dark:border-gray-700">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
                    Edit Task
                </h1>
                <form class="space-y-4 md:space-y-6" action="{{ route('tasks.update', $task->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="title" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Title</label>
                        <input type="text" name="title" id="title" value="{{ $task->title }}" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required>
                    </div>
                    <div>
                        <label for="description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Description</label>
                        <textarea name="description" id="description" rows="4" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">{{ $task->description }}</textarea>
                    </div>
                    <div>
                        <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                        <select name="status" id="status" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in-progress" {{ $task->status == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div>
                        <label for="assigned_to" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Assign To (Multiple)</label>
                        <select name="assigned_to[]" id="assigned_to" multiple class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ $task->assignedUsers->contains($user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                            <!-- Include current user -->
                            <option value="{{ Auth::id() }}" {{ $task->assignedUsers->contains(Auth::id()) ? 'selected' : '' }}>{{ Auth::user()->name }} (You)</option>
                        </select>
                        <p class="text-xs mt-1 text-gray-500 dark:text-gray-400">Hold Ctrl (Windows) or Cmd (Mac) to select multiple users</p>
                    </div>
                    <div>
                        <label for="due_date" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Due Date</label>
                        <input type="date" name="due_date" id="due_date" value="{{ $task->due_date ? date('Y-m-d', strtotime($task->due_date)) : '' }}" class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div class="flex items-center justify-between pt-4">
                        <a href="{{ route('dashboard') }}" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">
                            Back to Dashboard
                        </a>
                        <button type="submit" class="w-auto text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                            Update Task
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
