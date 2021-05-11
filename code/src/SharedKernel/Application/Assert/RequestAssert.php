<?php

declare(strict_types=1);

namespace App\SharedKernel\Application\Assert;

use App\SharedKernel\Application\Exception\RequestException;
use Symfony\Component\HttpFoundation\Request;
use Webmozart\Assert\Assert;

final class RequestAssert extends Assert
{
    /**
     * @throws RequestException
     */
    public static function missingRequest(?Request $request): void
    {
        if (null === $request) {
            throw RequestException::missingRequest();
        }
    }
}
