<?php

declare(strict_types=1);

namespace App\Service;

use App\Validation\RepositoryUrlsValidation;

class ApiResponseComparisonService
{
    private GitHubApiService $gitHubApiService;

    public function __construct(GitHubApiService $gitHubApiService)
    {
        $this->gitHubApiService = $gitHubApiService;
    }

    public function getGitHubReposComparison(RepositoryUrlsValidation $repositoryUrlsValidation): array
    {
        $firstApiResponse = $this->gitHubApiService->prepareGitHubRepoData($repositoryUrlsValidation->getFirstUrl());
        $secondApiResponse = $this->gitHubApiService->prepareGitHubRepoData($repositoryUrlsValidation->getSecondUrl());

        $comparison = (null !== $firstApiResponse && null !== $secondApiResponse) ? $this->getComparison($firstApiResponse, $secondApiResponse) : [];
        return [
            'reposInfo' => [
                $firstApiResponse,
                $secondApiResponse,
            ],
            'comparison' => $comparison,
        ];
    }

    private function getComparison(ApiResponse $firstApiResponse, ApiResponse $secondApiResponse): array
    {
        return [
            $this->compare('forks', $firstApiResponse->getForksCount(), $secondApiResponse->getForksCount()),
            $this->compare('stars', $firstApiResponse->getStargazerCount(), $secondApiResponse->getStargazerCount()),
            $this->compare('watchers', $firstApiResponse->getWatchersCount(), $secondApiResponse->getWatchersCount()),
            $this->compare('openPulls', $firstApiResponse->getOpenPulls(), $secondApiResponse->getOpenPulls()),
            $this->compare('closedPulls', $firstApiResponse->getClosedPulls(), $secondApiResponse->getClosedPulls()),
            $this->compareReposByLatestReleaseTime($firstApiResponse, $secondApiResponse),
        ];
    }

    private function compare(string $comparedParam, int $firstValue, int $secondValue): Comparison
    {
        $comparison = new Comparison();
        $comparison->setComparedParam($comparedParam);
        $comparison->setQuantityDifference($this->getQuantityDifference($firstValue, $secondValue));
        $comparison->setPercentageDifference($this->getPercentageDifference($firstValue, $secondValue));

        return $comparison;
    }

    private function compareReposByLatestReleaseTime(ApiResponse $firstApiResponse, ApiResponse $secondApiResponse): string
    {
        if (null !== $firstApiResponse->getPublishedAt() && null !== $secondApiResponse->getPublishedAt()) {
            $releaseTimeDifference = $firstApiResponse->getPublishedAt()->diff($secondApiResponse->getPublishedAt());
            return sprintf('Difference between the latest releases is %d days, %d hours, %d minutes',
                    $releaseTimeDifference->d, $releaseTimeDifference->h, $releaseTimeDifference->i);
        }

        return 'Can\'t tell witch repository has newer version';
    }

    private function getQuantityDifference(int $firstValue, int $secondValue): int
    {
        return abs($firstValue - $secondValue);
    }

    private function getPercentageDifference(int $firstValue, int $secondValue): string
    {
        if(0 !== $firstValue && 0 !== $secondValue) {
            return round((($this->getQuantityDifference($firstValue, $secondValue) / (($firstValue + $secondValue) / 2)) * 100), 2).'%';
        }

        return '0%';
    }
}