<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;

class Column extends Model
{
    use HasFactory;

        /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'user_id'
    ];

    public static function listAll()
    {
        return Column::where('user_id',Auth::id())->orderBy('sort', 'ASC')->get();
    }

    public static function createNew($name)
    {

        $newTask = Column::create(['name' => $name,
        'user_id' => Auth::id()]);
    }

    public static function getColumnForTask($id)
    {
        return Task::find($id)->column_id;
    }

}
