<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GroupByOwnersServiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;

    /**
     * Test the groupByOwners method.
     */
    public function testGroupByOwners()
    {
        // Sample input files with corresponding owners
        $inputFiles = [
            "insurance.txt" => "Company A",
            "letter.docx" => "Company A",
            "Contract.docx" => "Company B",
        ];

        // Call the groupByOwners method
        $result = $this->groupByOwners($inputFiles);

        // Expected result after grouping by owners
        $expectedResult = [
            "Company A" => ["insurance.txt", "letter.docx"],
            "Company B" => ["Contract.docx"],
        ];

        // Assert that the result matches the expected result
        $this->assertEquals($expectedResult, $result);
    }

    /**
     * Group files by their owners.
     *
     * @param array $files Associative array with files as keys and owners as values.
     * @return array Associative array with owners as keys and arrays of files as values.
     */
    public function groupByOwners(array $files)
    {
        $result = [];

        // Group files by their owners
        foreach ($files as $file => $owner) {
            $result[$owner][] = $file;
        }

        return $result;
    }
}
