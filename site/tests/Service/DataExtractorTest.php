<?php

namespace App\Tests\Service;

use PHPUnit\Framework\TestCase;
use App\Service\DataExtractor;

class DataExtractorTest extends TestCase
{
    private DataExtractor $extractor;

    protected function setUp(): void
    {
        $this->extractor = new DataExtractor();
    }

    public function testExtractData(): void
    {
        $filePath = __DIR__ . '/../fixtures/test_data.xlsx';

        // Simulate data extraction
        $result = $this->extractor->extractData($filePath);

        // Assertions
        $this->assertIsArray($result);
        $this->assertArrayHasKey('rows', $result);
        $this->assertArrayHasKey('locations', $result);
        $this->assertArrayHasKey('ramList', $result);
        $this->assertArrayHasKey('minHDDCapacity', $result);
        $this->assertArrayHasKey('maxHDDCapacity', $result);

        $this->assertGreaterThan(0, count($result['rows']));
        $this->assertContains('DallasDAL-10', $result['locations']);
        $this->assertContains('128GBDDR4', $result['ramList']);
        $this->assertEquals(120, $result['minHDDCapacity']);
        $this->assertEquals(24576, $result['maxHDDCapacity']);
    }
}