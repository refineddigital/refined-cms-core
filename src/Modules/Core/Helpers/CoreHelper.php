<?php

namespace RefinedDigital\CMS\Modules\Core\Helpers;

use Illuminate\Support\Traits\Macroable;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Http\Request;

class CoreHelper {
  use Macroable;

  public function objToAttr($data) {
    return $this->arrayToAttr((array) $data);
  }

  public function arrayToAttr($data)
  {
    $bits = [];
    foreach ($data as $key => $value) {
      $bits[] = $key.'="'.$value.'"';
    }

    return implode(' ', $bits);
  }
}
