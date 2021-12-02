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
        'user_id'
    ];

    public static function createNewTask($description, $priority)
    {
        $newTask = TasK::create(['description' => $description,
        'priority' => $priority,
        'user_id' => Auth::id(),
        'status' => 'En cours']);
    }
    public static function deleteById($id)
    {
        Task::where('id', $id)->delete();
    }

    public static function closeById($id)
    {
        Task::where('id', $id)->update(['status' => 'Terminé']);
    }
    public static function openById($id)
    {
        Task::where('id', $id)->update(['status' => 'En cours']);
    }

    public static function priorityUp($id)
    {
        $task = Task::where('id', $id)->first();
        //dd($task->priority);

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
