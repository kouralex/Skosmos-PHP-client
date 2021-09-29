<?php

namespace NatLibFi\Finto\PhpClient;

use Exception;

interface FintoApiInterface
{
    /**
     * Search concepts and collections by query term.
     *
     * @param string      $query The term to search for
     * @param string|null $lang  Language of labels to match, e.g. "en" or "fi"
     * @param array|null  $other Keyed array of other parameters accepted by Finto
     *                           API's /search method
     *
     * @return array Results
     * @throws Exception
     */
    public function search(
        string $query,
        ?string $lang = null,
        ?array $other = null
    ): array;

    /**
     * Narrower concepts of the requested concept.
     *
     * @param string      $vocid A Skosmos vocabulary identifier e.g. "stw" or "yso"
     * @param string      $uri   URI of the concept whose narrower concept to return
     * @param string|null $lang  Label language, e.g. "en" or "fi"
     * @param boolean     $sort  Whether to sort results alphabetically or not
     *
     * @return array Results
     * @throws Exception
     */
    public function narrower(
        string $vocid,
        string $uri,
        ?string $lang = null,
        bool $sort = false
    ): array;
}
