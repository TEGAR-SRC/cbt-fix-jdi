<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;

class LogoutResponse implements LogoutResponseContract
{

    /**
     * toResponse
     *
     * @param  mixed $request
     * @return void
     */
    public function toResponse($request)
    {
    // Default Fortify logout response (admin/web). Students use /students/logout separately.
    return redirect('/login');
    }
}