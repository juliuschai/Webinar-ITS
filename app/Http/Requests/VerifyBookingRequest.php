<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\AlasanRequiredIfDenied;

class VerifyBookingRequest extends FormRequest
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
			'adminDPTSI' => 'required|integer|exists:users,id',
			'verify.*.id' => 'required|integer', 
			'verify.*.status' => ['nullable', 'in:accept,deny', new AlasanRequiredIfDenied($this->alasan)], 
			'verify.*.hostAccount' => 'required_if:verify.*.status,accept|nullable|exists:host_accounts,id', 
		];
	}
}
