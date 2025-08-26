<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class SystemStatusController extends Controller
{
    public function show(Request $request)
    {
        $data = $this->gather();
        return response()->json($data);
    }

    protected function gather(): array
    {
        // Database check (simple select 1)
        $databaseOk = true;
        try { DB::select('SELECT 1'); } catch (\Throwable $e) { $databaseOk = false; }

        // Websocket (heuristic: broadcasting driver not log)
        $websocket = config('broadcasting.default') !== 'log';

        // Anti cheat (based on proctoring feature flag)
        $antiCheat = (bool) (config('proctoring.features.real_time_alerts') ?? true);

        // Storage usage (disk level)
        $disk = base_path();
        $total = @disk_total_space($disk) ?: 0;
        $free  = @disk_free_space($disk) ?: 0;
        $usedRatio = $total > 0 ? ($total - $free) / $total : 0;

        // Last backup (scan storage/app/backups)
        $backupDir = storage_path('app/backups');
        $lastBackupHuman = '-';
        if (is_dir($backupDir)) {
            $latestTime = null;
            foreach (glob($backupDir.'/*') as $f) {
                $mtime = @filemtime($f);
                if ($mtime && ($latestTime === null || $mtime > $latestTime)) {
                    $latestTime = $mtime;
                }
            }
            if ($latestTime) {
                $lastBackupHuman = Carbon::createFromTimestamp($latestTime)->diffForHumans();
            }
        }

        // Queue stats (database driver assumed)
        $pending = 0; $failed = 0;
        try { if (Schema::hasTable('jobs')) { $pending = DB::table('jobs')->count(); } } catch (\Throwable $e) {}
        try { if (Schema::hasTable('failed_jobs')) { $failed = DB::table('failed_jobs')->count(); } } catch (\Throwable $e) {}

        return [
            'database'     => $databaseOk,
            'websocket'    => $websocket,
            'anti_cheat'   => $antiCheat,
            'storage_used' => round($usedRatio, 4), // fraction 0-1
            'last_backup'  => $lastBackupHuman,
            'queue'        => [ 'pending' => $pending, 'failed' => $failed ],
        ];
    }
}
