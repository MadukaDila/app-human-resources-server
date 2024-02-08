<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DuplicatesController extends Controller
{
    /**
     * Find duplicates in the provided array.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function duplicates(Request $request)
    {
        // Retrieve JSON data from the request
        $inputData = $request->json()->all();

        // Ensure that the 'N' key exists in the request data
        if (!isset($inputData['N']) || !isset($inputData['a'])) {
            return response()->json(['error' => 'Invalid input. N and a keys are required.'], 400);
        }

        try {
            // Extract values from the input data
            $N = $inputData['N'];
            $a = $inputData['a'];

            // Count occurrences of each value in the array
            $countedValues = array_count_values($a);

            // Find duplicates in the array
            $duplicates = [];
            foreach ($countedValues as $value => $count) {
                if ($count > 1) {
                    $duplicates[] = $value;
                }
            }

            // Return duplicates as JSON response
            return response()->json($duplicates);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
