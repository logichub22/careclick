<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Organization\Organization;
use App\Jobs\ProcessUserDeactivation;
use Illuminate\Support\Facades\DB;
use App\User;

class ProcessAssociationDeactivation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $organization;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Organization $organization)
    {
        $this->organization = $organization;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $users = DB::table('users')
                ->join('user_details', 'user_details.user_id', '=', 'users.id')
                ->where('user_details.org_id', '=', $this->organization->id)
                ->select('users.*')
                ->get();

        if(!empty($users)) {
            foreach ($users as $user) {
                DB::table('users')->where('id', $user->id)->update([
                    'status' => false,
                ]);
                ProcessUserDeactivation::dispatch($user);
            }
        }
    }
}
