<?php

namespace wbALFINop\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonaFrmRequest extends FormRequest
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
            //aqui van las reglas de validacion
        ];
    }
}
