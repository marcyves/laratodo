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
                if(Task::status($request->task_id) == "Terminé"){
                    // Elle est marquée terminée, elle repart en colonne
                    Task::openbyId($request->task_id);
                    $message = "Tache réouverte";    
                }else{
                    $sort  = Column::getColumnSortForTask($request->task_id);
                    $left_column_id = Column::getColumnIdBySort($sort-1);
                    Task::moveColumn($request->task_id, $left_column_id);
                    $message = "Tache déplacée";    
                }
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
