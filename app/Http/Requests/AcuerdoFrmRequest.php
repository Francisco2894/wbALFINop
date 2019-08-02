<?php

namespace wbALFINop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AcuerdoFrmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;//se cambia a true
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //reglas de validacion del form
            'txtAcuerdo'=>'required|max:100',
            'txtMontoAcuerdo'=>'required|max:11',
            'dtpFechaAcuerdo'=>'required',
            'sltIdDevengo'=>'required',
            'sltIdResultado'=>'required'
        ];
    }
}
