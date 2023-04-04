<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use App\Models\Categoria;
use App\Models\Empresa;
use App\Models\Poblacio;
use App\Models\Sector;

class EmpresaController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empreses = Empresa::all();
        return view('empresa.index', ['empreses' => $empreses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $poblacions = Poblacio::all();
        $categories = Categoria::all();
        $sectors = Sector::all();

        return view('empresa.create', [
            'poblacions' => $poblacions,
            'categories' => $categories,
            'sectors' => $sectors
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $empresa = new Empresa;
        $empresa->nom = $request->nom;
        $empresa->telefon = $request->telefon;
        $empresa->web = $request->web;
        $empresa->email = $request->email;
        $empresa->poblacio_id = $request->poblacio_id;
        $empresa->categoria_id = $request->categoria_id;
        $empresa->sector_id = $request->sector_id;

        $empresa->save();

        $id = $empresa->id;


        return redirect()->route('empresa_show', ['id' => $id])->with('status', 'Nova empresa ' . $empresa->nom . ' creada!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $empresa = Empresa::find($id);
        $contactes = Empresa::find(1)->contactes;

        return view('empresa.show', ['empresa' => $empresa, 'contactes' => $contactes]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $empresa = Empresa::find($id);
        $poblacions = Poblacio::all();
        $categories = Categoria::all();
        $sectors = Sector::all();
        return view('empresa.edit', ['empresa' => $empresa, 'poblacions' => $poblacions, 'categories' => $categories, 'sectors' => $sectors]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $empresa = Empresa::find($id);
        $empresa->nom = $request->nom;
        $empresa->telefon = $request->telefon;
        $empresa->web = $request->web;
        $empresa->email = $request->email;
        $empresa->poblacio_id = $request->poblacio_id;
        $empresa->categoria_id = $request->categoria_id;
        $empresa->sector_id = $request->sector_id;

        $empresa->save();

        return redirect()->route('empresa_index')->with('status', 'Empresa ' . $empresa->nom . ' modificada!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $empresa = Empresa::find($id);
        $empresa->delete();

        return redirect()->route('empresa_index')->with('status', 'Empresa ' . $empresa->nom . ' eliminada!');
    }
}
