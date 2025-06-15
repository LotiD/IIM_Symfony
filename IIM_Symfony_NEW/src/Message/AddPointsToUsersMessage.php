<?php

namespace App\Message;

class AddPointsToUsersMessage
{
    private int $points;

    public function __construct(int $points)
    {
        $this->points = $points;
    }

    public function getPoints(): int
    {
        return $this->points;
    }
} 