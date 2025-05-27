<?php

namespace App\Http\Controllers;

use App\Models\DetailReport;
use App\Models\ReportModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportWebController extends Controller
{
    public function index()
    {
        try {
            $reports = ReportModel::with(['detailReport.user'])
                ->paginate(10); // Paginate the results (10 per page)

            return response()->json([
                'message' => 'Reports fetched successfully',
                'data' => $reports,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An error occurred while fetching reports',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function edit($id)
    {
        $report = ReportModel::findOrFail($id);

        // Return the edit view with the report
        return view('reports.edit', compact('report'));
    }
    public function update(Request $request, $id)
    {
        try {
            // Validate the request
            $data = $request->validate([
                'status' => 'required|in:accepted,declined,waiting',
            ]);

            // Find the report by ID
            $report = ReportModel::findOrFail($id);

            // Update the status
            $report->update([
                'status' => $data['status'],
            ]);

            return response()->json([
                'message' => 'Report status updated successfully',
                'data' => $report,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An error occurred while updating the report',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    public function destroy($id)
    {
        try {
            $report = ReportModel::findOrFail($id);

            $report->delete();

            return response()->json([
                'message' => 'Report deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'An error occurred while deleting the report',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    public function ViewHistoryReport(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $reportMap = DetailReport::with(['user.dataPribadi', 'report'])
                ->where('user_id', $user->id)
                ->get();
            $response = $reportMap->map(function ($history) {
                return [
                    'matrix' => $history->users->dataPribadi->matrix_id,
                    'full_name' => $history->users->dataPribadi->full_name,
                    'context' => $history->report->context,
                    'status' => $history->report->status,
                    'rating' => $history->report->rating,
                ];
            });
            return response()->json([
                'message' => 'History successfully fetched',
                'data' => $response,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
