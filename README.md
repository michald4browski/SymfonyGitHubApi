# SymfonyGitHubApi
The Symfony GitHub Api is simple application createt to give possibility of simple comparison of two GitHub repositories. Application compares parameter:
* forks
* stars
* watchers
* open and closed pulls
* latest release date
### Requirements
* PHP 7.4 or higher
* and the usual Symfony application requirements (https://symfony.com/doc/current/setup.html).
### Usage
If you have Symfony installed you just need to go to the project directory and run in terminal command:
* `symfony serve`
Now you can access application in browser at given URL (https://localhost:8000/ by default).
To compare GitHub repositories you need to have repos URLs looking like:
* `https://github.com/:user/:repoName`
You need to supply URLs as a arguments in application URL, for example like that:
* `http://localhost:8000/?firstUrl={firstUrl}&secondUrl={secondUrl}`
### Example
For example for repos: https://github.com/docker/compose and https://github.com/dotnet/core URL should look like that:
* `http://localhost:8000/?firstUrl=https://github.com/docker/compose&secondUrl=https://github.com/dotnet/core`
And answer from application will look like that:
```json
{
  "reposInfo": [
    {
      "repository": "https://github.com/docker/compose",
      "forksCount": 4070,
      "stargazerCount": 24320,
      "watchersCount": 24320,
      "latestRelease": {
        "date": "2021-11-08 11:36:34.000000",
        "timezone_type": 2,
        "timezone": "Z"
      },
      "openPulls": 30,
      "closedPulls": 0
    },
    {
      "repository": "https://github.com/dotnet/core",
      "forksCount": 4289,
      "stargazerCount": 16646,
      "watchersCount": 16646,
      "latestRelease": {
        "date": "2021-11-08 17:11:30.000000",
        "timezone_type": 2,
        "timezone": "Z"
      },
      "openPulls": 0,
      "closedPulls": 0
    }
  ],
  "comparison": [
    {
      "comparedParam": "forks",
      "quantityDifference": 219,
      "percentageDifference": "5.24%"
    },
    {
      "comparedParam": "stars",
      "quantityDifference": 7674,
      "percentageDifference": "37.47%"
    },
    {
      "comparedParam": "watchers",
      "quantityDifference": 7674,
      "percentageDifference": "37.47%"
    },
    {
      "comparedParam": "openPulls",
      "quantityDifference": 30,
      "percentageDifference": "0%"
    },
    {
      "comparedParam": "closedPulls",
      "quantityDifference": 0,
      "percentageDifference": "0%"
    },
    "Difference between the latest releases is 0 days, 5 hours, 34 minutes"
  ]
}

```
