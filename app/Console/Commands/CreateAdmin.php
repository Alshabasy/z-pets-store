<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create';

    protected $description = 'Create a new admin account for Z-Pets Store dashboard';

    public function handle(): int
    {
        $this->info('');
        $this->info('  🐾  Z-Pets Store — Create Admin Account');
        $this->info('  ─────────────────────────────────────────');
        $this->info('');

        $name = $this->ask('  Full name');
        if (empty(trim($name))) {
            $this->error('  Name cannot be empty.');

            return self::FAILURE;
        }

        $email = $this->ask('  Email address');
        $emailValidator = Validator::make(
            ['email' => $email],
            ['email' => 'required|email|unique:users,email']
        );
        if ($emailValidator->fails()) {
            $this->error('  '.$emailValidator->errors()->first('email'));

            return self::FAILURE;
        }

        $password = $this->secret('  Password (min 8 characters)');
        if (strlen($password) < 8) {
            $this->error('  Password must be at least 8 characters.');

            return self::FAILURE;
        }

        $confirm = $this->secret('  Confirm password');
        if ($password !== $confirm) {
            $this->error('  Passwords do not match.');

            return self::FAILURE;
        }

        $this->info('');
        $this->info("  Name  : {$name}");
        $this->info("  Email : {$email}");
        $this->info('  Role  : admin');
        $this->info('');

        if (! $this->confirm('  Create this admin account?', true)) {
            $this->info('  Cancelled.');

            return self::SUCCESS;
        }

        User::create([
            'name'     => trim($name),
            'email'    => strtolower(trim($email)),
            'password' => $password,
            'role'     => 'admin',
        ]);

        $this->info('');
        $this->info('  ✅  Admin account created successfully!');
        $this->info('  You can now log in at: '.url('/login'));
        $this->info('');

        return self::SUCCESS;
    }
}
