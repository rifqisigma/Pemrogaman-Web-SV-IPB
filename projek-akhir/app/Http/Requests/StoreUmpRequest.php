<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUmpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'provinsi_id' => ['required', 'exists:provinsis,id'],
            'tahun' => [
                'required',
                'integer', 
                'min:2000', 
                'max:2100', 
                Rule::unique('umps')->where(function ($query) {
                    return $query->where('provinsi_id', $this->provinsi_id);
                })
            ],
            'nominal' => ['required', 'numeric', 'min:0']
        ];
    }

    public function messages(): array
    {
        return [
            'tahun.unique' => 'Data UMP untuk provinsi dan tahun tersebut sudah terdaftar.',
        ];
    }
}
