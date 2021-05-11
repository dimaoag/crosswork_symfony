<?php

declare(strict_types=1);

namespace App\SharedKernel\Application\Response\API;

use App\SharedKernel\Application\Enum\HttpStatusCode;
use App\SharedKernel\Application\Enum\ResponseStatus;

/**
 * @psalm-immutable
 */
final class SuccessApiResponse implements ResponseInterface
{
    private int $status;
    private array $data;

    public function __construct(array $data = [], int $status = HttpStatusCode::HTTP_OK)
    {
        $this->data = $data;
        $this->status = $status;
    }

    public function body(): array
    {
        return [
            'status' => ResponseStatus::SUCCESS,
            'data' => $this->data,
        ];
    }

    public function status(): int
    {
        return $this->status;
    }
}
