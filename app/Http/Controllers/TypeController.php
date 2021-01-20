<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeStoreRequest;
use App\Http\Requests\TypeUpdateRequest;
use App\Models\Type;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TypeExport;

class TypeController extends Controller
{
    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $types = Type::Search($request->nombre)->get();

        return view('type.index', compact('types'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('type.create');
    }

    /**
     * @param \App\Http\Requests\TypeStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(TypeStoreRequest $request)
    {
        $type = Type::create($request->validated());

        $request->session()->flash('type.id', $type->id);

        return redirect()->route('type.index');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Type $type
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Type $type)
    {
        return view('type.show', compact('type'));
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Type $type
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Type $type)
    {
        return view('type.edit', compact('type'));
    }

    /**
     * @param \App\Http\Requests\TypeUpdateRequest $request
     * @param \App\Models\Type $type
     * @return \Illuminate\Http\Response
     */
    public function update(TypeUpdateRequest $request, $id)
    {
        $type = Type::findOrFail($request->id);        

        $type->update($request->validated());

        $request->session()->flash('type.id', $type->id);

        return redirect()->route('type.index');
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Type $type
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Type $type)
    {
        $type->delete();

        return redirect()->route('type.index');
    }

    public function export() 
    {
        return Excel::download(new TypeExport, 'TiposErradicaciones.xlsx');
    }
}
