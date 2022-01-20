<?php
/**
 * Vocabulary-specific new method console command.
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
 * Vocabulary-specific new method console command.
 */
class VocabularyNewCommand extends AbstractSkosmosCommand
{
    protected static $defaultName = 'skosmos:vocid/new';

    protected static $defaultDescription = 'New concepts in the vocabulary';

    protected const CLIENT_METHOD_NAME = 'vocabularyNew';

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setHelp('This command allows you to list the most recently created concepts of the vocabulary');

        // Generate method/command related arguments array.
        $this->commandArgs = [
            new InputArgument(self::ARG_VOCID, InputArgument::REQUIRED, 'A Skosmos vocabulary identifier e.g. "stw" or "yso"'),
        ];

        // Generate method/command related options array.
        $this->commandOpts = [
            new InputOption(self::ARG_LANG, null, InputOption::VALUE_REQUIRED, 'Language of labels to match, e.g. "en" or "fi"'),
            new InputOption(self::ARG_OFFSET, null, InputOption::VALUE_REQUIRED, 'Offset of the starting index', 0),
            new InputOption(self::ARG_LIMIT, null, InputOption::VALUE_REQUIRED, 'Maximum number of concepts to return', 200),
        ];

        // Set common input options.
        parent::configure();
        // Set the complete input definition.
        $this->setCommandDefinition();
    }
}
