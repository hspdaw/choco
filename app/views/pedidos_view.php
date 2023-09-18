<?php
class PedidosView
{
    private function detalle($pedido, $estado, $detalle)
    {
        $nPiezas = count($detalle["piezasUtilizadas"]);
        $descripcion = '<ol class="list-unstyled">
                            <li>Peso Solicitado: ' . ($detalle["pesoSolicitado"]) . '</li>';
        if ($estado  === 'OK') {
            $descripcion .='<li>Pedido completado con &eacute;xito.</li>';
        } else {
            $descripcion .='<li>No es posible completar el pedido</li>
                            <li>Peso cubierto: ' . ($detalle["pesoCubierto"]) . '</li>
                            <li>Peso faltante: ' . $detalle["pesoPendiente"] . '</li>
                            <li>Pieza sugerida: ' . $detalle["pesoSugerido"] . '</li>';
        }
        $descripcion .='<li>Cantidad de piezas utilizadas: ' . $nPiezas . '</li>
                        <li>
                            <a class="link-offset-3" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#' . $pedido . '">
                                Detalle de piezas:
                            </a>
                            <div id="' . $pedido . '" class="collapse container">
                                <p>' . implode(',', $detalle["piezasUtilizadas"]) . '</p>
                            </div>
                        </li>
                    </ol>';
        return $descripcion;
    }
    private function datosPedido($pedido, $detalle)
    {
        $estado = $detalle['estadoPedido'];
        $estilo = $estado === 'OK' ? 'bg-success' : 'bg-warning';
        return '<li class="list-group-item"><span class="badge ' . $estilo . '">' . $estado . ' </span> PEDIDO ' . $pedido . ': ' . $this->detalle($pedido, $estado, $detalle) . '</li>';
    }
    public function listadoPedidos($pedidos)
    {
        echo '<div class="container mt-3">
                <div class="container mt-3"> 
                    <h5>Total de pedidos: ' . sizeOf($pedidos) . '</h5>
                    <ol class="list-group list-group-flush">';
        foreach ($pedidos as $key => $value) {
            echo $this->datosPedido($key + 1, $value);
        }
        echo '</ol>
            </div>
        </div>';
    }
    public function errorPedidos($m){
        echo '<div class="container mt-3">
                <div class="container mt-3"> 
                    <div class="alert alert-danger" role="alert">
                        <strong>Error!</strong> '.$m.'
                    </div>
                </div>
            </div>';
    }
}
