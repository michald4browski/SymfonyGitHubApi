<?php

declare(strict_types=1);

namespace App\Validation;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

class RepositoryUrlsValidation
{
    private ?string $firstUrl = null;

    private ?string $secondUrl = null;

    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('firstUrl', new Assert\NotBlank());
        $metadata->addPropertyConstraint('firstUrl', new Assert\Url());
        $metadata->addPropertyConstraint('firstUrl', new Assert\Regex([
            'pattern' => '/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)(github+)\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/'
        ]));
        $metadata->addPropertyConstraint('secondUrl', new Assert\NotBlank());
        $metadata->addPropertyConstraint('secondUrl', new Assert\Url());
        $metadata->addPropertyConstraint('secondUrl', new Assert\Regex([
            'pattern' => '/^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/)(github+)\.[a-z]{2,5}(:[0-9]{1,5})?(\/.*)?$/'
        ]));
    }

    public function getFirstUrl(): ?string
    {
        return $this->firstUrl;
    }

    public function setFirstUrl(?string $firstUrl): void
    {
        $this->firstUrl = $firstUrl;
    }

    public function getSecondUrl(): ?string
    {
        return $this->secondUrl;
    }

    public function setSecondUrl(?string $secondUrl): void
    {
        $this->secondUrl = $secondUrl;
    }
}