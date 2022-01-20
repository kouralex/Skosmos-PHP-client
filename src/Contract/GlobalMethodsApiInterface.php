<?php
/**
 * Skosmos global methods API interface.
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
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://skosmos.org/, https://github.com/NatLibFi/Skosmos, https://github.com/NatLibFi/Skosmos-PHP-Client
 */
namespace NatLibFi\SkosmosClient\Contract;

/**
 * Skosmos global methods API interface.
 */
interface GlobalMethodsApiInterface
{
    /**
     * List available vocabularies
     *
     * @param string $lang Language of labels, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array A list of vocabularies on the server
     */
    public function vocabularies(
        string $lang
    ): array;

    /**
     * Search concepts and collections by query term
     *
     * Implementation Notes
     * Returns a list of search results. The search is performed as a case-insensitive pattern, where an asterisk (*) may be used as wildcard. E.g. "cat*" may return results such as "CATCH-22" and "categorization".
     *
     * @param string $query     The term to search for e.g. "cat*"
     * @param string $lang      Language of labels to match, e.g. "en" or "fi"
     * @param string $labellang Language of labels to match, e.g. "en" or "fi"
     * @param string $vocab     Vocabulary/vocabularies to limit search to, e.g. "yso" or "yso allars"
     * @param string $type      Limit search to concepts of the given type, e.g. "skos:Concept"; multiple types can be specified as a space-separated list
     * @param string $parent    Limit search to concepts which have the given concept (specified by URI) as parent in their transitive broader hierarchy
     * @param string $group     Limit search to concepts in the given group (specified by URI)
     * @param int    $maxhits   Maximum number of results to return. If not given, all results will be returned
     * @param int    $offset    Offset where to start in ther esult set, useful for paging the result. If not given, defaults to 0.
     * @param string $fields    Space-separated list of extra fields to include in the results. e.g. "related" or "prefLabel" or any other skos property
     * @param bool   $unique    Boolean flag to indicate that each concept should be returned only once, instead of returning all the different ways it could match (for example both via prefLabel and altLabel)
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array Data of the concepts matching the search term.
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
    ): array;

    /**
     * List of labels for the requested concept
     *
     * @param string $uri  URI of the concept whose labels to return
     * @param string $lang Language of labels, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array Labels for the requested concept
     */
    public function label(
        string $uri,
        string $lang = ''
    ): array;

    /**
     * RDF data of the requested concept
     *
     * @param string $uri    URI of the concept whose data to return
     * @param string $format The MIME type of the serialization format, e.g. "text/turtle" or "application/rdf+xml"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404)
     *
     * @return string The data of the requested concept in the requested format or an empty string if the URI did not match any known concept
     */
    public function data(
        string $uri,
        string $format = ''
    ): string;

    /**
     * Information about the types (classes) of objects contained in all vocabularies
     *
     * @param string $lang Language of labels, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array Type information about all vocabularies
     */
    public function types(
        string $lang
    ): array;
}
