<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\NonGladiMin;
use Illuminate\Validation\Rule;

class EditBookingRequest extends FormRequest
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
			'kategoriAcara' => 'required|integer|exists:kategoris,id',
			'namaAcara' => 'required|string|max:254',
			'dokumenPendukung' => 'nullable|mimes:pdf,jpeg,jpg,png|max:2000',
			'penyelengaraAcara' => 'required|numeric|exists:units,id',
			'bookingTimes' => ['required','array','min:1',new NonGladiMin],
			'bookingTimes.*.id' => 'nullable|integer',
			'bookingTimes.*.gladi' => 'required|in:true,false',
			'bookingTimes.*.waktuMulai' => 'required|date|after:now',
			'bookingTimes.*.waktuSelesai' => 'required|date|after:bookingTimes.*.waktuMulai',
			'bookingTimes.*.pesertaBanyak' => 'required|numeric|in:500,1000',
			'bookingTimes.*.relayITSTV' => 'required|in:true,false',
		];
	}
}
