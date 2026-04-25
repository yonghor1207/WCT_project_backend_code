<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Api\v1\BaseAPI;
use App\Models\User;
use App\Models\Classroom;
use App\Models\Course;
use App\Models\Payment;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends BaseAPI
{
    public function getStats(Request $request)
    {
        try {
            // Get total students
            $totalStudents = User::where('role', 'student')->where('status', 1)->count();
            
            // Get active teachers
            $activeTeachers = User::where('role', 'teacher')->where('status', 1)->count();
            
            // Get total classrooms (changed from courses)
            $totalClasses = Classroom::count();
            
            // Get total revenue
            $totalRevenue = Payment::where('status', 'verified')->sum('amount');
            
            // Get previous month data for comparison
            $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
            $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
            
            $lastMonthStudents = User::where('role', 'student')
                ->where('status', 1)
                ->where('created_at', '>=', $lastMonthStart)
                ->where('created_at', '<=', $lastMonthEnd)
                ->count();
            
            $lastMonthTeachers = User::where('role', 'teacher')
                ->where('status', 1)
                ->where('created_at', '>=', $lastMonthStart)
                ->where('created_at', '<=', $lastMonthEnd)
                ->count();
            
            $lastMonthClassrooms = Classroom::where('created_at', '>=', $lastMonthStart)
                ->where('created_at', '<=', $lastMonthEnd)
                ->count();
            
            $lastMonthRevenue = Payment::where('status', 'verified')
                ->where('created_at', '>=', $lastMonthStart)
                ->where('created_at', '<=', $lastMonthEnd)
                ->sum('amount');
            
            // Calculate percentage changes
            $studentChange = $lastMonthStudents > 0 
                ? round((($totalStudents - $lastMonthStudents) / $lastMonthStudents) * 100, 1) 
                : 0;
            
            $teacherChange = $lastMonthTeachers > 0 
                ? round((($activeTeachers - $lastMonthTeachers) / $lastMonthTeachers) * 100, 1) 
                : 0;
            
            $classroomChange = $lastMonthClassrooms > 0 
                ? round((($totalClasses - $lastMonthClassrooms) / $lastMonthClassrooms) * 100, 1) 
                : 0;
            
            $revenueChange = $lastMonthRevenue > 0 
                ? round((($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) 
                : 0;
            
            $stats = [
                'totalStudents' => [
                    'value' => $totalStudents,
                    'change' => ($studentChange >= 0 ? '+' : '') . $studentChange . '% from last month',
                    'isPositive' => $studentChange >= 0
                ],
                'activeTeachers' => [
                    'value' => $activeTeachers,
                    'change' => ($teacherChange >= 0 ? '+' : '') . $teacherChange . ' new this month',
                    'isPositive' => $teacherChange >= 0
                ],
                'totalClasses' => [
                    'value' => $totalClasses,
                    'change' => $lastMonthClassrooms . ' new classes added',
                    'isPositive' => true
                ],
                'revenue' => [
                    'value' => $totalRevenue,
                    'change' => ($revenueChange >= 0 ? '+' : '') . $revenueChange . '% from last month',
                    'isPositive' => $revenueChange >= 0
                ]
            ];
            
            return $this->successResponse($stats, 'Dashboard stats retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
    
    public function getChartData(Request $request)
    {
        try {
            $months = 6; // Last 6 months
            $studentGrowth = [];
            $revenueData = [];
            
            for ($i = $months - 1; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthStart = $date->copy()->startOfMonth();
                $monthEnd = $date->copy()->endOfMonth();
                
                // Student growth
                $studentCount = User::where('role', 'student')
                    ->where('status', 1)
                    ->where('created_at', '<=', $monthEnd)
                    ->count();
                
                $studentGrowth[] = [
                    'month' => $date->format('M'),
                    'value' => $studentCount
                ];
                
                // Revenue data
                $revenue = Payment::where('status', 'verified')
                    ->where('created_at', '>=', $monthStart)
                    ->where('created_at', '<=', $monthEnd)
                    ->sum('amount');
                
                $revenueData[] = [
                    'month' => $date->format('M'),
                    'value' => (float) $revenue
                ];
            }
            
            $chartData = [
                'studentGrowth' => $studentGrowth,
                'revenueData' => $revenueData
            ];
            
            return $this->successResponse($chartData, 'Chart data retrieved successfully');
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
    }
}
