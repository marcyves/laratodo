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
        'user_id',
        'sort'
    ];

    public static function listAll()
    {
        return Column::all();
    }

    public static function listAllUserColumns()
    {
        return Column::where('user_id',Auth::id())->orderBy('sort', 'ASC')->get();
    }

    public static function count()
    {
        // TODO compter uniquement les colonnes du user connecté
        return Column::all()->count();
    }

    public static function createNew($name)
    {
        $max_col = Column::count();

        Column::create(['name' => $name,
        'user_id' => Auth::id(),
        'sort' => $max_col+1]);
    }

    public static function getColumnSortForTask($id)
    {
        $task = Task::find($id);
        return Column::find($task->column_id)->sort;
    }

    public static function getColumnIdByTask($id)
    {
        return Task::find($id)->column_id;
    }

    public static function getColumnIdBySort($sort)
    {
        return Column::where('sort', $sort)->first()->id;
    }

    public static function resetOrder($id, $sort)
    {
        Column::where('id', $id)->update(['sort' => $sort]);
    }



}
