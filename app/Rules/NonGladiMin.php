<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class NonGladiMin implements Rule
{
	/**
	 * Create a new rule instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
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
		foreach ($value as $val) {
			if ($val['gladi'] == "false") {
				return true;
			}
		}
		return false;
	}

	/**
	 * Get the validation error message.
	 *
	 * @return string
	 */
	public function message()
	{
		return 'Harus terdapat 1 sesi yang bukan merupakan gladi!';
	}
}
