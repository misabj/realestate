<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// ⬇️ dodaj ova dva importa
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser, HasAvatar
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

    // Koristi lokalni Filament avatar (inicijali) umesto ui-avatars.com
    public function getFilamentAvatarUrl(): ?string
    {
        $name    = trim($this->name ?? $this->email ?? '?');
        $initial = mb_strtoupper(mb_substr($name, 0, 1));

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40">'
             . '<rect width="40" height="40" rx="20" fill="#1a1a1a"/>'
             . '<text x="50%" y="50%" dominant-baseline="central" text-anchor="middle" '
             . 'font-size="18" font-family="sans-serif" fill="#ffffff">'
             . htmlspecialchars($initial)
             . '</text></svg>';

        return 'data:image/svg+xml;base64,' . base64_encode($svg);
    }
}
