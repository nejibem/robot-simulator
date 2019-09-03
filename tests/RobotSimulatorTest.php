<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Service\RobotSimulator;

class RobotSimulatorTest extends TestCase
{

    public function testValidCommands()
    {
        $rs = new RobotSimulator();

        $validCommands = [
            'PLACE 0,4,NORTH',
            'PLACE 1,2,EAST',
            'PLACE 0,0,NORTH',
            'PLACE 4,4,SOUTH',
            'PLACE 2,3,WEST',
            'MOVE',
            'LEFT',
            'RIGHT',
            'REPORT',
        ];

        foreach($validCommands as $command)
        {
            $this->assertTrue($rs->isCommandValid($command), "Command valid: $command");
        }
    }

    public function testInValidCommands()
    {
        $rs = new RobotSimulator();

        $validCommands = [
            'PLACE 5,5,EAST',
            'PLACE 0,5,NORTH',
            'PLACE 4,6,SOUTH',
            'PLACE 2,3,NOPE',
            'PLACE 2,3,INGEN',
            'yo',
            'wtf',
            'nope',
            'stop',
        ];

        foreach($validCommands as $command)
        {
            $this->assertFalse($rs->isCommandValid($command), "Command Invalid: $command");
        }
    }

    public function testMoveValid()
    {
        $rs = new RobotSimulator();

        $commands = [
            'PLACE 0,0,NORTH',
            'MOVE',
            'MOVE',
            'MOVE',
            'PLACE 0,4,EAST',
            'MOVE',
            'MOVE',
            'MOVE',
            'PLACE 4,4,SOUTH',
            'MOVE',
            'MOVE',
            'MOVE',
            'PLACE 4,0,WEST',
            'MOVE',
            'MOVE',
            'MOVE',
        ];

        foreach($commands as $i => $command)
        {
            $rs->runCommands([$command]);
            $this->assertTrue($rs->isMoveValid(), "Command $command ...Move Valid $i: $command");
        }

    }

    public function testMoveInValid()
    {
        $rs = new RobotSimulator();

        $facingOutPositions = [
            '2,4,NORTH',
            '4,2,EAST',
            '2,0,SOUTH',
            '0,2,WEST',
        ];

        foreach($facingOutPositions as $i => $facingOutPosition)
        {
            $rs->runCommands(["PLACE $facingOutPosition"]);
            $this->assertEquals($facingOutPosition, $rs->report());
            $this->assertEquals(FALSE, $rs->isMoveValid(), "Move Invalid $i: $facingOutPosition");
        }

    }

    public function testExampleA()
    {
        $rs = new RobotSimulator();

        $commands = [
            'PLACE 0,0,NORTH',
            'MOVE',
            'REPORT',
        ];
        $rs->runCommands($commands);

        $this->assertEquals('0,1,NORTH', $rs->report());
    }

    public function testExampleB()
    {
        $rs = new RobotSimulator();

        $commands = [
            'PLACE 0,0,NORTH',
            'LEFT',
            'REPORT',
        ];
        $rs->runCommands($commands);

        $this->assertEquals('0,0,WEST', $rs->report());
    }

    public function testExampleC()
    {
        $rs = new RobotSimulator();

        $commands = [
            'PLACE 1,2,EAST',
            'MOVE',
            'MOVE',
            'LEFT',
            'MOVE',
            'REPORT',
        ];
        $rs->runCommands($commands);

        $this->assertEquals('3,3,NORTH', $rs->report());
    }




}