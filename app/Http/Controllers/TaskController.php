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
        if($tasks->isEmpty())
            $tasks = "";
        $closed = Task::where('status', 'Terminé')->orderBy('priority', 'ASC')->get();
        if($closed->isEmpty())
            $closed = "";

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

        return redirect()->route('dashboard')->with('msg', 'Nouvelle tache ajoutée');
    }

    /**
     * Manage the specified task in storage
     * 
     * Cette méthode est appelée par tous les boutons du formulaire de gestion des taches
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
                $message = "Tache effacée";
            break;
            case "Update":
                Task::prepareForUpdate($request->task_id);
                $message = "Vous pouvez modifier la tache sélectionnée";
            break;
            case "Updated":
                Task::saveUpdated($request->task_id, $request->description, $request->priority);
                $message = "La tache a été modifiée";
            break;
            case "Termine":
                Task::closebyId($request->task_id);
                $message = "Tache terminée";
            break;
            case "Reopen":
                Task::openbyId($request->task_id);
                $message = "Tache réouverte";
            break;
            case "+":
                Task::priorityUp($request->task_id);
                $message = "Priorité modifiée";
            break;
            case "-":
                Task::priorityDown($request->task_id);
                $message = "Priorité modifiée";
            break;
        }
    
        return redirect()->route('dashboard')->with('msg', $message);
    }

}
