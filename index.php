<!DOCTYPE html>
<html>
<head>
  <title>Formulario de Datos de Empleados</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/estilo.css">
</head>
<body>
  <div class="container mt-4">
    <h2>Formulario de Empleados</h2>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <div class="form-group">
        <label for="nombre">Nombre y Apellido:</label>
        <input type="text" class="form-control" id="nombre" name="nombre" required>
      </div>
      <div class="form-group">
        <label for="edad">Edad:</label>
        <input type="number" class="form-control" id="edad" name="edad" required>
      </div>
      <div class="form-group">
        <label for="estado_civil">Estado Civil:</label>
        <select class="form-control" id="estado_civil" name="estado_civil" required>
          <option value="Soltero">Soltero</option>
          <option value="Casado">Casado</option>
          <option value="Viudo">Viudo</option>
        </select>
      </div>
      <div class="form-group">
        <label for="sexo">Sexo:</label>
        <select class="form-control" id="sexo" name="sexo" required>
          <option value="Femenino">Femenino</option>
          <option value="Masculino">Masculino</option>
        </select>
      </div>
      <div class="form-group">
        <label for="sueldo">Sueldo:</label>
        <select class="form-control" id="sueldo" name="sueldo" required>
          <option value="Menos de 1000$">Menos de 1000$</option>
          <option value="Entre 1000$ y 2500$">Entre 1000$ y 2500$</option>
          <option value="Mas de 2500$">Mas de 2500$</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">Enviar Datos</button>
    </form>
    <hr>
    <h2>Lista de Empleados</h2>
    <table class="table">
      <thead>
        <tr>
          <th>Nombre y Apellido</th>
          <th>Edad</th>
          <th>Estado Civil</th>
          <th>Sexo</th>
          <th>Sueldo</th>
        </tr>
      </thead>
      <tbody>
        <?php
        function obtenerDatos() {
          $datosJson = file_get_contents("datos.json");
          $datos = json_decode($datosJson, true);
          return $datos;
        }

        function guardarDatos($datos) {
          $datosJson = json_encode($datos);
          file_put_contents("datos.json", $datosJson);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $nombre = $_POST["nombre"];
          $edad = $_POST["edad"];
          $EstadoCivil = $_POST["estado_civil"];
          $sexo = $_POST["sexo"];
          $sueldo = $_POST["sueldo"];

          $nuevo_dato = array(
            "nombre" => $nombre,
            "edad" => $edad,
            "estado_civil" => $EstadoCivil,
            "sexo" => $sexo,
            "sueldo" => $sueldo
          );

          $datos = obtenerDatos();
          $datos[] = $nuevo_dato;
          guardarDatos($datos);
          header("Location: index.php");
          exit();
        }

        $datos = obtenerDatos();

        foreach ($datos as $dato) {
          echo "<tr>";
          echo "<td>" . $dato["nombre"] . "</td>";
          echo "<td>" . $dato["edad"] . "</td>";
          echo "<td>" . $dato["estado_civil"] . "</td>";
          echo "<td>" . $dato["sexo"] . "</td>";
          echo "<td>" . $dato["sueldo"] . "</td>";
          echo "</tr>";
        }
        ?>
      </tbody>
    </table>

    <h2>Estadísticas</h2>
    <?php
    $total_femenino = 0;
    $total_hombres_casados = 0;
    $total_mujeres_viudas = 0;
    $total_edad_hombres=0;
    $contador_hombres=0;

    foreach ($datos as $dato) {
      if ($dato["sexo"] == "Femenino") {
        $total_femenino++;
      }

      if ($dato["sexo"] == "Masculino" && $dato["estado_civil"] == "Casado" && $dato["sueldo"] == "Mas de 2500$") {
        $total_hombres_casados++;
      }

      if ($dato["sexo"] == "Femenino" && $dato["estado_civil"] == "Viudo" && $dato["sueldo"] == "Mas de 1000$") {
        $total_mujeres_viudas++;
      }
      if ($dato["sexo"] == "Masculino") {
        $total_edad_hombres += $dato["edad"];
        $contador_hombres++;
      }
    }

    echo "<p>Total de empleados del sexo femenino: " . $total_femenino . "</p>";
    echo "<p>Total de hombres casados que ganan más de 2500$: " . $total_hombres_casados . "</p>";
    echo "<p>Total de mujeres viudas que ganan más de 1000$: " . $total_mujeres_viudas . "</p>";
    if ($contador_hombres > 0) {
        $promedio_edad_hombres = $total_edad_hombres / $contador_hombres;
        echo "<p>Edad promedio de los hombres: " . $promedio_edad_hombres . "</p>";
      } else {
        echo "<p>No se encontraron hombres registrados.</p>";
      }
    ?>
  </div>
</body>
</html>
