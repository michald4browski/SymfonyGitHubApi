<?php

declare(strict_types=1);

namespace App\Validator;

use App\Validation\RepositoryUrlsValidation;
use App\Validation\Violation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiValidator
{
    private ValidatorInterface $validator;

    public function __construct(
        ValidatorInterface $validator
    ) {
        $this->validator = $validator;
    }

    public function validate(RepositoryUrlsValidation $repositoryUrlsValidation): array
    {
        $validationErrors = $this->validator->validate($repositoryUrlsValidation);

        $violations = [];

        if(count($validationErrors) > 0) {
            foreach ($validationErrors as $validationError) {
                $violation = new Violation();
                $violation->setParameter($validationError->getPropertyPath());
                $violation->setConstraint($validationError->getMessage());
                $violations[] = $violation;
            }
        }

        return $violations;
    }
}