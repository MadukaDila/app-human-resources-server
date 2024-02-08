<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppHumanResources\Attendance\Application\AttendanceService;

class AttendanceController extends Controller
{

    private $attendanceService;

    public function __construct(AttendanceService $attendanceService)
    {
        $this->attendanceService = $attendanceService;
    }

    /**
     * Retrieves and returns employee attendance information.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function fetchEmployeeAttendance(Request $request)
    {
        try {
            // Get employee attendance information from the service
            $attendanceInfo = $this->attendanceService->getEmployeeAttendanceInformation();

            if ($attendanceInfo->isEmpty()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'No attendance records found',
                    'data' => []
                ]);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Attendance records found',
                'data' => $attendanceInfo
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage(),
                'data' => []
            ]);
        }
    }

    /**
     * Stores uploaded attendance records.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadAttendanceRecords(Request $request)
    {
        // Extract processed attendance data from the request
        $processedData = $request->input('attendance_data');

        if (empty($processedData)) {
            return response()->json([
                'status' => 400,
                'message' => 'No data found for attendance records upload'
            ]);
        }

        try {
            // Pass the processed attendance data to the service for upload
            $this->attendanceService->uploadProcessedAttendanceData($processedData);

            return response()->json([
                'status' => 200,
                'message' => 'Attendance records added successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
