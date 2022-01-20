<?php
/**
 * Vocabulary-specific data method console command.
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
namespace NatLibFi\SkosmosClient\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * Vocabulary-specific data method console command.
 */
class VocabularyDataCommand extends AbstractSkosmosCommand
{
    protected static $defaultName = 'skosmos:vocid/data';

    protected static $defaultDescription = 'RDF data of the whole vocabulary or a specific concept. If the vocabulary has support for it, MARCXML data is available for the whole vocabulary in each language';

    protected const CLIENT_METHOD_NAME = 'vocabularyData';

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setHelp('This command allows you to get the RDF data of the whole vocabulary or a specific concept. If the vocabulary has support for it, MARCXML data is available for the whole vocabulary in each language.');

        // Generate method/command related arguments array.
        $this->commandArgs = [
            new InputArgument(self::ARG_VOCID, InputArgument::REQUIRED, 'A Skosmos vocabulary identifier e.g. "stw" or "yso"'),
        ];

        // Generate method/command related options array.
        $this->commandOpts = [
            new InputOption(self::ARG_FORMAT, null, InputOption::VALUE_REQUIRED, 'The MIME type of the serialization format, e.g. "text/turtle" or "application/rdf+xml"'),
            new InputOption(self::ARG_URI, null, InputOption::VALUE_REQUIRED, 'URI of the desired concept. When no uri parameter is given, the whole vocabulary is returned instead'),
            new InputOption(self::ARG_LANG, null, InputOption::VALUE_REQUIRED, 'RDF language code when the requested resource for the MIME type is language specific, e.g. "fi" or "en"'),
        ];

        // Set common input options.
        parent::configure();
        // Set the complete input definition.
        $this->setCommandDefinition();
    }
}
