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
            'namaAcara' => 'required|string|max:254',
            'unitDepartemen' => 'required|string|max:254',
            'namaAnda' => 'required|string|max:254',
            'emailITS' => 'required|email|max:254',
            'userIntegra' => 'required|string|max:254',
            'waktuMulai' => 'required|date|after:now',
            'waktuSelesai' => 'required|date|after:waktuMulai',
            'group' => 'required|string|max:12',
            'pesertaBanyak' => 'required|numeric|in:500,1000',
        ];
    }
}
