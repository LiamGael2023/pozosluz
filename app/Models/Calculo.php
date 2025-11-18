<?php
namespace App\Models;

use App\Core\Model;
use App\Core\Database;

class Calculo extends Model
{
    protected string $table = 'calculos';

    /**
     * Obtener dimensión mínima base según normativa RNE
     */
    public static function getDimensionMinima(string $tipoEdificacion, string $tipoAmbiente): float
    {
        $dimensiones = [
            'unifamiliar' => [
                'tipoA' => 2.00,
                'tipoB' => 1.80
            ],
            'bifamiliar' => [
                'tipoA' => 2.00,
                'tipoB' => 1.80
            ],
            'multifamiliar' => [
                'tipoA' => 2.20,
                'tipoB' => 2.00
            ]
        ];

        return $dimensiones[$tipoEdificacion][$tipoAmbiente] ?? 2.00;
    }

    /**
     * Calcular distancia perpendicular según altura y tipo de ambiente
     * RNE A.010: 1/3 para Tipo A, 1/4 para Tipo B
     */
    public static function getDistanciaPerpendicular(float $altura, string $tipoAmbiente): float
    {
        $factor = ($tipoAmbiente === 'tipoA') ? (1/3) : (1/4);
        return $altura * $factor;
    }

    /**
     * Calcular dimensiones del pozo de luz
     */
    public static function calcular(array $datos): array
    {
        $tipoEdificacion = $datos['tipo_edificacion'];
        $altura = floatval($datos['altura']);
        $numeroPisos = intval($datos['numero_pisos']);
        $tipoAmbiente = $datos['tipo_ambiente'];
        $ladosEdificados = intval($datos['lados_edificados']);

        // Dimensión mínima base
        $dimensionBase = self::getDimensionMinima($tipoEdificacion, $tipoAmbiente);

        // Calcular distancia perpendicular
        $distanciaPerpendicular = self::getDistanciaPerpendicular($altura, $tipoAmbiente);

        // Ajuste por tramos de 18m
        $tramosCompletos = floor($altura / 18);
        $incrementoTramo = $tramosCompletos * 0.25;

        // Dimensión mínima con incremento
        $dimensionMinima = $dimensionBase + $incrementoTramo;

        // La dimensión final es el mayor entre la mínima y la perpendicular
        $dimensionFinal = max($dimensionMinima, $distanciaPerpendicular);

        // Área mínima
        $areaMinima = $dimensionFinal * $dimensionFinal;

        return [
            'dimension_minima' => round($dimensionFinal, 2),
            'distancia_perpendicular' => round($distanciaPerpendicular, 2),
            'area_minima' => round($areaMinima, 2),
            'dimension_base' => $dimensionBase,
            'tramos_completos' => $tramosCompletos,
            'incremento_tramo' => round($incrementoTramo, 2),
            'factor_perpendicular' => ($tipoAmbiente === 'tipoA') ? '1/3' : '1/4',
            'tipo_edificacion' => $tipoEdificacion,
            'tipo_ambiente' => $tipoAmbiente,
            'altura' => $altura,
            'numero_pisos' => $numeroPisos,
            'lados_edificados' => $ladosEdificados
        ];
    }

    /**
     * Guardar cálculo en la base de datos
     */
    public function guardarCalculo(array $datos, array $resultado): int
    {
        return $this->create([
            'tipo_edificacion' => $datos['tipo_edificacion'],
            'altura' => $datos['altura'],
            'numero_pisos' => $datos['numero_pisos'],
            'tipo_ambiente' => $datos['tipo_ambiente'],
            'lados_edificados' => $datos['lados_edificados'],
            'dimension_minima' => $resultado['dimension_minima'],
            'distancia_perpendicular' => $resultado['distancia_perpendicular'],
            'area_minima' => $resultado['area_minima'],
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Obtener historial de cálculos
     */
    public function getHistorial(int $limite = 10): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ?";
        return Database::query($sql, [$limite])->fetchAll();
    }
}
