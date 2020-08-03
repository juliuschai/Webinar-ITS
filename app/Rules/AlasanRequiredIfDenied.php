<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AlasanRequiredIfDenied implements Rule
{
	public $id;

	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct($alasan)
	{
		$this->alasan = $alasan;
	}

	/**
	 * Determine if the validation rule passes.
	 *
	 * @param  string  $attribute
	 * @param  mixed  $value
	 * @return bool
	 */
	public function passes($attribute, $value)
	{
		// if alasan doesn't exist and booking is denied
		if (!$this->alasan && $value == "deny") {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return 'Alasan diperlukan jika terdapat booking yang ditolak!';
	}
}
