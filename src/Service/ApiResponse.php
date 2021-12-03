<?php

declare(strict_types=1);

namespace App\Service;

class ApiResponse implements \JsonSerializable
{
    private string $repository;

    private int $forksCount = 0;

    private int $stargazerCount = 0;

    private int $watchersCount = 0;

    private ?\DateTime $publishedAt = null;

    private int $openPulls = 0;

    private int $closedPulls = 0;

    public function getRepository(): string
    {
        return $this->repository;
    }

    public function setRepository(string $repository): void
    {
        $this->repository = $repository;
    }

    public function getForksCount(): int
    {
        return $this->forksCount;
    }

    public function setForksCount(int $forksCount): void
    {
        $this->forksCount = $forksCount;
    }

    public function getStargazerCount(): int
    {
        return $this->stargazerCount;
    }

    public function setStargazerCount(int $stargazerCount): void
    {
        $this->stargazerCount = $stargazerCount;
    }

    public function getWatchersCount(): int
    {
        return $this->watchersCount;
    }

    public function setWatchersCount(int $watchersCount): void
    {
        $this->watchersCount = $watchersCount;
    }

    public function getPublishedAt(): ?\DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTime $publishedAt): void
    {
        $this->publishedAt = $publishedAt;
    }

    public function getOpenPulls(): int
    {
        return $this->openPulls;
    }

    public function setOpenPulls(int $openPulls): void
    {
        $this->openPulls = $openPulls;
    }

    public function getClosedPulls(): int
    {
        return $this->closedPulls;
    }

    public function setClosedPulls(int $closedPulls): void
    {
        $this->closedPulls = $closedPulls;
    }

    public function jsonSerialize()
    {
        return [
            'repository' => $this->getRepository(),
            'forksCount' => $this->getForksCount(),
            'stargazerCount' => $this->getStargazerCount(),
            'watchersCount' => $this->getWatchersCount(),
            'latestRelease' => $this->getPublishedAt(),
            'openPulls' => $this->getOpenPulls(),
            'closedPulls' => $this->getClosedPulls(),
        ];
    }
}