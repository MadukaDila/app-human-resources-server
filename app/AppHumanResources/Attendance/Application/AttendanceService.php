<?php

namespace App\AppHumanResources\Attendance\Application;

use App\Models\Attendance as AttendanceModel;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AttendanceService
{
    /**
     * Uploads processed attendance data to the database.
     *
     * @param array $processedAttendanceData Processed attendance data to be uploaded.
     */
    public function uploadProcessedAttendanceData(array $processedAttendanceData)
    {
        foreach ($processedAttendanceData as $attendanceRecord) {
            AttendanceModel::create([
                'employee_id' => $attendanceRecord['employee_id'],
                'check_in' => $attendanceRecord['check_in'],
                'check_out' => $attendanceRecord['check_out'],
            ]);
        }
    }

    /**
     * Retrieves and calculates attendance information for employees.
     *
     * @return Collection
     */
    public function getEmployeeAttendanceInformation(): Collection
    {
        // Eager load employee data
        $attendanceInfo = AttendanceModel::with('employee:employee_id,name')->get(['employee_id', 'check_in', 'check_out']);

        // Calculate total hours for each attendance entry
        $attendanceInfo->each(function ($attendanceEntry) {
            $attendanceEntry->total_hours = $this->calculateTotalWorkingHours($attendanceEntry->check_in, $attendanceEntry->check_out);
        });

        return $attendanceInfo;
    }

    /**
     * Calculates total working hours based on check-in and check-out times.
     *
     * @param string|null $checkInTime  Check-in time.
     * @param string|null $checkOutTime Check-out time.
     *
     * @return int|null Total working hours or null if times are invalid.
     */
    private function calculateTotalWorkingHours(?string $checkInTime, ?string $checkOutTime): ?int
    {
        // If either check-in or check-out time is missing, return null
        if ($checkInTime === null || $checkOutTime === null) {
            return null;
        }
        
        // Parse check-in and check-out times as Carbon instances
        $checkIn = Carbon::parse($checkInTime);
        $checkOut = Carbon::parse($checkOutTime);

        // Calculate and return the difference in hours
        return $checkIn->diffInHours($checkOut);
    }
}
