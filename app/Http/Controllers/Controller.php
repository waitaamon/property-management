<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function toast(string $message, string $type = 'success')
    {
        request()->session()->flash('toast', [
            'type' => $type,
            'message' => $message,
        ]);
    }
}
