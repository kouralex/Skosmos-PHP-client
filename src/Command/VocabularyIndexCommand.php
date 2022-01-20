<?php
/**
 * Vocabulary-specific index method console command.
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
 * Vocabulary-specific index method console command.
 */
class VocabularyIndexCommand extends AbstractSkosmosCommand
{
    protected static $defaultName = 'skosmos:vocid/index/';

    protected static $defaultDescription = 'Initial letters of the alphabetical index';

    protected const CLIENT_METHOD_NAME = 'vocabularyIndex';

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        $this->setHelp('This command allows you to get the initial letters of labels (skos:prefLabel, skos:altLabel) in the given language, or the default language of the vocabulary. The special value "0-9" indicates the presence of labels starting with a number and the value "!*" indicates labels starting with a special character.');

        // Generate method/command related arguments array.
        $this->commandArgs = [
            new InputArgument(self::ARG_VOCID, InputArgument::REQUIRED, 'A Skosmos vocabulary identifier e.g. "stw" or "yso"'),
        ];

        // Generate method/command related options array.
        $this->commandOpts = [
            new InputOption(self::ARG_LANG, null, InputOption::VALUE_REQUIRED, 'Language of labels, e.g. "en" or "fi"'),
        ];

        // Set common input options.
        parent::configure();
        // Set the complete input definition.
        $this->setCommandDefinition();
    }
}
