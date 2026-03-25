<?php

namespace App\Observers;

use App\Models\Participant;
use Illuminate\Support\Facades\DB;

class ParticipantObserver
{
    /**
     * Handle the Participant "created" event.
     */
    public function created(Participant $participant): void
    {
        DB::table('audit_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'created',
            'target_type' => 'participant',
            'target_id' => $participant->id,
            'new_value' => json_encode($participant->toArray()),
        ]);
    }

    /**
     * Handle the Participant "updated" event.
     */
    public function updated(Participant $participant): void
    {
        DB::table('audit_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'target_type' => 'participant',
            'target_id' => $participant->id,
            'old_value' => json_encode($participant->getOriginal()),
            'new_value' => json_encode($participant->getChanges()),
        ]);
    }

    /**
     * Handle the Participant "deleted" event.
     */
    public function deleted(Participant $participant): void
    {
        DB::table('audit_logs')->insert([
            'user_id' => auth()->id(),
            'action' => 'deleted',
            'target_type' => 'participant',
            'target_id' => $participant->id,
            'old_value' => json_encode($participant->toArray()),
        ]);
    }

    /**
     * Handle the Participant "restored" event.
     */
    public function restored(Participant $participant): void
    {
        //
    }

    /**
     * Handle the Participant "force deleted" event.
     */
    public function forceDeleted(Participant $participant): void
    {
        //
    }
}
