<?php
namespace App\Models;

use App\Core\Model;

class TipoAmbiente extends Model
{
    protected string $table = 'tipos_ambiente';

    public function getActivos(): array
    {
        return $this->where('activo', 1);
    }
}
