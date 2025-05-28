<?php

namespace App\Http\Controllers;

use App\Models\DetailReport;
use App\Models\ReportModel;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function SendReport(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'subject' => 'required|string',
                'context' => 'required|string',
                'rating' => 'required|integer|in:1,2,3,4,5'
            ]);

            DB::transaction(function () use ($data, $request) {
                $report = ReportModel::create([
                    'subject' => $data['subject'],
                    'context' => $data['context'],
                    'rating' => $data['rating']
                ]);

                DetailReport::create([
                    'user_id' => $request->user()->id,
                    'report_id' => $report->id,
                ]);
            });
            return response()->json([
                'message' => 'Report has been send'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function ViewHistoryReport(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            $reportMap = DetailReport::with(['report'])
                ->where('user_id', $user->id)
                ->get();
            $response = $reportMap->map(function ($history) {
                return [
                    'subject' => $history->report->subject,
                    'context' => $history->report->context,
                    'rating' => (int) $history->report->rating,
                    'status' => $history->report->status,
                    'date' => $history->report->created_at,
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
