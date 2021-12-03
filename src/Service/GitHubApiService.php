<?php

declare(strict_types=1);

namespace App\Service;


use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class GitHubApiService
{
    private const REQUEST_TIMEOUT = 2.0;

    private const PULL_STATUS_OPEN = 'open';

    private const PULL_STATUS_CLOSED = 'closed';

    private const GITHUB_URL = 'https://github.com/';

    private string $githubApiUrl;

    private HttpClientInterface $client;

    public function __construct(
        string $githubApiUrl,
        HttpClientInterface $client
    ) {
        $this->githubApiUrl = $githubApiUrl;
        $this->client = $client;
    }

    public function prepareGitHubRepoData(string $repo): ?ApiResponse
    {
        try {
            $extractedRepo = $this->extractDataFromUrl($repo);
            $apiResponse = new ApiResponse();
            $dataFromGitHubApi = $this->getDataFromGitHubApi($extractedRepo);
            $apiResponse->setRepository($repo);
            $apiResponse->setForksCount($dataFromGitHubApi['forks_count']);
            $apiResponse->setStargazerCount($dataFromGitHubApi['stargazers_count']);
            $apiResponse->setWatchersCount($dataFromGitHubApi['watchers_count']);
            if(null !== $this->getLatestReleaseDateFromGitHubApi($extractedRepo)) {
                $apiResponse->setPublishedAt(new \DateTime($this->getLatestReleaseDateFromGitHubApi($extractedRepo)));
            }
            $pullDataFromGitHubApi = $this->getPullDataFromGitHubApi($extractedRepo);
            $apiResponse->setOpenPulls($pullDataFromGitHubApi['pull_open']);
            $apiResponse->setClosedPulls($pullDataFromGitHubApi['pull_closed']);

            return $apiResponse;
        } catch (\Exception $e) {
            //do nothing
        }
        return null;
    }

    public function getDataFromGitHubApi(string $repo): array
    {
        $result = [];
        try {
            $response = $this->client->request(
                'GET',
                sprintf('%s%s', $this->githubApiUrl, $repo),
                [
                    'headers' => [
                        'Accept' => 'application/vnd.github.v3+json',
                        'Content-Type' => 'application/json',
                        'timeout' => self::REQUEST_TIMEOUT,
                    ],
                ]
            );

            $result = json_decode($response->getContent(), true);
        } catch (HttpExceptionInterface | TransportExceptionInterface $e) {
            //do nothing
        }

        return $result;
    }

    public function getLatestReleaseDateFromGitHubApi(string $repo): ?string
    {
        try {
            $response = $this->client->request(
                'GET',
                sprintf('%s%s/releases/latest', $this->githubApiUrl, $repo),
                [
                    'headers' => [
                        'Accept' => 'application/vnd.github.v3+json',
                        'Content-Type' => 'application/json',
                        'timeout' => self::REQUEST_TIMEOUT,
                    ],
                ]
            );

            $responseContent = json_decode($response->getContent(), true);
            return $responseContent['published_at'];
        } catch (HttpExceptionInterface | TransportExceptionInterface $e) {
            //do nothing
        }

        return null;
    }

    public function getPullDataFromGitHubApi(string $repo): array
    {
        $result = [];
        try {
            $response = $this->client->request(
                'GET',
                sprintf('%s%s/pulls', $this->githubApiUrl, $repo),
                [
                    'headers' => [
                        'Accept' => 'application/vnd.github.v3+json',
                        'Content-Type' => 'application/json',
                        'timeout' => self::REQUEST_TIMEOUT,
                    ],
                ]
            );

            $responseContent = json_decode($response->getContent(), true);
            $result['pull_open'] = $this->countPullsByStatus($responseContent, self::PULL_STATUS_OPEN);
            $result['pull_closed'] = $this->countPullsByStatus($responseContent, self::PULL_STATUS_CLOSED);
            return $result;
        } catch (HttpExceptionInterface | TransportExceptionInterface $e) {
            //do nothing
        }
        return $result;
    }

    private function countPullsByStatus(array $responseContent, string $status): int
    {
        $counter = 0;

        foreach ($responseContent as $item) {
            if($status === $item['state']) {
                $counter++;
            }
        }
        return $counter;
    }

    private function extractDataFromUrl(string $url): string
    {
        return str_replace(self::GITHUB_URL, '', $url);
    }
}