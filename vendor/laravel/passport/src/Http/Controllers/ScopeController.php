<?php

namespace Laravel\Passport\Http\Controllers;

use Laravel\Passport\Passport;

class ScopeController
{
    /**
     * Get all of the available scopes for the application.
     *
     * @return Response
     */
    public function all()
    {
        return Passport::scopes();
    }
}
