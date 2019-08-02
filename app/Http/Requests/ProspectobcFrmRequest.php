<?php

namespace wbALFINop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProspectobcFrmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      if ($this->method()=='PUT') {
        $rulfolio='required|numeric';
      }else {
         $rulfolio='required|numeric|unique:tblprospectosbc';
      }
      return [
          //reglas de validacion del form

          'folio'=>$rulfolio,
          'nombre'=>'required|max:40',
          'paterno'=>'max:40',
          'materno'=>'max:40',
          'score'=>'required|numeric|max:99999',
          'fechaConsulta'=>'required',
          'tipoProspecto'=>'required|numeric|max:9',
          'tipoCliente'=>'required|numeric|max:99',
          'tipoProducto'=>'required|numeric|max:99',
          'montoSolicitud'=>'required|max:12',
          'estatus'=>'required|numeric|max:9',
          'perfil'=>'required|max:20'
      ];
    }
}
