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
            Column::createNewColumn($request->name);

        return redirect()->route('dashboard')->with('msg', 'Nouvelle Colonne ajoutée');
    }

}
