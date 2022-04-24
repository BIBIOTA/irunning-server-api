<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Enum\StoragePathEnum;

class StoragePath implements Rule
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
        return StoragePathEnum::BANNER === $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The path name is not valid.';
    }
}
