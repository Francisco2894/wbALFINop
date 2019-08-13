<?php

namespace wbALFINop\Http\Controllers;

use Illuminate\Http\Request;
use wbALFINop\Http\Requests\CambiarPasswordRequest;
use wbALFINop\User;
use wbALFINop\Perfil;
use wbALFINop\Events\UserNewPassword;
use wbALFINop\Events\UserNew;

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

    public function perfiles(Request $request)
    {
        $perfiles = Perfil::join('catpersonas','catpersonas.idPersona','=','catperfiles.idPersona')
        ->join('catsucursales','catsucursales.idSucursal','=','catperfiles.idSucursal')
        ->select('catperfiles.*','catpersonas.nombre','catpersonas.paterno','catpersonas.materno','catsucursales.sucursal')
        ->where("catpersonas.nombre", "LIKE", "%{$request->get('name')}%")
        ->orWhere("catpersonas.paterno", "LIKE", "%{$request->get('name')}%")
        ->orWhere("catpersonas.materno", "LIKE", "%{$request->get('name')}%")
        ->orderBy('catpersonas.nombre','ASC')
        ->paginate(15);
        return view('usuario.perfil',compact('perfiles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        $perfil = Perfil::where('idPerfil',$request->idPerfil)->first();
        if (!is_null($perfil->usuario)) {
            return redirect()->route('listarPerfiles')->with(['error'=>'El Usuario ya esta <strong>Registrado</strong>']);
        }
        return view('usuario.create',compact('perfil'));
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
        //return $request;
        $password = str_random(5);
        $request['password'] = bcrypt($password);
        $request['status'] = 1;
        $usuario = User::create($request->all());
        UserNew::dispatch($usuario, $password);
        return redirect()->route('listarPerfiles')->with(['mensaje'=>'Usuario Registrado']);
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
        $request['password'] = bcrypt($password);
        $usuario->update($request->all());
        UserNewPassword::dispatch($usuario, $password);
        return redirect()->route('usuario.index')->with(['mensaje'=>"Password <strong>$usuario->name</strong> Actualizado con Exito"]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, User $usuario)
    {
        //
        if ($usuario->status == 0) {
            $usuario->update(['status'=>1]);
            return redirect()->route('listarPerfiles')->with(['Mensaje'=>'Usuario Activo']);
        } else {
            $usuario->update(['status'=>0]);
            return redirect()->route('listarPerfiles')->with(['error'=>'Usuario Bloqueado']);
        }
    }
}
