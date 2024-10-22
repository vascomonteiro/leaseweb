<?php

namespace App\Service;

class DataFilter
{
    public function filterData(array $rows, array $filterRam, string $filterLocation, string $filterHddType, int $minHDDCapacity, int $maxHDDCapacity): array
    {
        return array_values(array_filter($rows, function ($item) use ($filterRam, $filterLocation, $filterHddType, $minHDDCapacity, $maxHDDCapacity) {
            return in_array($item['RAM'], $filterRam) && 
                   strpos($item['Location'], $filterLocation) !== false && 
                   strpos($item['HDD'], $filterHddType) !== false && 
                   $item['HDDGB'] >= $minHDDCapacity && 
                   $item['HDDGB'] <= $maxHDDCapacity;
        }));
    }
}
