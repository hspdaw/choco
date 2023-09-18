<?php
require_once 'app/models/pedidos_model.php';
require_once 'app/views/pedidos_view.php';

class PedidoController
{
    private $pedidos_model;
    private $pedidos_view;

    public function __construct()
    {
        $this->pedidos_model = new PedidoModel();
        $this->pedidos_view = new PedidosView();
    }
    private function mensajesError($n)
    {
        $texto = '';
        switch ($n) {
            case 0:
                $texto = 'El archivo buscado NO existe.';
                break;
            case 1:
                $texto = 'Hubo un problema al intentar obtener los pedidos.';
                break;
            case 2:
                $texto = 'Hubo un problema durante el porcesamiento de los pedidos.';
                break;
        }
        return $texto;
    }
    private function error($n)
    {
        $texto = $this->mensajesError($n);
        $this->pedidos_view->errorPedidos($texto);
    }
    private function calcularPedido($pesosDisponibles, $pesoSolicitado)
    {
        rsort($pesosDisponibles, SORT_NUMERIC);
        $piezasUsadas = array();
        $pesoPendiente = $pesoSolicitado;
        while ($pesoPendiente > 0) {
            $pedidoCompletado = false;
            foreach ($pesosDisponibles as $peso) {
                if ($pesoPendiente >= $peso) {
                    $piezasUsadas[] = $peso;
                    $pesoPendiente -= $peso;
                    $pedidoCompletado = true;
                    break;
                }
            }
            if (!$pedidoCompletado) {
                return array(
                    "estadoPedido" => "KO",
                    "pesoSolicitado" => $pesoSolicitado,
                    "pesoCubierto" => $pesoSolicitado - $pesoPendiente,
                    "pesoPendiente" => $pesoPendiente,
                    "pesoSugerido" => min($pesosDisponibles),
                    "piezasUtilizadas" => $piezasUsadas,
                );
            }
        }
        return array(
            "estadoPedido" => "OK",
            "pesoSolicitado" => $pesoSolicitado,
            "piezasUtilizadas" => $piezasUsadas,
        );
    }
    private function procesarPedidos($data)
    {
        try {
            $pedidosProcesados = array();
            foreach ($data as $key => $value) {
                $pesosDisponibles = $value['pesosDisponibles'];
                $pesoSolicitado = $value['pesoSolicitado'];
                $pedidosProcesados[$key] = $this->calcularPedido($pesosDisponibles, $pesoSolicitado);
            }
            return $pedidosProcesados;
        } catch (Exception $e) {
            return false;
        }
    }
    private function tratarFichero()
    {
        $dir = './';
        $archivo = 'input.txt';
        return file_exists($dir . $archivo) ? $dir . $archivo : false;
    }
    public function gestionarPedidos()
    {
        $hayPedidos = $this->tratarFichero();
        if (!$hayPedidos) {
            return $this->error(0);
        }
        $pedidosObtenidos = $this->pedidos_model->obtenerPedidos($hayPedidos);
        if (!$pedidosObtenidos) {
            return $this->error(1);
        }
        $data = $this->procesarPedidos($pedidosObtenidos);
        if (!$data) {
            return $this->error(2);
        }
        $this->pedidos_view->listadoPedidos($data);
    }
}
