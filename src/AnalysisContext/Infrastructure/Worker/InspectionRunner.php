<?php

/*
 * Regis – Static analysis as a service
 * Copyright (C) 2016-2017 Kévin Gomez <contact@kevingomez.fr>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace Regis\AnalysisContext\Infrastructure\Worker;

use League\Tactician\CommandBus;
use Regis\AnalysisContext\Application\Command;
use Regis\AnalysisContext\Domain\Model;
use Swarrot\Broker\Message;
use Swarrot\Processor\ProcessorInterface;

class InspectionRunner implements ProcessorInterface
{
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function process(Message $message, array $options)
    {
        $event = json_decode($message->getBody(), true);

        $repository = Model\Git\Repository::fromArray($event['repository']);
        $revisions = Model\Git\Revisions::fromArray($event['revisions']);

        $this->commandBus->handle(new Command\InspectRevisions($event['inspection_id'], $repository, $revisions));
    }
}
