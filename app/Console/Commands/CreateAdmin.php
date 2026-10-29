<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

#[Signature('admin:create {--name= : Nama admin} {--email= : Email untuk login}')]
#[Description('Buat akun admin untuk masuk ke CMS (/admin)')]
class CreateAdmin extends Command
{
    public function handle(): int
    {
        $name = $this->option('name') ?: text('Nama', required: true);
        $email = $this->option('email') ?: text('Email (untuk login)', required: true);
        $password = password('Password (minimal 8 karakter)', required: true);

        $validator = Validator::make(
            compact('name', 'email', 'password'),
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8'],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        User::create(compact('name', 'email', 'password'));

        $this->info("Admin {$email} berhasil dibuat. Masuk di /admin/login");

        return self::SUCCESS;
    }
}
