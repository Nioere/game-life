<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class GameLifeCommand extends Command
{
    protected static $defaultName = 'GameLife';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $field = $this->initializeField(10, 10);

        while (true) {

            if (!$this->hasAliveCells($field)) {
                $output->writeln("Все клетки мертвы. Игра окончена.");
                break;
            }

            $field = $this->calculateNextGeneration($field);

            $this->printField($field, $output);

            usleep(500000);
        }

        return Command::SUCCESS;
    }

    private function initializeField($width, $height)
    {
        $field = [];
        for ($i = 0; $i < $height; $i++) {
            for ($j = 0; $j < $width; $j++) {
                $field[$i][$j] = rand(0, 1);
            }
        }
        return $field;
    }

    private function calculateNextGeneration($field)
    {
        $nextField = [];
        $height = count($field);
        $width = count($field[0]);

        for ($i = 0; $i < $height; $i++) {
            for ($j = 0; $j < $width; $j++) {
                $aliveNeighbors = $this->countAliveNeighbors($field, $i, $j);
                if ($field[$i][$j] && $aliveNeighbors >= 2 && $aliveNeighbors <= 3) {
                    $nextField[$i][$j] = 1;
                } elseif (!$field[$i][$j] && $aliveNeighbors === 3) {
                    $nextField[$i][$j] = 1;
                } else {
                    $nextField[$i][$j] = 0;
                }
            }
        }

        return $nextField;
    }

    private function countAliveNeighbors($field, $i, $j)
    {
        $neighbors = 0;
        $height = count($field);
        $width = count($field[0]);

        for ($y = max(0, $i - 1); $y <= min($i + 1, $height - 1); $y++) {
            for ($x = max(0, $j - 1); $x <= min($j + 1, $width - 1); $x++) {
                $neighbors += $field[$y][$x];
            }
        }

        $neighbors -= $field[$i][$j];
        return $neighbors;
    }

    private function hasAliveCells($field)
    {
        foreach ($field as $row) {
            foreach ($row as $cell) {
                if ($cell) {
                    return true;
                }
            }
        }

        return false;
    }

    private function printField($field, OutputInterface $output)
    {
        //$output->write(sprintf("\033\143"));
        //$output->write(chr(27).chr(91).'H'.chr(27).chr(91).'J');
        for($i = 0; $i < 50; $i++){
            $output->writeln(PHP_EOL);
        }

        foreach ($field as $row) {
            foreach ($row as $cell) {
                $output->write($cell ? ' ■ ' : ' □ ');
            }
            $output->write(PHP_EOL);
        }
    }

}
