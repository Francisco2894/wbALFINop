<?php

namespace wbALFINop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PerfilRequest extends FormRequest
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
        return [
            //
            'nombre'        =>  'required|min:3',
            'paterno'       =>  'required|min:3',
            'materno'       =>  'required|min:3',
            'idPerfil'      =>  'required|unique:catperfiles',
            'descripcion'   =>  'required',
        ];
    }
}
