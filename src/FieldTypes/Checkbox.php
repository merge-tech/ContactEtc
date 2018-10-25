<?php

namespace WebDevEtc\ContactEtc\FieldTypes;

use Illuminate\Validation\Rule;

/**
 * Class Checkbox
 * @package WebDevEtc\ContactEtc\FieldTypes
 */
class Checkbox extends BaseFieldType
{

    /**
     * return the blade view that this field type should use
     *
     * @return string
     */
    public function getView()
    {
        return "contactetc::fields.Checkbox";
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            Rule::in('1'),
        ];
    }
}