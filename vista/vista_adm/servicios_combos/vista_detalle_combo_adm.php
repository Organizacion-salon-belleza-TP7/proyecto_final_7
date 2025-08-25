<?php
require_once(__DIR__ . '/../../../variable_global.php');
require_once(ROOT_PATH . '/modelo/BD.php');
require_once(ROOT_PATH . '/modelo/modelo_adm/servicios_combos/modelo_inicio_adm.php');

$servicios_combos_modelo = new servicios($conn);
$id_combo = $_GET['id'];
$mostrar_detalle_combo = $servicios_combos_modelo->detalle_combo($id_combo);

if($mostrar_detalle_combo && $mostrar_detalle_combo->num_rows > 0) {
    // Obtener primera fila para datos del combo
    $first_row = $mostrar_detalle_combo->fetch_assoc();
    $mostrar_detalle_combo->data_seek(0); // Reiniciar puntero
    
    echo "<h2>Detalles del Combo: {$first_row['nombre_combo']}</h2>";
    echo "<p><strong>Descripción:</strong> {$first_row['descripcion_combo']}</p>";
    echo "<p><strong>Precio del Combo:</strong> {$first_row['precio_combo']}</p>";
    
    echo "<h3>Servicios incluidos:</h3>";
    echo "<table border='1'>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio Individual</th>
                <th>Tipo</th>
                <th>Intereses</th>
            </tr>
        </thead>
        <tbody>";

    while($row_detalle = $mostrar_detalle_combo->fetch_assoc()) {
        echo "<tr>
                <td>{$row_detalle['id_servicios']}</td>
                <td>{$row_detalle['nombre']}</td>
                <td>{$row_detalle['descripcion']}</td>
                <td>{$row_detalle['precio']}</td>
                <td>{$row_detalle['tipo_servicio']}</td>
                <td>{$row_detalle['intereses']}%</td>
              </tr>";
    }
    
    echo "</tbody></table>";
    echo "<a href='".BASE_URL."/vista/vista_adm/servicios_combos/vista_inicio_adm.php' class='add-btn'>Volver</a>";
} else {
    echo '<script language="javascript">
        alert("Este combo no tiene servicios asociados");
        self.location = "'.BASE_URL.'/vista/vista_adm/servicios_combos/vista_inicio_adm.php"
        </script>';
}
?>