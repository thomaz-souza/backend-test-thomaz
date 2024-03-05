<?php

namespace App\Http\Controllers;

use App\Models\Redirect;

class RedirectController extends Controller
{
    public function show($code)
    {
        $id = Redirect::where('code', $code)->firstOrFail()->id;
        $redirect = app(Redirect::class)->findOrFail($id);
    }
}
