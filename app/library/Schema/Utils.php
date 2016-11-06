<?php

namespace Schema;

class Utils
{

    public static function connectionFromArray($results)
    {
        $edges = [];

        foreach ($results as $result) {
            $edges[] = [
                'node' => $result
            ];
        }

        return [
            'edges' => $edges
        ];
    }
}
