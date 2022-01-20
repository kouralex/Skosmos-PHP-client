<?php
/**
 * Abstract Skosmos API router class.
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

/**
 * Abstract Skosmos API router class.
 */
abstract class SkosmosApiRouter
{
    /* Global methods */

    public const VOCABULARIES = 'vocabularies';

    public const SEARCH = 'search';

    public const LABEL = 'label';

    public const DATA = 'data';

    public const TYPES = 'types';

    /* Vocabulary-specific methods */

    public const VOCID = '{vocid}/';

    public const VOCID_TYPES = '{vocid}/types';

    public const VOCID_TOPCONCEPTS = '{vocid}/topConcepts';

    public const VOCID_DATA = '{vocid}/data';

    public const VOCID_SEARCH = '{vocid}/search';

    public const VOCID_LOOKUP = '{vocid}/lookup';

    public const VOCID_VOCABULARYSTATISTICS = '{vocid}/vocabularyStatistics';

    public const VOCID_LABELSTATISTICS = ' {vocid}/labelStatistics';

    public const VOCID_INDEX = '{vocid}/index/';

    public const VOCID_INDEX_LETTER = '{vocid}/index/{letter}';

    public const VOCID_GROUPS = '{vocid}/groups';

    public const VOCID_NEW = '{vocid}/new';

    public const VOCID_MODIFIED = '{vocid}/modified';

    /* Concept-specific methods */

    public const VOCID_LABEL = '{vocid}/label';

    public const VOCID_BROADER = '{vocid}/broader';

    public const VOCID_BROADERTRANSITIVE = '{vocid}/broaderTransitive';

    public const VOCID_NARROWER = '{vocid}/narrower';

    public const VOCID_NARROWERTRANSITIVE = '{vocid}/narrowerTransitive';

    public const VOCID_RELATED = '{vocid}/related';

    public const VOCID_CHILDREN = '{vocid}/children';

    public const VOCID_GROUPMEMBERS = '{vocid}/groupMembers';

    public const VOCID_HIERARCHY = '{vocid}/hierarchy';

    public const VOCID_MAPPINGS = '{vocid}/mappings';

    /**
     * Get final API method route via placeholder substitution(s).
     *
     * @param array  $substArr Associative array of substitutions-substitutives with which to replace any occurrenses in $route. Meaningful named keys include (but or not necessarily limited to) 'vocid' and 'letter'
     * @param string $route    SkosmosApiRouter const; the raw API method route yet to be used in substitutions. Placeholder values are enclosed in curly braces
     *
     * @return string Final, substituted method route
     */
    public static function getSubstituted(array $substArr, string $route): string
    {
        $ret = $route;
        foreach ($substArr as $key => $value) {
            $ret = strtr($ret, ["{{$key}}" => $value]);
        }
        return $ret;
    }
}
