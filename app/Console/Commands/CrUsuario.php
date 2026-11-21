<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CrUsuario extends Command
{
    
    protected $signature = 'app:criar-usuario';

    protected $description = 'Cria um usuário inicial no sistema AsseTrack';

    public function handle()
    {
        $nome = $this->ask('Digite o nome do usuário');
        $email = $this->ask('Digite o e-mail do usuário');
        $senha = $this->secret('Digite a senha do usuário');

        if (User::where('email', $email)->exists()) {
            $this->error('Já existe um usuário cadastrado com esse e-mail.');
            return self::FAILURE;
        }

        User::create([
            'name' => $nome,
            'email' => $email,
            'password' => Hash::make($senha),
        ]);

        $this->info('Usuário criado com sucesso!');

        return self::SUCCESS;
    }
}
