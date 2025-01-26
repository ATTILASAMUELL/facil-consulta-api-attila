<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCPF;

class CreatePatientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'cpf' => ['required', 'string', 'max:14', 'unique:patients', new ValidCPF],
            'cellphone' => 'required|string|max:20',
        ];
    }
}
