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
}
