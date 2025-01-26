<?php

namespace App\Http\Requests\Patient;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidCPF;

class UpdatePatientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'cpf' => ['sometimes', 'string', 'max:14', 'unique:patients,cpf,' . $this->route('patient'), new ValidCPF],
            'cellphone' => 'sometimes|string|max:20',
        ];
    }
}
