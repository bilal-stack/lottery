<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class UserGiveRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:give-role {user_id} {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Will assign user to role.';

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
        if(!$user = User::find($this->argument('user_id'))) {
            exit('User does not exist!');
        }
        if(!Role::findByName($this->argument('role'))) {
            exit('Role does not exist!');
        }

        $user->assignRole($this->argument('role'));

        echo sprintf('User ID: %d - %s %s <%s> assigned to role [%s]', $user->id, $user->first_name, $user->last_name, $user->email, $this->argument('role'));
        echo "\n";
        echo 'User roles: [' . implode(', ', $user->roles()->pluck('name')->toArray()) .']';
        echo "\n";
    }
}
