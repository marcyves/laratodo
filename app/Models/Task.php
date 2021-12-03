<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'description',
        'priority',
        'status',
        'update',
        'column_id',
        'user_id'
    ];


    public static function listForStatus($status)
    {
        return Task::where('status', $status)->where('user_id',Auth::id())->orderBy('priority', 'ASC')->get();
    }

    public static function createNew($description, $priority, $column)
    {

        $newTask = Task::create(['description' => $description,
        'priority' => $priority,
        'user_id' => Auth::id(),
        'column_id' => $column,
        'update' => False,
        'status' => 'En cours']);
    }
    
    public static function deleteById($id)
    {
        Task::where('id', $id)->delete();
    }

    public static function status($id)
    {
        return Task::find($id)->status;
    }

    public static function prepareForUpdate($id)
    {
        Task::where('id', $id)->update(['update' => True]);
    }

    public static function saveUpdated($id, $description, $priority)
    {
        Task::where('id', $id)->update([
                            'update' => False,
                            'description' => $description,
                            'priority' => $priority]);
    }

    public static function closeById($id)
    {
        $max_col = Column::count();
        $sort  = Column::getColumnSortForTask($id);

        if($sort>=$max_col){
            // Quand elle arrive dans la dernière colonne, son status passe à terminé
            Task::where('id', $id)->update(['status' => 'Terminé']);
        }else{
            // Quand une tâche est terminée dans une colonne, elle passe dans la colonne à droite
            $next_column_id = Column::getColumnIdBySort($sort+1);
            Task::moveColumn($id, $next_column_id);
        }
    }

    public static function moveColumn($id, $column_id)
    {
        Task::where('id', $id)->update(['column_id' => $column_id]);
    }

    public static function openById($id)
    {
        Task::where('id', $id)->update(['status' => 'En cours']);
    }

    public static function priorityUp($id)
    {
        $task = Task::where('id', $id)->first();

        switch($task->priority)
        {
            case "A":
                $priority = "C";
            break;
            case "B":
                $priority = "A";
            break;
            case "C":
                $priority = "B";
            break;
        }

        Task::where('id', $id)->update(['priority' => $priority]);
    }
    public static function priorityDown($id)
    {
        $task = Task::where('id', $id)->first();

        switch($task->priority)
        {
            case "A":
                $priority = "B";
            break;
            case "B":
                $priority = "C";
            break;
            case "C":
                $priority = "A";
            break;
        }

        Task::where('id', $id)->update(['priority' => $priority]);
    }
}
