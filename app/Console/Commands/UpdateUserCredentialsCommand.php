<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;

class UpdateUserCredentialsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'etincelle:update-user-credentials';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->addArgument('email', InputArgument::REQUIRED);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $password = $this->generatePassword();

        $user = User::where('email', $this->argument('email'))->firstOrFail();
        $user->password = \Illuminate\Support\Facades\Hash::make($password);
        $user->api_token = \Illuminate\Support\Str::random(60);
        $user->save();

        $this->output->writeln(sprintf('User has been updated, new password is %s', $password));

    }

    protected function generatePassword($length = 8) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = rand(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }
}
