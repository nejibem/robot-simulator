<?php

namespace App\Service;

class RobotSimulator
{
    const DIRECTION_NORTH = 'NORTH';
    const DIRECTION_EAST = 'EAST';
    const DIRECTION_SOUTH = 'SOUTH';
    const DIRECTION_WEST = 'WEST';

    const COMMAND_PLACE = 'PLACE';
    const COMMAND_MOVE = 'MOVE';
    const COMMAND_LEFT = 'LEFT';
    const COMMAND_RIGHT = 'RIGHT';
    const COMMAND_REPORT = 'REPORT';

    private $simpleCommands = [
        self::COMMAND_MOVE,
        self::COMMAND_LEFT,
        self::COMMAND_RIGHT,
        self::COMMAND_REPORT,
    ];

    private $currentPositionX = null;
    private $currentPositionY = null;
    private $currentDirection = null;


    public function isCommandValid(string $command) :bool
    {
        return in_array($command, $this->simpleCommands) || $this->isPlaceValid($command);
    }

    public function isPlaceValid($command) :bool
    {
        return count($this->parsePlace($command)) === 4;
    }

    public function isMoveValid() :bool
    {
        if($this->currentPositionY === 4 && $this->currentDirection === self::DIRECTION_NORTH)
        {
            return false;
        }
        elseif($this->currentPositionX === 4 && $this->currentDirection === self::DIRECTION_EAST)
        {
            return false;
        }
        elseif($this->currentPositionY === 0 && $this->currentDirection === self::DIRECTION_SOUTH)
        {
            return false;
        }
        elseif($this->currentPositionX === 0 && $this->currentDirection === self::DIRECTION_WEST)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function runCommands(array $commands) :self
    {
        foreach($commands as $command)
        {
            $this->runCommand($command);
        }
        return $this;
    }

    public function report()
    {
        return implode([
            $this->currentPositionX,
            $this->currentPositionY,
            $this->currentDirection
        ], ",");
    }

    private function parsePlace($command) :array
    {
        preg_match("/PLACE (0|1|2|3|4){1},(0|1|2|3|4){1},(NORTH|EAST|SOUTH|WEST)+/", $command, $matches);
        return $matches;
    }

    private function runCommand(string $command) :void
    {
        if($command === self::COMMAND_MOVE && $this->isMoveValid($command))
        {
            $this->move();
        }
        elseif($command === self::COMMAND_RIGHT)
        {
            $this->rotateRight();
        }
        elseif($command === self::COMMAND_LEFT)
        {
            $this->rotateLeft();
        }
        elseif($this->isPlaceValid($command)) {
            $this->place($command);
        }

    }

    private function move() :void
    {
        if($this->currentDirection === self::DIRECTION_NORTH)
        {
            ++$this->currentPositionY;
        }
        elseif($this->currentDirection === self::DIRECTION_EAST)
        {
            ++$this->currentPositionX;
        }
        elseif($this->currentDirection === self::DIRECTION_SOUTH)
        {
            --$this->currentPositionY;
        }
        elseif($this->currentDirection === self::DIRECTION_WEST)
        {
            --$this->currentPositionX;
        }
    }

    private function place(string $command) :void
    {
        $newPlace = $this->parsePlace($command);
        $this->currentPositionX = (int) $newPlace[1];
        $this->currentPositionY = (int) $newPlace[2];
        $this->currentDirection = (string) $newPlace[3];
    }

    private function rotateRight() :void
    {
        if($this->currentDirection === self::DIRECTION_NORTH)
        {
            $this->currentDirection = self::DIRECTION_EAST;
        }
        elseif($this->currentDirection === self::DIRECTION_EAST)
        {
            $this->currentDirection = self::DIRECTION_SOUTH;
        }
        elseif($this->currentDirection === self::DIRECTION_SOUTH)
        {
            $this->currentDirection = self::DIRECTION_WEST;
        }
        elseif($this->currentDirection === self::DIRECTION_WEST)
        {
            $this->currentDirection = self::DIRECTION_NORTH;
        }
    }

    private function rotateLeft() :void
    {
        if($this->currentDirection === self::DIRECTION_NORTH)
        {
            $this->currentDirection = self::DIRECTION_WEST;
        }
        elseif($this->currentDirection === self::DIRECTION_EAST)
        {
            $this->currentDirection = self::DIRECTION_NORTH;
        }
        elseif($this->currentDirection === self::DIRECTION_SOUTH)
        {
            $this->currentDirection = self::DIRECTION_EAST;
        }
        elseif($this->currentDirection === self::DIRECTION_WEST)
        {
            $this->currentDirection = self::DIRECTION_SOUTH;
        }
    }

}