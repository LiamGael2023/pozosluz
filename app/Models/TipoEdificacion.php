<?php
namespace App\Models;

use App\Core\Model;

class TipoEdificacion extends Model
{
    protected string $table = 'tipos_edificacion';

    public function getActivos(): array
    {
        return $this->where('activo', 1);
    }
}
