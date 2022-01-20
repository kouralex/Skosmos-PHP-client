<?php
/**
 * Skosmos vocabulary-specific methods API interface.
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
 * Skosmos vocabulary-specific methods API interface.
 */
interface VocabularyMethodsApiInterface
{
    /**
     * General information about the vocabulary
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $lang  Language of labels to match, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array Information about the requested vocabulary
     */
    public function vocabularyVocid(
        string $vocid,
        string $lang = ''
    ): array;

    /**
     * Information about the types (classes) of objects in the vocabulary
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $lang  Language of labels, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array Type information about the requested vocabulary
     */
    public function vocabularyTypes(
        string $vocid,
        string $lang = ''
    ): array;

    /**
     * Top concepts of the vocabulary
     *
     * @param string $vocid  A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $lang   Language of labels, e.g. "en" or "fi"
     * @param string $scheme Concept scheme whose top concepts to return. If not given, the default concept scheme of the vocabulary will be used.
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array Top concepts of the requested vocabulary
     */
    public function vocabularyTopConcepts(
        string $vocid,
        string $lang = '',
        string $scheme = ''
    ): array;

    /**
     * RDF data of the whole vocabulary or a specific concept. If the vocabulary has support for it, MARCXML data is available for the whole vocabulary in each language
     *
     * @param string $vocid  A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $format The MIME type of the serialization format, e.g "text/turtle" or "application/rdf+xml". If not specified, HTTP content negotiation (based on the Accept header) is used to determine a suitable serialization format from among the available ones.
     * @param string $uri    URI of the desired concept. When no uri parameter is given, the whole vocabulary is returned instead
     * @param string $lang   RDF language code when the requested resource for the MIME type is language specific, e.g. "fi" or "en"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404)
     *
     * @return array The RDF data of the requested vocabulary/concept, or MARCXML if the vocabulary supports such
     */
    public function vocabularyData(
        string $vocid,
        string $format = '',
        string $uri = '',
        string $lang = ''
    ): string;

    /**
     * Finds concepts and collections from a vocabulary by query term
     *
     * Implementation Notes
     * Returns a list of search results. The search is performed as a case-insensitive pattern, where an asterisk (*) may be used as wildcard. E.g. "cat*" may return results such as "CATCH-22" and "categorization". If decoded into RDF, the result is a vocabulary fragment expressed as SKOS.
     *
     * @param string $vocid   A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $query   The term to search for
     * @param string $lang    Language of labels to match, e.g. "en" or "fi"
     * @param string $type    Limit search to concepts of the given type, e.g. "skos:Concept"; multiple types can be specified as a space-separated list
     * @param string $parent  Limit search to concepts which have the given concept (specified by URI) as parent in their transitive broader hierarchy
     * @param string $group   Limit search to concepts in the given group (specified by URI)
     * @param int    $maxhits Maximum number of results to return. If not given, all results will be returned
     * @param int    $offset  Offset where to start in ther esult set, useful for paging the result. If not given, defaults to 0.
     * @param string $fields  Space-separated list of extra fields to include in the results. e.g. "related" or "prefLabel" or any other skos property
     * @param        $unique  Boolean flag to indicate that each concept should be returned only once, instead of returning all the different ways it could match (for example both via prefLabel and altLabel)
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array Data of the concepts matching the search term
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
    ): array;

    /**
     *  Look up concepts by label
     *
     * Implementation Notes
     * Returns the best matching concept(s) for the given label in JSON-LD format. In case the label matches several concepts with the same precedence, all of them are returned.
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $label The label to look for, e.g. "cat" or "dog"
     * @param string $lang  Search language, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array The best matching concept(s) for the given label. In case the label matches several concepts with the same precedence, all of them are returned.
     */
    public function vocabularyLookup(
        string $vocid,
        string $label,
        string $lang = ''
    ): array;

    /**
     * Number of Concepts and Collections in the vocabulary
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $lang  Language of labels, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array The concept and group counts for the vocabulary
     */
    public function vocabularyStatistics(
        string $vocid,
        string $lang = ''
    ): array;

    /**
     * Number of labels by language
     *
     * Implementation Notes
     * Returns a list of label (skos:prefLabel, skos:altLabel and skos:hiddenLabel) counts in all the different languages.
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $lang  Language of labels, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array The label counts for the vocabulary
     */
    public function vocabularyLabelStatistics(
        string $vocid,
        string $lang = ''
    ): array;

    /**
     * Initial letters of the alphabetical index
     *
     * Implementation Notes
     * Returns a list of the initial letters of labels (skos:prefLabel, skos:altLabel) in the given language, or the default language of the vocabulary. The special value "0-9" indicates the presence of labels starting with a number and the value "!*" indicates labels starting with a special character.
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $lang  Language of labels, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array Initial letters of the alphabetical index
     */
    public function vocabularyIndex(
        string $vocid,
        string $lang = ''
    ): array;

    /**
     * Concepts for a given letter in the alphabetical index
     *
     * Implementation Notes
     * Returns a list of the concepts which have a label (skos:prefLabel or skos:altLabel) starting with the given letter in the given language, or the default language of the vocabulary. The special value "0-9" matches labels starting with a number and the value "!*" matches labels starting with a special character.
     *
     * @param string $vocid  A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $letter An initial letter, or one of the special values "0-9 or "!*"
     * @param string $lang   Language of labels, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array Concepts of the alphabetical index
     */
    public function vocabularyIndexLetter(
        string $vocid,
        string $letter,
        string $lang = ''
    ): array;

    /**
     * Concept groups in the vocabulary
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $lang  Language of labels, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array Concept groups of the vocabulary
     */
    public function vocabularyGroups(
        string $vocid,
        string $lang = ''
    ): array;

    /**
     * New concepts in the vocabulary
     *
     * @param string $vocid  A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $lang   Label language, e.g. "en" or "fi"
     * @param int    $offset Offset of the starting index
     * @param int    $limit  Maximum number of concepts to return
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array List of most recently created concepts of the vocabulary
     */
    public function vocabularyNew(
        string $vocid,
        string $lang = '',
        int $offset = 0,
        int $limit = 200
    ): array;

    /**
     * Modified concepts in the vocabulary
     *
     * @param string $vocid  A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $lang   Label language, e.g. "en" or "fi"
     * @param int    $offset Offset of the starting index
     * @param int    $limit  Maximum number of concepts to return
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array List of most recently modified concepts of the vocabulary
     */
    public function vocabularyModified(
        string $vocid,
        string $lang = '',
        int $offset = 0,
        int $limit = 200
    ): array;
}
