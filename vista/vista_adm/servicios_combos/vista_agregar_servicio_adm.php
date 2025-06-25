<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    require_once(__DIR__ . '/../../../variable_global.php');
    require_once(ROOT_PATH . '/modelo/BD.php');
    require_once(ROOT_PATH . '/modelo/modelo_adm/servicios_combos/modelo_inicio_adm.php');

    $servicio_modelo = new servicios($conn);
    $resultado_traer_datos_form = $servicio_modelo->formulario_agregar_servicio();

    $resultado_tiempo = $resultado_traer_datos_form['tiempos'];
    $resultado_trabajador = $resultado_traer_datos_form['trabajadores'];
    $productosJS = $resultado_traer_datos_form['productosJS'];
    ?>

    <script src="<?php echo BASE_URL; ?>/modelo/modelo_adm/servicios_combos/modelo_inicio_adm.js"></script>



    <h1>Agregar Servicio</h1>
    <div>
        <form action="<?= BASE_URL ?>/controlador/controladores_adm/servicios_combos/controlador_inicio_adm.php" method = "post">
            <table border = '1'>
                <input type="hidden" name="agregar" value="vista_agregar_servicio_adm">
                <tr>
                    <td>Nombre</td>
                    <td><input type="text" name = "nombre"></td>
                </tr>
                <tr>
                    <td>Descripcion</td>
                    <td><input type="text" name = "descripcion_servicio"></td>
                </tr>
                <tr>
                    <td>Duracion Servicio</td>
                    <td><input type="number" name = "duracion_servicio"></td>
                </tr>
                <tr>
                    <td>Tiempo a Realizar</td>
                    <td>
                        <select name = "tiempo_realizar">
                            <?php
                            if($resultado_tiempo && $resultado_tiempo->num_rows > 0){
                                while($row_tiempo = $resultado_tiempo->fetch_assoc()){
                                    echo "<option value='{$row_tiempo['id_tiempo_servicio']}'>" . htmlspecialchars($row_tiempo['tiempo_servicio']) . "</option>";

                                }

                            }else{
                                 echo "<option value=''>Error al cargar datos</option>";
                            }
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Precio</td>
                    <td><input type="number" name = "precio_servicio"></td>
                </tr>
                <tr>
                    <td>Trabajador a cargo</td>
                    <td>
                        <select name="trabajador_cargo" >
                            <option value="">Elija el trabajador a cargo</option>
                                <?php
                                if($resultado_trabajador && $resultado_trabajador->num_rows > 0){
                                    while($row_trabajador_cargo = $resultado_trabajador->fetch_assoc()){
                                        echo "<option value='{$row_trabajador_cargo['id_trabajador']}'>" . htmlspecialchars($row_trabajador_cargo['nombre_trabajador']) . "</option>";

                                    }

                                }else{
                                    echo "<option value=''>Error al cargar datos</option>";
                                }
                                ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Activo</td>
                    <td><select name="activo">
                        <option value="activo">Activo</option>
                        <option value="inactivo">Inactivo</option>
                    </select></td>
                </tr>

            

            </table>

            <h1>Productos a utilizar</h1>
            <div id="productos-container">
                <div class="product-row">
                    <select name="productos[]" required></select>
                    <input type="number" name="cantidades[]" placeholder="Cantidad" min="1" required>
                    <span class="remove-product" onclick="removeProduct(this)">✖</span>
                </div>
            </div>

            <button type="button" onclick="addProduct()">Añadir otro producto</button>

            <div style="margin-top: 20px;">
                <input type="submit" name="enviar_servicio_new" value="Guardar Servicio">
            </div>
        </form>

        <!-- Plantilla oculta -->
        <template id="product-template">
            <div class="product-row">
                <select name="productos[]" required></select>
                <input type="number" name="cantidades[]" placeholder="Cantidad" min="1" required>
                <span class="remove-product" onclick="removeProduct(this)">✖</span>
            </div>
        </template>

        <script>
            // Pasamos los productos desde PHP a JavaScript
            const productosDisponibles = <?php echo json_encode($productosJS); ?>;
        </script>

    </div>
    
</body>
</html>