<?php

namespace App\API;

use App\Service\ApiResponseComparisonService;
use App\Validation\RepositoryUrlsValidation;
use App\Validator\ApiValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

class GitHubApiController extends AbstractController
{
    private RequestStack $requestStack;
    private ApiResponseComparisonService $apiResponseComparisonService;
    private ApiValidator $apiValidator;

    public function __construct(
        RequestStack $requestStack,
        ApiResponseComparisonService $apiResponseComparisonService,
        ApiValidator $apiValidator
    ) {
        $this->requestStack = $requestStack;
        $this->apiResponseComparisonService = $apiResponseComparisonService;
        $this->apiValidator = $apiValidator;
    }

    public function index(): Response
    {
        $request = $this->requestStack->getCurrentRequest();

        $repositoryUrlValidation = new RepositoryUrlsValidation();
        $repositoryUrlValidation->setFirstUrl($request->get('firstUrl'));
        $repositoryUrlValidation->setSecondUrl($request->get('secondUrl'));

        $violations = $this->apiValidator->validate($repositoryUrlValidation);
        if(!empty($violations)) {
            return new JsonResponse($violations);
        }

        $response = $this->apiResponseComparisonService->getGitHubReposComparison($repositoryUrlValidation);
        return new JsonResponse($response);
    }
}