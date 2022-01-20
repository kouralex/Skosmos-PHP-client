<?php
/**
 * Abstract base class for all Skosmos client console commands.
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
 * @author   Aleksi Peebles <aleksi.peebles@helsinki.fi>
 * @license  https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     http://skosmos.org/, https://github.com/NatLibFi/Skosmos, https://github.com/NatLibFi/Skosmos-PHP-Client
 */
namespace NatLibFi\SkosmosClient\Command;

use Laminas\Config\Config;
use Laminas\Http\Client;
use Laminas\Log\Filter\Priority;
use Laminas\Log\Logger;
use Laminas\Log\Writer\Stream;
use NatLibFi\SkosmosClient\Contract\SkosmosExceptionInterface;
use NatLibFi\SkosmosClient\Exceptions\InvalidOptionValueException;
use NatLibFi\SkosmosClient\Exceptions\MissingRestApiUrlException;
use NatLibFi\SkosmosClient\Exceptions\RuntimeRequestException;
use NatLibFi\SkosmosClient\SkosmosClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use ValueError;

/**
 * Abstract base class for all Skosmos client console commands.
 */
abstract class AbstractSkosmosCommand extends Command
{
    /**
     * Note that const names below follow ARG_* naming pattern even though some of them may be options.
     */

    /**
     * Optional REST API URL argument name.
     *
     * @var string
     */
    protected const ARG_REST_API_URL = 'service';

    /**
     * Query argument name.
     *
     * @var string
     */
    protected const ARG_QUERY = 'query';

    /**
     * Vocabulary id / vocab(s)  argument name.
     *
     * @var string
     */
    protected const ARG_VOCABS = 'vocab';

    /**
     * Vocabulary id argument name.
     *
     * @var string
     */
    protected const ARG_VOCID = 'vocid';

    /**
     * URI argument name.
     *
     * @var string
     */
    protected const ARG_URI = 'uri';

    /**
     * Lang argument name.
     *
     * @var string
     */
    protected const ARG_LANG = 'lang';

    /**
     * Labellang argument name.
     *
     * @var string
     */
    protected const ARG_LABELLANG = 'labellang';

    /**
     * Type argument name.
     *
     * @var string
     */
    protected const ARG_TYPE = 'type';

    /**
     * Parent argument name.
     *
     * @var string
     */
    protected const ARG_PARENT = 'parent';

    /**
     * Group argument name.
     *
     * @var string
     */
    protected const ARG_GROUP = 'group';

    /**
     * Maxhits argument name.
     *
     * @var string
     */
    protected const ARG_MAXHITS = 'maxhits';

    /**
     * Offset argument name.
     *
     * @var string
     */
    protected const ARG_OFFSET = 'offset';

    /**
     * Fields argument name.
     *
     * @var string
     */
    protected const ARG_FIELDS = 'fields';

    /**
     * Unique argument name.
     *
     * @var string
     */
    protected const ARG_UNIQUE = 'unique';

    /**
     * Scheme argument name.
     *
     * @var string
     */
    protected const ARG_SCHEME = 'scheme';

    /**
     * Label argument name.
     *
     * @var string
     */
    protected const ARG_LABEL = 'label';

    /**
     * Format argument name.
     *
     * @var string
     */
    protected const ARG_FORMAT = 'format';

    /**
     * Letter argument name.
     *
     * @var string
     */
    protected const ARG_LETTER = 'letter';

    /**
     * Limit argument name.
     *
     * @var string
     */
    protected const ARG_LIMIT = 'limit';

    /**
     * External argument name.
     *
     * @var string
     */
    protected const ARG_EXTERNAL = 'external';

    /**
     * Clang argument name.
     *
     * @var string
     */
    protected const ARG_CLANG = 'clang';

    /**
     * Optional output argument name.
     *
     * @var string
     */
    protected const ARG_OUTPUT = 'output';

    /**
     * Optional prettified output argument name.
     *
     * @var string
     */
    protected const ARG_PRETTY_JSON = 'pretty-json';

    /**
     * Optional logging output argument name.
     *
     * @var string
     */
    protected const ARG_LOG = 'log';

    /**
     * Command arguments.
     *
     * @var array
     */
    protected $commandArgs = [];

    /**
     * General command options (e.g., --service).
     *
     * @var array
     */
    protected $generalOpts = [];

    /**
     * Command options.
     *
     * @var array
     */
    protected $commandOpts = [];

    /**
    * Client logger instance.
    *
    * @var \Laminas\Log\Logger
    */
    protected $logger;

    /**
    * Skosmos client.
    *
    * @var SkosmosClient
    */
    protected $skosmos;

    /**
     * Skosmos client method to be called in the non-abstract command.
     *
     * @var string
     */
    protected const CLIENT_METHOD_NAME = '';

    /**
     * Skosmos client class name. Override this to use a subclass implementation.
     *
     * @var string
     */
    protected const CLIENT_CLASS_NAME = SkosmosClient::class;

    /**
     * Prepares and gets Skosmos API method-specific parameters
     *
     * @param InputInterface $input Input values providing class
     *
     * @throws InvalidOptionValueException if an invalid value is given to an option
     *
     * @return array Skosmos API method-specific parameters
     */
    protected function getMethodParameters(InputInterface $input): array
    {
        $ret = [];

        // Skip command name (the first argument).
        $commandArgs = array_slice($input->getArguments(), 1);

        // Loop command arguments first.
        // TODO: support other than string values.
        foreach ($commandArgs as $k => $v) {
            // Array values are casted to a (space-separated) string via concatenation.
            if (is_array($v)) {
                array_push($ret, implode(' ', array_map('trim', $v)));
            } else {
                array_push($ret, $v);
            }
        }

        // Then, loop optional arguments.
        foreach ($this->commandOpts as $opt) {
            $val = $input->getOption($opt->getName());
            if (is_int($opt->getDefault())) {
                // Integer-expecting option, we need to validate the value.
                if (!is_int(filter_var($val, FILTER_VALIDATE_INT))) {
                    $msg = 'Expected integer value for option "' . $opt->getName() . '", got "' . $val . '".';
                    throw new InvalidOptionValueException($msg);
                }
                array_push($ret, (int)$val);
            } elseif ($opt->isNegatable() || !$opt->isValueRequired()) {
                // Reserved for boolean-expecting options.
                array_push($ret, (bool)$val);
            } elseif ($opt->isArray()) {
                // Array values are casted to a (space-separated) string via concatenation.
                array_push($ret, implode(' ', array_map('trim', $val)));
            } else {
                // String values.
                array_push($ret, $val ?? '');
            }
        }
        return $ret;
    }

    /**
     * Helper function for writing to a file. Directories will be created if needed.
     *
     * Adapted from https://www.php.net/manual/en/function.file-put-contents.php#120817
     *
     * @param string $dir        Filename (path)
     * @param string $contents   Content to write to file
     */
    protected static function file_force_contents(string $dir, string $contents)
    {
        $parts = explode('/', $dir);
        $file = array_pop($parts);
        $dir = '';

        foreach ($parts as $part) {
            if (!is_dir($dir .= "{$part}/")) {
                mkdir($dir);
            }
        }

        return file_put_contents("{$dir}{$file}", $contents);
    }

    /**
     * Writes either to a file or output stream
     *
     * @param string $contents Content to write to file
     * @param OutputInterface $output   Default output interface
     * @param string|null     $filename Filename (path) to write to
     * @param bool            $newLine  Whether to add a newline
     *
     * @throws RuntimeRequestException if the file could not be written
     *
     * @return int 0 if everything went fine, or an exit code
     */
    protected function writeToStream(
        string $contents,
        OutputInterface $output,
        ?string $filename = null,
        bool $newLine = true
    ): int {
        if (!empty($filename)) {
            $retVal = $this->file_force_contents($filename, $contents);
            if (false === $retVal) {
                // Failed to write to a file.
                $this->getApplication()->renderThrowable(
                    new RuntimeRequestException(
                        "Failed to write to $filename"
                    ),
                    $output
                );
                return Command::FAILURE;
            }
            return Command::SUCCESS;
        }
        $output->write($contents, $newLine);
        return Command::SUCCESS;
    }

    /**
     * {@inheritDoc}
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->logger = new Logger();

        $logTo = $input->getOption(self::ARG_LOG);
        if (isset($logTo) && !empty($logTo)) {
            $writer = new Stream($logTo, 'w');
        } else {
            $writer = new Stream('php://stderr');
        }

        /**
         * Please note that with --quiet the --output option will still write data to output stream
         * (but not directly to console) in case of err()-priority messages are being logged.
         * Fixing this behaviour would require either manually setting the verbosity to a lower value
         * at write time or different approach altogether.
         */

        // Set logging verbosity.
        $verb = decbin($output->getVerbosity());
        $verb_LEVEL = max(min(strlen($verb) - 2 - strpos($verb, '1'), 7), 0);
        $writer->addFilter(new Priority(intval($verb_LEVEL)));

        $this->logger->addWriter($writer);

        // Set configuration.
        // TODO: support configuration files via a --config option.
        $configArr = [];
        $apiUrl = $input->getOption(self::ARG_REST_API_URL);
        if (isset($apiUrl)) {
            $configArr['base_url'] = $apiUrl;
        }

        // Initialize SkosmosClient.
        try {
            $this->skosmos = $this->getApiClientImplementation($configArr);
        } catch (MissingRestApiUrlException $e) {
            throw $e;
        }

        // Set the logger.
        $this->skosmos->setLogger($this->logger);
    }

    /**
     * Get a Skosmos API Client object.
     *
     * @param array $configArr Skosmos configuration array
     *
     * @throws MissingRestApiUrlException if Skosmos REST API base URL has not been provided via $configArr
     *
     * @return SkosmosClient An instance of SkosmosClient
     */
    protected function getApiClientImplementation($configArr): SkosmosClient
    {
        $className = $this::CLIENT_CLASS_NAME;
        return new $className(new Config($configArr), new Client());
    }

    /**
     * Sets the complete command input definition.
     */
    protected function setCommandDefinition(): void
    {
        // Construct input definition array for this command.
        $inputDefs = array_merge(
            $this->commandArgs,
            $this->commandOpts,
            $this->generalOpts,
        );

        // Set the input definition.
        $this->setDefinition(new InputDefinition($inputDefs));
    }

    /**
     * {@inheritDoc}
     */
    protected function configure(): void
    {
        // Add general options.
        $this->generalOpts = [
            new InputOption(self::ARG_REST_API_URL, null, InputOption::VALUE_REQUIRED, 'The base URL of the Skosmos instance followed by /rest/v1/'),
            new InputOption(self::ARG_OUTPUT, 'o', InputOption::VALUE_REQUIRED, 'Output file name. Default is to use standard output'),
            new InputOption(self::ARG_PRETTY_JSON, null, InputOption::VALUE_NONE, 'Pretty-print JSON output'),
            new InputOption(self::ARG_LOG, 'O', InputOption::VALUE_REQUIRED, 'Log file name. Default is to use standard error'),
        ];
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Prepare API method parameters.
        try {
            $params = $this->getMethodParameters($input);
        } catch (InvalidOptionValueException $e) {
            $this->getApplication()->renderThrowable($e, $output);
            return Command::INVALID;
        }

        // Command-specific SkosmosClient method name.
        $method = static::CLIENT_METHOD_NAME;

        // Call the approriate SkosmosClient method with the given parameters.
        try {
            $result = $this->skosmos->$method(...$params);
        } catch (ValueError $e) {
            $this->getApplication()->renderThrowable($e, $output);
            return Command::INVALID;
        } catch (RuntimeRequestException $e) {
            $this->getApplication()->renderThrowable($e, $output);
            return Command::FAILURE;
        } catch (SkosmosExceptionInterface $e) {
            $this->getApplication()->renderThrowable($e, $output);
            return Command::FAILURE;
        }

        // If an output destination was given, write to it.
        $filename = $input->getOption(self::ARG_OUTPUT);
        // TODO: make vocabularyData() to use data-streaming as whole vocabularies may be quite large.

        // Handling of different formats (supported by data-related methods).
        try {
            // If the requested method supports format option and the requested format was not JSON.
            $format = trim($input->getOption(self::ARG_FORMAT));
            if (!in_array(
                $format,
                [
                    'application/ld+json',
                    'application/json',
                    ''
                ]
            )) {
                return $this->writeToStream($result, $output, $filename, false);
            }
            // Please note that the validity of JSON is not checked here.
            if (!$input->getOption(self::ARG_PRETTY_JSON)) {
                return $this->writeToStream($result, $output, $filename, false);
            }
            // In these methods, the JSON response is yet to be decoded or else the simple pretty printing via JSON encoding will not work.
            $result = json_decode($result);
            // TODO: test output validity or do not support pretty printing.
        } catch (InvalidArgumentException $e) {
            // Format option was not supported (default JSON response), let through.
        }
        // Please note that the validity of JSON has already been tested in the decoding phase, here we just encode the string back to JSON (default).
        $result = json_encode(
            $result,
            $input->getOption(
                self::ARG_PRETTY_JSON
            ) ? JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE : 0
        );
        return $this->writeToStream($result, $output, $filename, true);
    }
}
