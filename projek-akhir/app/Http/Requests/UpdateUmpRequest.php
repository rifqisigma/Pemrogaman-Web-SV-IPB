<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUmpRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ump = $this->route('ump');
        $provinsiId = $this->provinsi_id ?? $ump->provinsi_id;

        return [
            'provinsi_id' => ['sometimes', 'required', 'integer', 'exists:provinsis,id'],
            'tahun' => [
                'sometimes',
                'required',
                'integer',
                'min:2000',
                'max:2100',
                Rule::unique('umps')
                    ->where(function ($query) use ($provinsiId) {
                        return $query->where('provinsi_id', $provinsiId);
                    })
                    ->ignore($ump->id),
            ],
            'nominal' => ['sometimes', 'required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'tahun.unique' => 'Data UMP untuk provinsi dan tahun tersebut sudah terdaftar.',
        ];
    }
}