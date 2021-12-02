<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                <form action="{{ route('store') }}" method="post">
                    @csrf
                    <input type="text" name="description">
                    <select name="priority">
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                    </select>
                    <input type="submit" value="Save">
                </form>
                </div>
            </div>
        </div>
    </div>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @empty($tasks)
                    You're logged in!
                    @else
                    <h2 class="font-bold text-xl text-gray-800 leading-tight">Taches en cours</h2>
                        <ul>
                        @foreach ($tasks as $task)
                            <li>{{ $task->description }}
                            <form action="{{ route('manage') }}" method="post">
                                @csrf
                                <input type="hidden" name="task_id" value="{{ $task->id }}">

                                <input type="submit" name="priority" value="{{ $task->priority }}">

                                <input type="submit" name="cmd" value="Termine">
                                <input type="submit" name="cmd" value="Efface">
                            </form>
                            </li>
                        @endforeach
                        </ul>
                        <hr>
                        <h2 class="font-bold text-xl text-gray-800 leading-tight">Taches terminées</h2>
                        <ul>
                            @foreach ($closedTasks as $task)
                                <li>{{ $task->description }} ({{ $task->priority }}) 
                                <form action="{{ route('manage') }}" method="post">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                                    <input type="submit" name="cmd" value="Reopen">
                                    <input type="submit" name="cmd" value="Efface">
                                </form>
                                </li>
                            @endforeach
                            </ul>
    
                    @endempty
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
