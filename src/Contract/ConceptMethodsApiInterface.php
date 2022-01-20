<?php
/**
 * Skosmos concept-specific methods API interface.
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
 * Skosmos concept-specific methods API interface.
 */
interface ConceptMethodsApiInterface extends VocabularyMethodsApiInterface
{
    /**
     * List of labels for the requested concept
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $uri   URI of the concept whose labels to return
     * @param string $lang  Search language, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array Labels for the requested concept
     */
    public function vocabularyLabel(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array;

    /**
     * Broader concepts of the requested concept
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $uri   URI of the concept whose broader concept to return
     * @param string $lang  Label language, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array The broader concept(s) of the requested concept or an empty array if there are none
     */
    public function vocabularyBroader(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array;

    /**
     * Broader transtive hierarchy for the requested concept
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $uri   URI of the concept whose broader transitive hierarchy to return
     * @param string $lang  Label language, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array The broader transitive hierarchy for the requested concept or an empty array if there concept does not have broaders

     */
    public function vocabularyBroaderTransitive(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array;

    /**
     * Narrower concepts of the requested concept
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $uri   URI of the concept whose narrower concept to return
     * @param string $lang  Label language, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array The narrower concept(s) of the requested concept or an empty array if there are none

     */
    public function vocabularyNarrower(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array;

    /**
     * Narrower transitive hierarchy for the requested concept
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $uri   URI of the concept whose narrower transitive hierarchy to return
     * @param string $lang  Label language, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array The narrower transitive hierarchy for the requested concept or an empty array if there concept does not have narrowers

     */
    public function vocabularyNarrowerTransitive(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array;

    /**
     * Related concepts of the requested concept
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $uri   URI of the concept whose related concept to return
     * @param string $lang  Label language, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array The related concept(s) of the requested concept or an empty array if there are none
     */
    public function vocabularyRelated(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array;

    /**
     * Narrower concepts of the requested concept
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $uri   URI of the concept whose narrower concepts to return
     * @param string $lang  Label language, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array Returns the children of the requested concept. The data is intended to be used in a hierarchical display.
     */
    public function vocabularyChildren(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array;

    /**
     * Members of the requested concept group
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $lang  Label language, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array Concept groups of the vocabulary
     */
    public function vocabularyGroupMembers(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array;

    /**
     * Hierarchical context of the requested concept
     *
     * @param string $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $uri   URI of the concept whose hierarchical context to return
     * @param string $lang  Label language, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array Returns the hierarchical context of the requested concept. The hierarchy is intended to be used in a hierarchical display.
     */
    public function vocabularyHierarchy(
        string $vocid,
        string $uri,
        string $lang = ''
    ): array;

    /**
     * Mappings associated with the requested concept
     *
     * @param string $vocid    A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string $uri      URI of the concept whose hierarchical context to return
     * @param bool   $external Indicates whether mappings to external vocabularies should be listed
     * @param string $clang    Content language, e.g. "en" or "fi"
     * @param string $lang     Label language, e.g. "en" or "fi"
     *
     * @throws RuntimeRequestException if sending the HTTP request failed
     * @throws BadRequestException for HTTP 400 Bad Request
     * @throws NotFoundException for HTTP 404 Not Found
     * @throws InvalidResponseException for a generic invalid response exception (status code other than 2XX, 400, 404 or invalid JSON response)
     *
     * @return array Returns the mappings associated with the requested concept. The result is a JSKOS-compatible JSON object
     */
    public function vocabularyMappings(
        string $vocid,
        string $uri,
        bool $external = true,
        string $clang = '',
        string $lang = ''
    ): array;
}
