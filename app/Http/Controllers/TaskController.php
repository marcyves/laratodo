<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tasks = Task::where('status', 'En cours')->orderBy('priority', 'ASC')->get();
        $closed = Task::where('status', 'Terminé')->orderBy('priority', 'ASC')->get();

        return view('dashboard', ['tasks' => $tasks, 'closedTasks' => $closed]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!empty($request->description))
            Task::createNewTask($request->description, $request->priority);

        return redirect()->route('dashboard');
    }

    /**
     * Manage the specified task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function manage(Request $request)
    {
        switch($request->cmd)
        {
            case "Efface":
                Task::deleteById($request->task_id);

            break;
            case "Termine":
                Task::closebyId($request->task_id);
            break;
            case "Reopen":
                Task::openbyId($request->task_id);
            break;
            case "+":
                Task::priorityUp($request->task_id);
            break;
            case "-":
                Task::priorityDown($request->task_id);
            break;
        }
    
        return redirect()->route('dashboard');
    }

}
