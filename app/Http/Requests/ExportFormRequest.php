<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExportFormRequest extends FormRequest
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
            'semuaWaktu' => 'nullable',
            'waktuMulai' => 'required_unless:semuaWaktu,selected',
            'waktuAkhir' => 'required_unless:semuaWaktu,selected',
            'kategori' => 'nullable|exists:kategoris,id',
            'status' => 'nullable|in:true,false,null',
        ];
    }
}
