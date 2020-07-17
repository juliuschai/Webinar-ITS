<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaveBookingRequest extends FormRequest
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
            'namaPic' => 'required|string|max:254',
            'integraPic' => 'required|string|max:20',
            'emailPic' => 'required|email|max:254',
            'sivitas' => 'required|string|max:254',
            'departemenUnit' => 'required|string|max:254',
            'noWa' => 'required|string|max:254',
            'namaAcara' => 'required|string|max:254',
            'penyelengaraAcara' => 'required|numeric|exists:organisasis,id',
            'waktuMulai' => 'required|date|after:now',
            'waktuSelesai' => 'required|date|after:waktuMulai',
            'pesertaBanyak' => 'required|numeric|in:500,1000',
        ];
    }
}
