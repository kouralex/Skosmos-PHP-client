<?php
/**
 * Abstract Skosmos default parameter object class.
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
namespace NatLibFi\SkosmosClient;

use NatLibFi\SkosmosClient\SkosmosApiRouter;
use ValueError;

/**
 * Abstract Skosmos default parameter object class.
 */
abstract class SkosmosDefaultParameterObject
{
    /**
     * Global vocabularies default parameters
     *
     * @var array
     */
    public const VOCABULARIES_DEFAULT = [
        'lang' => null
    ];

    /**
     * Global search method default parameters
     *
     * @var array
     */
    public const SEARCH_DEFAULT = [
        'query' => null,
        'lang' => '',
        'labellang' => '',
        'vocab' => '',
        'type' => '',
        'parent' => '',
        'group' => '',
        'maxhits' => 0,
        'offset' => 0,
        'fields' => '',
        'unique' => false
    ];

    /**
     * Global label method default parameters
     *
     * @var array
     */
    public const LABEL_DEFAULT = [
        'uri' => null,
        'lang' => ''
    ];

    /**
     * Global data method default parameters
     *
     * @var array
     */
    public const DATA_DEFAULT = [
        'uri' => null,
        'format' => ''
    ];

    /**
     * Global types method default parameters
     *
     * @var array
     */
    public const TYPES_DEFAULT = [
        'lang' => null
    ];

    /**
     * Vocabulary-specific vocid method default parameters
     *
     * @var array
     */
    public const VOCID_DEFAULT = [
        'vocid' => null,
        'lang' => ''
    ];

    /**
     * Vocabulary-specific types method default parameters
     *
     * @var array
     */
    public const VOCID_TYPES_DEFAULT = [
        'vocid' => null,
        'lang' => ''
    ];

    /**
     * Vocabulary-specific topConcepts method default parameters
     *
     * @var array
     */
    public const VOCID_TOPCONCEPTS_DEFAULT = [
        'vocid' => null,
        'lang' => '',
        'scheme' => ''
    ];

    /**
     * Vocabulary-specific data method default parameters
     *
     * @var array
     */
    public const VOCID_DATA_DEFAULT = [
        'vocid' => null,
        'format' => '',
        'uri' => '',
        'lang' => ''
    ];

    /**
     * Vocabulary-specific search method default parameters
     *
     * @var array
     */
    public const VOCID_SEARCH_DEFAULT = [
        'vocid' => null,
        'query' => null,
        'lang' => '',
        'type' => '',
        'parent' => '',
        'group' => '',
        'maxhits' => 0,
        'offset' => 0,
        'fields' => '',
        'unique' => false
    ];

    /**
     * Vocabulary-specific lookup method default parameters
     *
     * @var array
     */
    public const VOCID_LOOKUP_DEFAULT = [
        'vocid' => null,
        'label' => null,
        'lang' => ''
    ];

    /**
     * Vocabulary-specific vocabularyStatistics method default parameters
     *
     * @var array
     */
    public const VOCID_VOCABULARYSTATISTICS_DEFAULT = [
        'vocid' => null,
        'lang' => ''
    ];

    /**
     * Vocabulary-specific labelStatistics method default parameters
     *
     * @var array
     */
    public const VOCID_LABELSTATISTICS_DEFAULT = [
        'vocid' => null,
        'lang' => ''
    ];

    /**
     * Vocabulary-specific index method default parameters
     *
     * @var array
     */
    public const VOCID_INDEX_DEFAULT = [
        'vocid' => null,
        'lang' => ''
    ];

    /**
     * Vocabulary-specific indexLetter method default parameters
     *
     * @var array
     */
    public const VOCID_INDEX_LETTER_DEFAULT = [
        'vocid' => null,
        'letter' => null,
        'lang' => ''
    ];

    /**
     * Vocabulary-specific groups method default parameters
     *
     * @var array
     */
    public const VOCID_GROUPS_DEFAULT = [
        'vocid' => null,
        'lang' => ''
    ];

    /**
     * Vocabulary-specific new method default parameters
     *
     * @var array
     */
    public const VOCID_NEW_DEFAULT = [
        'vocid' => null,
        'lang' => '',
        'offset' => 0,
        'limit' => 200
    ];

    /**
     * Vocabulary-specific modified method default parameters
     *
     * @var array
     */
    public const VOCID_MODIFIED_DEFAULT = [
        'vocid' => null,
        'lang' => '',
        'offset' => 0,
        'limit' => 200
    ];

    /**
     * Concept-specific label method default parameters
     *
     * @var array
     */
    public const VOCID_LABEL_DEFAULT = [
        'vocid' => null,
        'uri' => null,
        'lang' => ''
    ];

    /**
     * Concept-specific broader method default parameters
     *
     * @var array
     */
    public const VOCID_BROADER_DEFAULT = [
        'vocid' => null,
        'uri' => null,
        'lang' => ''
    ];

    /**
     * Concept-specific broaderTransitive method default parameters
     *
     * @var array
     */
    public const VOCID_BROADERTRANSITIVE_DEFAULT = [
        'vocid' => null,
        'uri' => null,
        'lang' => ''
    ];

    /**
     * Narrower concept search in vocabulary default parameters
     *
     * @var array
     */
    public const VOCID_NARROWER_DEFAULT = [
        'vocid' => null,
        'uri' => null,
        'lang' => ''
    ];

    /**
     * Concept-specific narrowerTransitive method default parameters
     *
     * @var array
     */
    public const VOCID_NARROWERTRANSITIVE_DEFAULT = [
        'vocid' => null,
        'uri' => null,
        'lang' => ''
    ];

    /**
     * Concept-specific related method default parameters
     *
     * @var array
     */
    public const VOCID_RELATED_DEFAULT = [
        'vocid' => null,
        'uri' => null,
        'lang' => ''
    ];

    /**
     * Concept-specific children method default parameters
     *
     * @var array
     */
    public const VOCID_CHILDREN_DEFAULT = [
        'vocid' => null,
        'uri' => null,
        'lang' => ''
    ];

    /**
     * Vocabulary groupMembers parameters
     *
     * @var array
     */
    public const VOCID_GROUPMEMBERS_DEFAULT = [
        'vocid' => null,
        'uri' => null,
        'lang' => ''
    ];

    /**
     * Concept-specific hierarchy method default parameters
     *
     * @var array
     */
    public const VOCID_HIERARCHY_DEFAULT = [
        'vocid' => null,
        'uri' => null,
        'lang' => ''
    ];

    /**
     * Concept-specific mappings method default parameters
     *
     * @var array
     */
    public const VOCID_MAPPINGS_DEFAULT = [
        'vocid' => null,
        'uri' => null,
        'external' => true,
        'clang' => '',
        'lang' => ''
    ];

    /**
     * Auxiliary mapper between API methods and their respective default parameters
     *
     * @var array
     */
    public const HIERARCHY = [
        SkosmosApiRouter::VOCABULARIES => self::VOCABULARIES_DEFAULT,
        SkosmosApiRouter::SEARCH => self::SEARCH_DEFAULT,
        SkosmosApiRouter::LABEL => self::LABEL_DEFAULT,
        SkosmosApiRouter::DATA => self::DATA_DEFAULT,
        SkosmosApiRouter::TYPES => self::TYPES_DEFAULT,
        SkosmosApiRouter::VOCID => self::VOCID_DEFAULT,
        SkosmosApiRouter::VOCID_TYPES => self::VOCID_TYPES_DEFAULT,
        SkosmosApiRouter::VOCID_TOPCONCEPTS => self::VOCID_TOPCONCEPTS_DEFAULT,
        SkosmosApiRouter::VOCID_DATA => self::VOCID_DATA_DEFAULT,
        SkosmosApiRouter::VOCID_SEARCH => self::VOCID_SEARCH_DEFAULT,
        SkosmosApiRouter::VOCID_LOOKUP => self::VOCID_LOOKUP_DEFAULT,
        SkosmosApiRouter::VOCID_VOCABULARYSTATISTICS => self::VOCID_VOCABULARYSTATISTICS_DEFAULT,
        SkosmosApiRouter::VOCID_LABELSTATISTICS => self::VOCID_LABELSTATISTICS_DEFAULT,
        SkosmosApiRouter::VOCID_INDEX => self::VOCID_INDEX_DEFAULT,
        SkosmosApiRouter::VOCID_INDEX_LETTER => self::VOCID_INDEX_LETTER_DEFAULT,
        SkosmosApiRouter::VOCID_GROUPS => self::VOCID_GROUPS_DEFAULT,
        SkosmosApiRouter::VOCID_NEW => self::VOCID_NEW_DEFAULT,
        SkosmosApiRouter::VOCID_MODIFIED => self::VOCID_MODIFIED_DEFAULT,
        SkosmosApiRouter::VOCID_LABEL => self::VOCID_LABEL_DEFAULT,
        SkosmosApiRouter::VOCID_BROADER => self::VOCID_BROADER_DEFAULT,
        SkosmosApiRouter::VOCID_BROADERTRANSITIVE => self::VOCID_BROADERTRANSITIVE_DEFAULT,
        SkosmosApiRouter::VOCID_NARROWER => self::VOCID_NARROWER_DEFAULT,
        SkosmosApiRouter::VOCID_NARROWERTRANSITIVE => self::VOCID_NARROWERTRANSITIVE_DEFAULT,
        SkosmosApiRouter::VOCID_RELATED => self::VOCID_RELATED_DEFAULT,
        SkosmosApiRouter::VOCID_CHILDREN => self::VOCID_CHILDREN_DEFAULT,
        SkosmosApiRouter::VOCID_GROUPMEMBERS => self::VOCID_GROUPMEMBERS_DEFAULT,
        SkosmosApiRouter::VOCID_HIERARCHY => self::VOCID_HIERARCHY_DEFAULT,
        SkosmosApiRouter::VOCID_MAPPINGS => self::VOCID_MAPPINGS_DEFAULT,
    ];

    /**
     * Strips extra and default parameters (if set) for a route.
     *
     * Implementation notes
     * Mandatory parameters are expected to be of type string (hence, no true type checking)
     *
     * @param array  $arr           The associative array of parameters
     * @param string $method        SkosmosApiRouter const; the route with respect to get the changed parameters
     * @param bool   $keepMandatory Whether to keep mandatory parameters or not (default: true)
     *
     * @throws ValueError if $arr key value was not of the expected type
     *
     * @return array Changed parameters
     */
    public static function getChanged(array $arr, string $method, bool $keepMandatory = true): array
    {
        return array_filter($arr, function ($v, $k) use ($method, $keepMandatory): bool {
            // Filtering out null values (mandatory parameters) if requested.
            if (!$keepMandatory && is_null($v)) {
                return false;
            }

            // Filter out any extra key values.
            if (!array_key_exists($k, self::HIERARCHY[$method])) {
                return false;
            }

            // The default value.
            $c = self::HIERARCHY[$method][$k];

            // Mandatory arguments have the default value set to null.
            if (is_null($c)) {
                // Expect the mandatory parameter values (denoted as 'null' in default parameters) to be of type 'string'.
                if (!is_string($v)) {
                    throw new ValueError(
                        "Expected parameter '" .
                        $k .
                        "' value to be of type 'string', got '" .
                        gettype($v) .
                        "'."
                    );
                }
                // Accept all other values.
                return true;
            }
            // Ensure type safety.
            if (gettype($c) != gettype($v)) {
                throw new ValueError(
                    "Expected parameter '" .
                    $k .
                    "' value to be of type '" .
                    gettype($c) .
                    "', got '" .
                    gettype($v) .
                    "'."
                );
            }

            // Special trimming treatment for strings.
            if (is_string($v)) {
                // Please note that trimming is only used for checking, i.e., the value is returned unchanged.
                return trim($v) !== $c;
            }
            // In case the default parameter is of type 'bool', cast the parameter value to bool before testing.
            if (is_bool($c)) {
                return (bool)$v !== $c;
            }
            return $v !== $c;
        }, ARRAY_FILTER_USE_BOTH);
    }
}
