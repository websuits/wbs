<?php
/**
 * @license MIT
 *
 * Modified by WebDevStudios on 23-February-2023 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace WebDevStudios\WPSWA\Algolia\AlgoliaSearch;

use WebDevStudios\WPSWA\Algolia\AlgoliaSearch\Config\PersonalizationConfig;
use WebDevStudios\WPSWA\Algolia\AlgoliaSearch\RequestOptions\RequestOptions;
use WebDevStudios\WPSWA\Algolia\AlgoliaSearch\RetryStrategy\ApiWrapper;
use WebDevStudios\WPSWA\Algolia\AlgoliaSearch\RetryStrategy\ApiWrapperInterface;
use WebDevStudios\WPSWA\Algolia\AlgoliaSearch\RetryStrategy\ClusterHosts;

final class PersonalizationClient
{
    /**
     * @var ApiWrapperInterface
     */
    private $api;

    /**
     * @var \WebDevStudios\WPSWA\Algolia\AlgoliaSearch\Config\PersonalizationConfig
     */
    private $config;

    /**
     * RecommendationClient constructor.
     */
    public function __construct(ApiWrapperInterface $api, PersonalizationConfig $config)
    {
        $this->api = $api;
        $this->config = $config;
    }

    /**
     * @param string|null $appId
     * @param string|null $apiKey
     * @param string|null $region
     *
     * @return PersonalizationClient
     */
    public static function create($appId = null, $apiKey = null, $region = null)
    {
        $config = PersonalizationConfig::create($appId, $apiKey, $region);

        return static::createWithConfig($config);
    }

    /**
     * @return PersonalizationClient
     */
    public static function createWithConfig(PersonalizationConfig $config)
    {
        $config = clone $config;

        if ($hosts = $config->getHosts()) {
            // If a list of hosts was passed, we ignore the cache
            $clusterHosts = ClusterHosts::create($hosts);
        } else {
            $clusterHosts = ClusterHosts::createForRecommendation($config->getRegion());
        }

        $apiWrapper = new ApiWrapper(
            Algolia::getHttpClient(),
            $config,
            $clusterHosts
        );

        return new self($apiWrapper, $config);
    }

    /**
     * @param array<string, int|string|array>|RequestOptions $requestOptions
     *
     * @return array<string, int|array>
     */
    public function getPersonalizationStrategy($requestOptions = [])
    {
        return $this->api->read('GET', api_path('/1/strategies/personalization'), $requestOptions);
    }

    /**
     * @param array<string, int|array>                       $strategy
     * @param array<string, int|string|array>|RequestOptions $requestOptions
     *
     * @return array<string, int|string>
     */
    public function setPersonalizationStrategy($strategy, $requestOptions = [])
    {
        return $this->api->write('POST', api_path('/1/strategies/personalization'), $strategy, $requestOptions);
    }
}
