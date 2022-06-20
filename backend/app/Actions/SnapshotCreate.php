<?php

namespace App\Actions;

use App\Models\Snapshot;
use App\Models\FunctionalAssay;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsListener;


class SnapshotCreate
{
    use AsListener;

    public function handle(FunctionalAssay $functionalAssay)
    {
        $functionalAssay->load(['assayClasses', 'publication']);

        $snapshot = new Snapshot([
            'functional_assay_id' => $functionalAssay->id,
            'version' => $this->getNextVersion($functionalAssay),
            'data' => $functionalAssay->toArray(),
        ]);

        $snapshot->save();
    }

    public function asListener(\App\Events\FunctionalAssaySaved $event)
    {
        $this->handle($event->functionalAssay);
    }

    private function getNextVersion(FunctionalAssay $functionalAssay)
    {
        $currentVersion = DB::table('snapshots')
            ->where('functional_assay_id', $functionalAssay->id)
            ->max('version');
        
        $nextVersion = $currentVersion + 1;

        return $nextVersion;
    }
    
}