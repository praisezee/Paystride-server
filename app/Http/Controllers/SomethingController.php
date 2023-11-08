<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SomethingController extends Controller
{
    public function justAnExample()
    {
        return [
            'msg' => 'It works!'
        ];
    }
}
