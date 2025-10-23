<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// ⬇️ dodaj ova dva importa
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser // ⬅️ implementiraj interfejs
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ⬇️ OVO JE KLJUČ: dozvoli pristup panelu
    public function canAccessPanel(Panel $panel): bool
    {
        // Privremeno pusti sve prijavljene korisnike
        return true;

        // Ili uslovi (primeri):
        // return in_array($this->email, ['ti@domen.rs']);
        // return (bool) $this->is_admin;
    }
}
