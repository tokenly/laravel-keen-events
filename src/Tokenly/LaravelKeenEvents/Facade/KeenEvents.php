<?php

namespace Tokenly\LaravelKeenEvents\Facade;

use Illuminate\Support\Facades\Facade;

class KeenEvents extends Facade {


    protected static function getFacadeAccessor() { return 'keenevents'; }


}
