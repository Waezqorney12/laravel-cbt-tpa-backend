<?php

namespace App\Http\Controllers;

use App\Models\DetailReport;
use App\Models\ReportModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportWebController extends Controller
{
    public function index()
    {
        try {
            $reports = ReportModel::with(['detailReport.user'])->paginate(10);
            return view('pages.reports.index', compact('reports'));
        } catch (\Throwable $th) {
            // Log the error for debugging
            Log::error($th->getMessage());

            // Redirect to a fallback page or show an error message
            return redirect()->route('home')->with('error', 'An error occurred while loading the reports.');
        }
    }
    public function edit($id)
    {
        $report = ReportModel::findOrFail($id);

        // Return the edit view with the report
        return view('pages.reports.edit', compact('report'));
    }
    public function update(Request $request, $id)
    {
        try {
            $data = $request->validate([
                'status' => 'required|in:accepted,declined,waiting',
            ]);
            $report = ReportModel::findOrFail($id);
            $report->update([
                'status' => $data['status'],
            ]);

            return redirect()->route('reports.index')->with('success', 'Reports status updated.');
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
                    'rating' => (int) $history->report->rating,
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
