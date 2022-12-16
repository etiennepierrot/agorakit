<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Group;
class SynchroActionPopulaire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'actionpopulaire:synchro';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize Action populaire events';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
       // get a list of groups
        $groups = Group::all()
        ->where('ga_id', '<>', null);

        foreach ($groups as $group) {
            echo $group->ga_id . "\n";
            $response = Http::get('https://actionpopulaire.fr/api/groupes/'.$group->ga_id.'/evenements/a-venir/');
            $json = json_decode($response->body(), true);
            foreach($json as $action_pop) {
                $action_pop_id = $action_pop['id'];
                echo $action_pop_id . "\n";
                $action = \App\Action::firstOrNew(['external_ref' => $action_pop['id']]);
                $action->name = $action_pop['name'];
                    $action->body = $action_pop['name'];
                    $action->start = \Carbon\Carbon::parse($action_pop['startTime']);
                    $action->stop = \Carbon\Carbon::parse($action_pop['endTime']);
                    $action->location = $action_pop['location']['shortAddress'];
                    $action->latitude = $action_pop['location']['coordinates']["coordinates"][1];
                    $action->longitude = $action_pop['location']['coordinates']["coordinates"][0];
                    $action->user_id = 1;
                    $action->group_id = $group->id;
                    echo $action;
                    $action->save();
                
            }
        }
    }
}
