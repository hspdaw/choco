<?php
class PedidoModel
{
    public function obtenerPedidos($fichero)
    {
        try {
            $file = fopen($fichero, "r");
            $totalPedidos = intval(trim(fgets($file)));
            $pedidos = [];
            for ($i = 0; $i < $totalPedidos; $i++) {
                $pesosDisponibles = array_map('intval', explode(',', fgets($file)));
                $pesoSolicitado = intval(fgets($file));
                $pedidos[$i] = [
                    "pesosDisponibles" => $pesosDisponibles,
                    "pesoSolicitado" => $pesoSolicitado,
                ];
            }
            fclose($file);
            return $pedidos;
        } catch (Exception $e) {
            return false;
        }
    }
}
?>