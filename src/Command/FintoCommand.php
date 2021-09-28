<?php

namespace NatLibFi\Finto\PhpClient\Command;

use Laminas\Http\Client;
use Laminas\Log\Logger;
use Laminas\Log\Writer\Noop;
use NatLibFi\Finto\PhpClient\Finto;
use Symfony\Component\Console\Command\Command;

abstract class FintoCommand extends Command
{
    protected $logger;

    protected $finto;

    public function __construct()
    {
        parent::__construct();
        $this->logger = new Logger();
        $this->logger->addWriter(new Noop());
        $this->finto = new Finto(null, new Client());
        $this->finto->setLogger($this->logger);
    }
}
