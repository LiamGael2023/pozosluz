<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Calculo;
use App\Models\TipoEdificacion;
use App\Models\TipoAmbiente;

class CalculadoraController extends Controller
{
    private Calculo $calculoModel;

    public function __construct()
    {
        $this->calculoModel = new Calculo();
    }

    /**
     * Página principal - Formulario de cálculo
     */
    public function index(): void
    {
        $tiposEdificacion = [
            ['id' => 'unifamiliar', 'nombre' => 'Vivienda Unifamiliar'],
            ['id' => 'bifamiliar', 'nombre' => 'Vivienda Bifamiliar'],
            ['id' => 'multifamiliar', 'nombre' => 'Edificación Multifamiliar']
        ];

        $tiposAmbiente = [
            ['id' => 'tipoA', 'nombre' => 'Tipo A - Dormitorios, Sala, Comedor, Estudio'],
            ['id' => 'tipoB', 'nombre' => 'Tipo B - Cocina, Patio de Servicio, Pasajes']
        ];

        $this->view('calculadora/index', [
            'titulo' => 'Calculadora de Pozos de Luz',
            'tiposEdificacion' => $tiposEdificacion,
            'tiposAmbiente' => $tiposAmbiente
        ]);
    }

    /**
     * Procesar cálculo via AJAX
     */
    public function calcular(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Método no permitido'], 405);
            return;
        }

        // Obtener datos del formulario
        $datos = [
            'tipo_edificacion' => $_POST['tipo_edificacion'] ?? '',
            'altura' => $_POST['altura'] ?? 0,
            'numero_pisos' => $_POST['numero_pisos'] ?? 0,
            'tipo_ambiente' => $_POST['tipo_ambiente'] ?? '',
            'lados_edificados' => $_POST['lados_edificados'] ?? 1
        ];

        // Validar datos
        $errores = $this->validarDatos($datos);
        if (!empty($errores)) {
            $this->json(['success' => false, 'errores' => $errores], 400);
            return;
        }

        // Realizar cálculo
        $resultado = Calculo::calcular($datos);

        // Intentar guardar en base de datos (silencioso si falla)
        try {
            $this->calculoModel->guardarCalculo($datos, $resultado);
        } catch (\Exception $e) {
            // Continuar sin guardar si hay error de BD
        }

        $this->json([
            'success' => true,
            'resultado' => $resultado
        ]);
    }

    /**
     * Ver historial de cálculos
     */
    public function historial(): void
    {
        try {
            $historial = $this->calculoModel->getHistorial(20);
        } catch (\Exception $e) {
            $historial = [];
        }

        $this->view('calculadora/historial', [
            'titulo' => 'Historial de Cálculos',
            'historial' => $historial
        ]);
    }

    /**
     * Validar datos del formulario
     */
    private function validarDatos(array $datos): array
    {
        $errores = [];

        if (empty($datos['tipo_edificacion'])) {
            $errores[] = 'Seleccione el tipo de edificación';
        }

        if (empty($datos['altura']) || $datos['altura'] <= 0) {
            $errores[] = 'Ingrese una altura válida';
        }

        if ($datos['altura'] > 200) {
            $errores[] = 'La altura máxima permitida es 200m';
        }

        if (empty($datos['numero_pisos']) || $datos['numero_pisos'] <= 0) {
            $errores[] = 'Ingrese un número de pisos válido';
        }

        if (empty($datos['tipo_ambiente'])) {
            $errores[] = 'Seleccione el tipo de ambiente';
        }

        if (empty($datos['lados_edificados']) || $datos['lados_edificados'] < 1 || $datos['lados_edificados'] > 4) {
            $errores[] = 'Seleccione los lados edificados';
        }

        return $errores;
    }
}
