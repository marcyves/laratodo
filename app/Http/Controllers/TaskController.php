<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Column;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $columns = Column::listAll();
        
        $tasks = Task::listForStatus('En cours');
        if($tasks->isEmpty())
            $tasks = "";

        $closed = Task::listForStatus('Terminé');
        if($closed->isEmpty())
            $closed = "";

        return view('dashboard', [
            'columns' => $columns,
            'tasks' => $tasks,
            'closedTasks' => $closed]);
    }

    /**
     * Store a newly created task in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!empty($request->description))
            Task::createNew($request->description, $request->priority, $request->column_id);

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
        // On vérifie la cohérence de la requête
        if($request->column_id == Column::getColumnForTask($request->task_id))
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
        }else{
            $message = "Cette tâche n'appartient pas à la route";
        }

    
        return redirect()->route('dashboard')->with('msg', $message);
    }

}
