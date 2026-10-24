<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class FileTypeValidate implements Rule
{
    protected $extensions;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($extensions)
    {
        $this->extensions = array_map(function ($item) {
            return strtolower(trim($item));
        }, is_array($extensions) ? $extensions : explode(',', (string)$extensions));
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
        if (!$value || !method_exists($value, 'getClientOriginalExtension')) {
            return false;
        }

        $ext = strtolower($value->getClientOriginalExtension());
        $dangerous = [
            'php', 'php3', 'php4', 'php5', 'php7', 'php8', 'phtml', 'phar', 'inc',
            'asp', 'aspx', 'jsp', 'jspx', 'cgi', 'pl', 'py', 'sh', 'bash', 'env',
            'exe', 'bat', 'cmd', 'com', 'htaccess', 'htpasswd', 'user.ini', 'ini'
        ];

        if (in_array($ext, $dangerous)) {
            return false;
        }

        return in_array($ext, $this->extensions);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute file type is not supported.';
    }
}
