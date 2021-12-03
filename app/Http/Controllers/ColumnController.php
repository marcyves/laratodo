<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Column;

class ColumnController extends Controller
{
        /**
     * Store a newly created column in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!empty($request->name))
            Column::createNew($request->name);

        return redirect()->route('dashboard')->with('msg', 'Nouvelle Colonne ajoutée');
    }

    public function sort(Request $request)
    {
        $sort      = Column::find($request->column_id)->sort;

        switch($request->cmd)
        {
            case "right":
                $column_right = Column::where('sort', $sort+1)->first();
                // On décale la colonne de droite à gauche
                Column::resetOrder($column_right->id, $column_right->sort-1);
                // On pousse la colonne à droite
                Column::resetOrder($request->column_id, $sort+1);
            break;
            case "left":
                $column_left = Column::where('sort', $sort-1)->first();
                // On décale la colonne de gauche à droite
                Column::resetOrder($column_left->id, $column_left->sort+1);
                // On pousse la colonne à gauche
                Column::resetOrder($request->column_id, $sort-1);
            break;
        }

        return redirect()->route('dashboard')->with('msg', 'Ordre des colonnes modifié');
    }

}
