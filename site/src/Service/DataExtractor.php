<?php

namespace App\Service;

use Shuchkin\SimpleXLSX;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Contracts\Cache\ItemInterface;

class DataExtractor
{
    public function extractData(string $filePath): array
    {
        $cache = new FilesystemAdapter();
        // $cache->delete('server_list_cache_key');
        // The callable will only be executed on a cache miss.
        $dataTable = $cache->get('server_list_cache_key', function (ItemInterface $item) use ($filePath) {
            $item->expiresAfter(3600);
            if (!$xlsx = SimpleXLSX::parse($filePath)) {
                throw new \RuntimeException(SimpleXLSX::parseError());
            }

            $headerValues = [];
            $rows = [];
            $locations = [];
            $minHDDCapacity = 100000;
            $maxHDDCapacity = 0;

            // create array with all data
            foreach ($xlsx->rows() as $key => $r) {
                
                // get valid columns from XLSX
                $r = array_slice($r, 0, -4);

                // extract column names
                if ($key === 0) {
                    $headerValues = $r;

                    // add extra column to compute HDD in GB (ex: 2x500GBSATA2, 8x3TBSATA2 )
                    $headerValues[] = 'HDDNumber';
                    $headerValues[] = 'HDDUnity';
                    $headerValues[] = 'HDDGB';
                    continue;
                }

                // ex of value of r[2] --> 8x3TBSATA2
                // get firsts digits (HDDNumber)
                try{
                    preg_match('/^\d+/', $r[2], $matches);
                    $firstDigits = $matches[0] ?? '';
                    $r[] = $firstDigits;
        
                    // get digits after x (HDDUnity)
                    preg_match('/x(\d+)/', $r[2], $matches);
                    $secondDigits = $matches[1] ?? '';
                    $r[] = $secondDigits;
                    $HDDCapacity = 0;
        
                    // compute HDD capacity in GB
                    if (strpos($r[2], 'GB') !== false) {
                        $HDDCapacity = $firstDigits * $secondDigits;
                    } elseif (strpos($r[2], 'TB') !== false) {
                        $HDDCapacity = $firstDigits * $secondDigits * 1024;
                    }
        
                    $r[] = $HDDCapacity;
                    $minHDDCapacity = min($minHDDCapacity, $HDDCapacity);
                    $maxHDDCapacity = max($maxHDDCapacity, $HDDCapacity);
        
                    $rows[] = array_combine($headerValues, $r);

                } catch (\Exception $e) {
                    throw new \Exception('Error: File with invalid data.');
                }
            }

            $locations = array_values(array_unique(array_column($rows, 'Location')));
            sort($locations);
            return array('rows'=>$rows, 'locations'=>$locations, 'minHDDCapacity'=>$minHDDCapacity, 'maxHDDCapacity'=>$maxHDDCapacity);
        });

        return [
            'rows' => $dataTable['rows'],
            'locations' => $dataTable['locations'],
            'ramList' => array_values(array_unique(array_column($dataTable['rows'], 'RAM'))),
            'minHDDCapacity' => $dataTable['minHDDCapacity'],
            'maxHDDCapacity' => $dataTable['maxHDDCapacity']
        ];
    }
}
