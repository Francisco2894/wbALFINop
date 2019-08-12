<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Http\Request;
use wbALFINop\Http\Requests\CambiarPasswordRequest;
use wbALFINop\User;
use wbALFINop\Perfil;
use wbALFINop\Events\UserNewPassword;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $users = User::orderBy('name','ASC')->where("name", "LIKE", "%{$request->get('name')}%")->paginate(15);
        return view('usuario.index',compact('users'));
    }

    public function perfiles()
    {
        $perfiles = Perfil::join('catpersonas','catpersonas.idPersona','=','catperfiles.idPersona')
        ->join('catsucursales','catsucursales.idSucursal','=','catperfiles.idSucursal')
        ->select('catperfiles.*','catpersonas.nombre','catpersonas.paterno','catpersonas.materno','catsucursales.sucursal')
        ->orderBy('catpersonas.nombre','ASC')
        ->paginate(15);
        return view('usuario.perfil',compact('perfiles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $usuario)
    {
        //
        return view('usuario.edit',compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CambiarPasswordRequest $request,User $usuario)
    {
        //
        $password = $request['password'];
        //$request['password'] = bcrypt($password);
        //$usuario->update($request->all());
        UserNewPassword::dispatch($usuario, $password);
        return redirect()->route('usuario.index')->with(['mensaje'=>"Password <strong>$usuario->name</strong> Actualizado con Exito"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
