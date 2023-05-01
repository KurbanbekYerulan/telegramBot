<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function ViewResponse($view, $with = [])
    {
        return view($view)->with('data', $with);
    }

    public function RedirectResponse($route, $message)
    {
        return redirect()
            ->to($route)
            ->with($message);
    }
}
