<?php
/**
 * Skosmos client implementation class.
 *
 * PHP version 7.3
 *
 * Copyright (c) 2021-2022 University Of Helsinki (The National Library Of Finland)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @category Skosmos
 * @package  Skosmos-PHP-client
 * @author   Alex Kourijoki <alex.kourijoki@helsinki.fi>
 * @author   Aleksi Peebles <aleksi.peebles@helsinki.fi>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://skosmos.org/, https://github.com/NatLibFi/Skosmos, https://github.com/NatLibFi/Skosmos-PHP-Client
 */
namespace NatLibFi\SkosmosClient;

use Laminas\Config\Config;
use Laminas\Http\Client;
use Laminas\Http\Client\Exception\RuntimeException;
use Laminas\Http\Exception\RuntimeException as ExceptionRuntimeException;
use Laminas\Http\Request;
use Laminas\Log\LoggerAwareInterface;
use Laminas\Log\LoggerAwareTrait;
use NatLibFi\SkosmosClient\Contract\SkosmosApiInterface;
use NatLibFi\SkosmosClient\Exceptions\BadRequestException;
use NatLibFi\SkosmosClient\Exceptions\InvalidResponseException;
use NatLibFi\SkosmosClient\Exceptions\MissingRestApiUrlException;
use NatLibFi\SkosmosClient\Exceptions\NotFoundException;
use NatLibFi\SkosmosClient\Exceptions\RuntimeRequestException;
use NatLibFi\SkosmosClient\SkosmosDefaultParameterObject;

/**
 * Skosmos client implementation class.
 */
class SkosmosClient implements SkosmosApiInterface, LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * Skosmos configuration.
     *
     * @var \Laminas\Config\Config
     */
    private $config;

    /**
     * HTTP client.
     *
     * @var \Laminas\Http\Client
     */
    private $client;

    /**
     * REST API base URL.
     *
     * @var string
     */
    protected $apiUrl;

    /**
     * Skosmos PHP client constructor.
     *
     * @param Config $config Skosmos configuration
     * @param Client $client HTTP client
     *
     * @throws MissingRestApiUrlException if Skosmos REST API base URL has not been provided via $config
     */
    public function __construct(Config $config, Client $client)
    {
        $this->config = $config;
        $this->apiUrl = $this->config->get('base_url');

        if (null === $this->apiUrl) {
            throw new MissingRestApiUrlException(
                'Instantiating Skosmos API client failed. Reason: missing Skosmos REST API base URL.' .
                PHP_EOL .
                PHP_EOL .
                'See https://github.com/NatLibFi/Skosmos-PHP-Client for more information on how to provide one.'
            );
        }

        $this->client = $client;

        // Set options
        $this->client->setOptions(
            [
                'timeout' => $this->config->get('http_timeout', 30),
                'useragent' => 'Skosmos PHP Client',
                'keepalive' => true,
                'strictredirects' => true
            ]
        );

        // Set Accept header
        $this->client->getRequest()->getHeaders()->addHeaderLine(
            'Accept',
            'application/json'
        );
    }

    /**
     * Normalize query
     *
     * @param string $query                 Query string
     * @param bool   $leftTruncationSearch  Left-truncated search
     * @param bool   $rightTruncationSearch Right-truncated search
     *
     * @return string A normalized query string
     */
    protected function normalizeQuery(
        string $query,
        bool $leftTruncationSearch = false,
        bool $rightTruncationSearch = false
    ): string {
        // prepare query string
        $normalizedQuery = ($leftTruncationSearch ? '*' : '') . $query . ($rightTruncationSearch ? '*' : '');
        // strip any multi-asterisk occurrences
        $normalizedQuery = preg_replace('/\*+/', '*', $normalizedQuery);

        // trim and strip any extra whitespace
        return trim(preg_replace('/\s+/', ' ', $normalizedQuery));
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularies(
        string $lang
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCABULARIES);

        $this->makeRequest(['vocabularies'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function search(
        string $query,
        string $lang = '',
        string $labellang = '',
        string $vocab = '',
        string $type = '',
        string $parent = '',
        string $group = '',
        int $maxhits = 0,
        int $offset = 0,
        string $fields = '',
        bool $unique = false
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::SEARCH);

        // Normalize query string.
        $params['query'] = $this->normalizeQuery($query);

        $this->makeRequest(['search'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function label(
        string $uri,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::LABEL);

        $this->makeRequest(['label'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function data(
        string $uri,
        string $format = ''
    ): string {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::DATA);

        $this->makeRequest(['data'], $params);

        $response = $this->client->getResponse();
        return $response->getBody();
    }

    /**
     * {@inheritDoc}
     */
    public function types(
        string $lang
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::TYPES);

        $this->makeRequest(['types'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyVocid(
        string $vocid,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID, false);

        $this->makeRequest([$vocid, ''], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyTypes(
        string $vocid,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_TYPES, false);

        $this->makeRequest([$vocid, 'types'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyTopConcepts(
        string $vocid,
        string $lang = '',
        string $scheme = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_TOPCONCEPTS, false);

        $this->makeRequest([$vocid, 'topConcepts'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyData(
        string $vocid,
        string $format = '',
        string $uri = '',
        string $lang = ''
    ): string {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_DATA, false);

        $this->makeRequest([$vocid, 'data'], $params);

        $response = $this->client->getResponse();
        return $response->getBody();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularySearch(
        string $vocid,
        string $query,
        string $lang = '',
        string $type = '',
        string $parent = '',
        string $group = '',
        int $maxhits = 0,
        int $offset = 0,
        string $fields = '',
        bool $unique = false
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_SEARCH, false);

        // Normalize query string.
        $params['query'] = $this->normalizeQuery($query);

        $this->makeRequest([$vocid, 'search'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyLookup(
        string $vocid,
        string $label,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_LOOKUP);
        unset($params['vocid']);

        $this->makeRequest([$vocid, 'lookup'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyStatistics(
        string $vocid,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_VOCABULARYSTATISTICS, false);

        $this->makeRequest([$vocid, 'vocabularyStatistics'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyLabelStatistics(
        string $vocid,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_LABELSTATISTICS, false);

        $this->makeRequest([$vocid, 'labelStatistics'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyIndex(
        string $vocid,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_INDEX, false);

        $this->makeRequest([$vocid, 'index', ''], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyIndexLetter(
        string $vocid,
        string $letter,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_INDEX_LETTER, false);

        $this->makeRequest([$vocid, 'index', $letter], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyGroups(
        string $vocid,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_GROUPS, false);

        $this->makeRequest([$vocid, 'groups'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyNew(
        string $vocid,
        string $lang = '',
        int $offset = 0,
        int $limit = 200
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_NEW, false);

        $this->makeRequest([$vocid, 'new'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyModified(
        string $vocid,
        string $lang = '',
        int $offset = 0,
        int $limit = 200
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_MODIFIED, false);

        $this->makeRequest([$vocid, 'modified'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyLabel(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_LABEL, false);

        $this->makeRequest([$vocid, 'label'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyBroader(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_BROADER);
        unset($params['vocid']);

        $this->makeRequest([$vocid, 'broader'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyBroaderTransitive(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_BROADERTRANSITIVE);
        unset($params['vocid']);

        $this->makeRequest([$vocid, 'broaderTransitive'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyNarrower(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_NARROWER);
        unset($params['vocid']);

        $this->makeRequest([$vocid, 'narrower'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyNarrowerTransitive(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_NARROWERTRANSITIVE);
        unset($params['vocid']);

        $this->makeRequest([$vocid, 'narrowerTransitive'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyRelated(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_RELATED);
        unset($params['vocid']);

        $this->makeRequest([$vocid, 'related'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyChildren(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_CHILDREN);
        unset($params['vocid']);

        $this->makeRequest([$vocid, 'children'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyGroupMembers(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_GROUPMEMBERS);
        unset($params['vocid']);

        $this->makeRequest([$vocid, 'groupMembers'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyHierarchy(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_HIERARCHY);
        unset($params['vocid']);

        $this->makeRequest([$vocid, 'hierarchy'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * {@inheritDoc}
     */
    public function vocabularyMappings(
        string $vocid,
        string $uri,
        bool $external = true,
        string $clang = '',
        string $lang = ''
    ): array {
        // Get all parameters.
        $argList = get_defined_vars();
        // Extract only non-default parameters.
        $params = SkosmosDefaultParameterObject::getChanged($argList, SkosmosApiRouter::VOCID_MAPPINGS);
        unset($params['vocid']);

        $this->makeRequest([$vocid, 'mappings'], $params);
        return $this->getDecodedResponse();
    }

    /**
     * Helper method for searching concepts and collections by query term with packed parameters and with/without truncation search.
     *
     * Implementation notes
     * This method unpacks parameters from the associative $packed array and determines whether to call global or vocabulary-specific search by the existence of associative vocabulary-specific search array key 'vocid' in the $packed array.
     *
     * @param string $query                 The term to search for
     * @param array  $packed                Keyed array of parameters accepted by Skosmos API's search or vocabularySearch method.
     * @param bool   $leftTruncationSearch  Whether to prepend the query string with asterisk for left-truncated search (default: false)
     * @param bool   $rightTruncationSearch Whether to append the query string with asterisk for right-truncated search (default: false)
     *
     * @throws ValueError if packed parameter was not of expected type
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404)
     *
     * @return array Search results
     */
    protected function packedSearch(
        string $query,
        array $packed = [],
        bool $leftTruncationSearch = false,
        bool $rightTruncationSearch = false
    ): array {
        // get API method
        $method = $route = isset($packed['vocid']) ? SkosmosApiRouter::VOCID_SEARCH : SkosmosApiRouter::SEARCH;
        // get changed parameters
        $params = SkosmosDefaultParameterObject::getChanged($packed, $method);

        // set query parameter
        $params['query'] = $this->normalizeQuery($query, $leftTruncationSearch, $rightTruncationSearch);

        if (isset($params['vocid'])) {
            // vocabulary search, substitute vocid to get the final route
            $route = SkosmosApiRouter::getSubstituted(['vocid' => trim($params['vocid'])], $method);
            // unset vocid parameter as it is already defined in $route
            unset($params['vocid']);
        }
        $this->makeRequest(explode('/', $route), $params);

        return $this->getDecodedResponse();
    }

    /**
     * Decodes the latest response.
     *
     * @throws InvalidResponseException if the JSON response data could not be decoded to an array
     *
     * @return array JSON response decoded to an associative array
     */
    protected function getDecodedResponse(): array
    {
        $response = $this->client->getResponse();
        $result = $response->getBody();
        $method = $this->client->getRequest()->getMethod();
        $apiUrl = $this->client->getRequest()->getUri();

        // Log and throw exception if the API call didn't return valid JSON that the caller can handle
        try {
            $decodedResult = json_decode($result, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $params = $method == Request::METHOD_GET
                ? $this->client->getRequest()->getQuery()->toString()
                : $this->client->getRequest()->getPost()->toString();
            $this->getLogger()->warn(
                "$method request for '$apiUrl' with params '$params' and contents '"
                . $this->client->getRequest()->getContent() . "' failed: "
                . $response->getStatusCode() . ': ' . $response->getReasonPhrase()
                . ', response content: ' . $response->getBody(),
                ['exception' => $e]
            );

            // Throw generic invalid response exception.
            throw new InvalidResponseException(
                $response->getStatusCode() . ' ' . $response->getReasonPhrase() . ' : ' . $response->getBody()
            );
        }

        return $decodedResult;
    }

    /**
     * Make Request.
     *
     * Makes a request to the Skosmos REST API.
     *
     * @param array  $hierarchy Array of values to embed in the URL path of the request
     * @param array  $params    A keyed array of query data
     * @param string $method    The http request method to use (Default is GET)
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404)
     */
    protected function makeRequest(
        array $hierarchy,
        array $params = [],
        string $method = 'GET'
    ): void {
        // Set up the request
        $apiUrl = $this->apiUrl;

        // Add hierarchy
        foreach ($hierarchy as $value) {
            $apiUrl .= '/' . urlencode($value);
        }

        $client = $this->client->setUri($apiUrl);

        // Add params
        if ($method == 'GET') {
            $client->setParameterGet($params);
        } else {
            $client->setParameterPost($params);
        }

        // Send request and retrieve response
        $startTime = microtime(true);
        try {
            $response = $client->setMethod($method)->send();
        } catch (RuntimeException|ExceptionRuntimeException $e) {
            $params = $method == Request::METHOD_GET
                ? $client->getRequest()->getQuery()->toString()
                : $client->getRequest()->getPost()->toString();
            throw new RuntimeRequestException("$method request for '$apiUrl' with parameters '$params' failed!", 0, $e);
        }

        $endTime = microtime(true);
        $result = $response->getBody();

        $this->getLogger()->debug(
            '[' . round($endTime - $startTime, 4) . 's]'
            . " $method request $apiUrl" . PHP_EOL . 'response: ' . PHP_EOL
            . $result
        );

        // Log and throw if the request did not succeed.
        if (!$response->isSuccess()) {
            $params = $client->getMethod() == Request::METHOD_GET
            ? $client->getRequest()->getQuery()->toString()
            : $client->getRequest()->getPost()->toString();

            $this->getLogger()->warn(
                "$method request for '$apiUrl' with params '$params' and contents '"
                . $client->getRequest()->getContent() . "' failed: "
                . $response->getStatusCode() . ': ' . $response->getReasonPhrase()
                . ', response content: ' . $response->getBody()
            );

            // TODO: support 304 responses

            // 400 bad request
            if ($response->getStatusCode() === 400) {
                throw new BadRequestException($result);
            }

            // 404 not found
            if ($response->isNotFound()) {
                throw new NotFoundException($result);
            }

            // Throw generic invalid response exception.
            throw new InvalidResponseException(
                $response->getStatusCode() . ' ' . $response->getReasonPhrase() . ' : ' . $response->getBody()
            );
        }
    }
}
