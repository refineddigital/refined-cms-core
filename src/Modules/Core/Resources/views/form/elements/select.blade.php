{!!
    html()
        ->select($field->name, (array) $field->options)
        ->class('form__control')
        ->id('form--'.$field->name)
        ->attributes($attrs)
!!}