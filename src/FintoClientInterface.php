<?php

namespace NatLibFi\Finto\PhpClient;

use Exception;

interface FintoClientInterface extends FintoApiInterface
{
    /**
     * Is the language supported by Finto.
     *
     * Can be used to determine whether to make an API call or not.
     *
     * @param string $lang Language code, e.g. "en" or "fi"
     *
     * @return bool
     */
    public function isSupportedLanguage(string $lang): bool;

    /**
     * Search concepts and collections by query term. Extend results with result type
     * and results from possible further queries.
     *
     * @param string      $query    The term to search for
     * @param string|null $lang     Language of labels to match, e.g. "en" or "fi"
     * @param array|null  $other    Keyed array of other parameters accepted by
     *                              Finto API's /search method
     * @param bool        $narrower Look for narrower concepts if applicable
     *
     * @return array Extended results or empty array if none
     * @throws Exception
     */
    public function extendedSearch(
        string $query,
        ?string $lang = null,
        ?array $other = null,
        bool $narrower = true
    ): array;
}
