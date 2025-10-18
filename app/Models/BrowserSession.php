<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class BrowserSession extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    public function getConnectionName(): ?string
    {
        return config('session.connection');
    }

    public function getTable(): string
    {
        return (string) config('session.table', 'sessions');
    }

    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                return ($this->getAttribute('id') === session()->getId())
                    ? 'Perangkat saat ini'
                    : 'Perangkat lain';
            },
        );
    }
}
