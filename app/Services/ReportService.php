<?php

namespace App\Services;

use App\Models\ReportSession;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ReportService
{
    /**
     * Get filtered report sessions.
     *
     * @param int|null $operatorId
     * @param string|null $startDate Y-m-d format
     * @param string|null $endDate   Y-m-d format
     * @return \Illuminate\Support\Collection
     */
    public function getSessions(?int $operatorId, ?string $startDate, ?string $endDate)
    {
        $query = ReportSession::query();

        if ($operatorId) {
            $query->where('operator_id', $operatorId);
        }

        if ($startDate) {
            $query->whereDate('session_start', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('session_end', '<=', $endDate);
        }

        return $query->orderBy('session_start', 'desc')->get();
    }

    /**
     * Calculate total time per state for a collection of sessions.
     * Returns an associative array [state => seconds].
     */
    public function calculateStateDurations($sessions)
    {
        $durations = [];
        foreach ($sessions as $session) {
            $state = $session->state;
            $start = Carbon::parse($session->session_start);
            $end   = $session->session_end ? Carbon::parse($session->session_end) : Carbon::now();
            $seconds = $end->diffInSeconds($start);
            $durations[$state] = ($durations[$state] ?? 0) + $seconds;
        }
        return $durations;
    }
}
?>
