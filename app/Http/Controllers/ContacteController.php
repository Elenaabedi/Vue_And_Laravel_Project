<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

use App\Models\Empresa;
use App\Models\Contacte;

class ContacteController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contactes = Contacte::all();
        return view('contacte.index', ['contactes' => $contactes]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $empreses = Empresa::all();

        return view('contacte.create', [
            'idEmpresa' => $id, 'empreses' => $empreses
        ]);
    }

    public function createWithoutId()
    {
        $empreses = Empresa::all();
        
        return view('contacte.createWithoutId', ['empreses' => $empreses]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $contacte = new Contacte;
        $contacte->nom = $request->nom;
        $contacte->cognoms = $request->cognoms;
        $contacte->movil = $request->movil;
        $contacte->email = $request->email;
        $contacte->empresa_id = $request->empresa;

        $contacte->save();

        $empresa = Empresa::find($contacte->empresa_id);
        $contactes = Empresa::find(1)->contactes;

        return view('empresa.show', ['empresa' => $empresa, 'contactes' => $contactes]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $contacte = Contacte::find($id);
        $empreses = Empresa::all();
        return view('contacte.edit', ['contacte' => $contacte, 'empreses' => $empreses]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $contacte = Contacte::find($id);
        $contacte->nom = $request->nom;
        $contacte->cognoms = $request->cognoms;
        $contacte->movil = $request->movil;
        $contacte->email = $request->email;
        $contacte->empresa_id = $request->empresa;

        $contacte->save();

        $empresa = Empresa::find($contacte->empresa_id);
        $contactes = Empresa::find(1)->contactes;

        return view('empresa.show', ['empresa' => $empresa, 'contactes' => $contactes]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $contacte = Contacte::find($id);
        $contacte->delete();

        $idEmpresa = $contacte->empresa_id;

        return redirect()->route('empresa_show', ['id' => $idEmpresa])->with('status', 'Contacte ' . $contacte->nom . ' eliminat!');
    }
}
