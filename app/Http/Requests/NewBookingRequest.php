<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewBookingRequest extends FormRequest
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
            'namaAcara' => 'required|string|max:254',
            'dokumenPendukung' => 'required|mimes:pdf,jpeg,jpg',
            'penyelengaraAcara' => 'required|numeric|exists:units,id',
            'waktuMulai' => 'required|date|after:now',
            'waktuSelesai' => 'required|date|after:waktuMulai',
            'pesertaBanyak' => 'required|numeric|in:500,1000',
        ];
    }
}
