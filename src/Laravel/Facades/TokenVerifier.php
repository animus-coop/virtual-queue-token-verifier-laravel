<?php

namespace VirtualQueue\TokenVerifier\Laravel\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array verifyToken(string $token)
 * @method static bool isTokenValid(string $token)
 * @method static array|null getFinishedLineDetails(string $token)
 */
class TokenVerifier extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \VirtualQueue\TokenVerifier\TokenVerifier::class;
    }
} 