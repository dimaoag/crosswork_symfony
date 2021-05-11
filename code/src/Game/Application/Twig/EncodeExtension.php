<?php

declare(strict_types=1);

namespace App\Game\Application\Twig;

use App\Game\Domain\Service\Encoder\LetterEncoderInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class EncodeExtension extends AbstractExtension
{
    private LetterEncoderInterface $encoder;

    public function __construct(LetterEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('base64', [$this, 'encode']),
        ];
    }

    public function encode(string $value): string
    {
        return $this->encoder->encode($value);
    }
}
