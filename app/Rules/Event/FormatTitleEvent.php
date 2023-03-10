<?php

namespace App\Rules\Event;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class FormatTitleEvent implements ValidationRule
{
    /**
     * Indicates whether the rule should be implicit.
     *
     * @var bool
     */
    public $implicit = true;

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $checkEmpty = $this->checkEmpty($value);

        if (!empty($checkEmpty)) {
            $fail(json_encode($checkEmpty, JSON_FORCE_OBJECT));
        }
    }

    public function checkEmpty($value) {
        $message = [];
        if (empty($value) || !is_array($value)) {
            $message['empty'] = "Data is empty";
            return $message;
        }

        foreach ($value as $key => $val) {
            if (trim((string)$val['type'], '') == '') {
                $message[$key][] = "Type empty value in item";
            }

            if (trim((string)$val['content'], '') == '') {
                $message[$key][] = "Content empty value in item";
            }

            if ($val['date'] == '') {
                $message[$key][] = "Date empty value in item";
            }
        }

        return $message;
    }
}
