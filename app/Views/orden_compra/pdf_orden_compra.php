<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Compra</title>

    <!-- Incluir la fuente "Unito" desde Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Unito:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Unito', sans-serif;
            margin: 0px;
            padding: 0;
            font-size: 12px;
            color: #333;
        }

        .container {
            width: 100%;
            margin: auto;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 8px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 24px;
            margin: 0;
            font-weight: 700;
        }

        .header .company-info {
            text-align: left;
        }

        .header .company-info h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .order-title {
            text-align: right;
            font-size: 22px;
            font-weight: 700;
            color: #888;
        }

        /* Usamos display: table para organizar los divs lado a lado */
        .info-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .info-box {
            display: table-cell;
            /* Esto pone los divs en fila */
            width: 48%;
            /* Asigna un 48% de espacio a cada div */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            background-color: #f9f9f9;
        }

        .info-box strong {
            display: block;
            font-size: 16px;
            margin-bottom: 6px;
        }

        .box:first-child {
            background-color: #d1e7dd;
        }

        .box:last-child {
            background-color: #f8d7da;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
            font-size: 10px;
        }

        .table th {
            background-color: #f2f2f2;
            font-weight: 600;
        }


        .totales {
            margin-top: 15px;
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <div class="company-info">
                <img src="<?= base_url('public/assets/img/sistema/iexe_logo.png'); ?>" alt="Logo Empresa">
                <p>Dirección:<br>Blvrd Esteban de Antuñano 2702,<br>Reforma, 72160 Heroica Puebla de Zaragoza, Pue.</p>
                <p>Teléfono: 800 286 8464 </p>
            </div>
            <div class="order-title">VISTA PREVIA ORDEN DE COMPRA</div>
        </div>

        <p><strong>NÚMERO DE O/C:</strong> <span class="order-number">XXXX</span></p>

        <div class="info-container">
            <div class="info-box">
                <strong>Para:</strong>
                <?= $proveedor['contacto']; ?><br>
                <?= $proveedor['proveedor']; ?><br>
                <?= $proveedor['direccion']; ?><br>
                <?= $proveedor['telefono']; ?>
            </div>
            <div class="info-box">
                <strong>Enviar a:</strong>
                Marco Antonio Cardona<br>
                Iexe Universidad<br>
                Blvrd Esteban de Antuñano 2702, Reforma, 72160 Heroica Puebla de Zaragoza, Pue.<br>
                800 286 8464
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Descripción</th>
                    <th>Cant.</th>
                    <th>Precio Unitario</th>
                    <th>Precion con descuento</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody style="font-size: 10px;">
                <?php foreach ($productos as $p) { ?>
                    <tr>
                        <td><?= $p->producto; ?></td>
                        <td><?= $p->descripcion; ?></td>
                        <td><?= $p->cantidad; ?></td>
                        <td>$ <?= $p->precio; ?></td>
                        <td>$ <?= $p->descuento; ?></td>
                        <td>$ <?= $p->total; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <!-- Totales -->
        <div class="totales">
            <p><strong>Subtotal:</strong> $ <?= $subtotal; ?></p>
            <p><strong>Descuento total:</strong> $ <?= $descuento_total; ?></p>

            <p><strong>IVA (16%):</strong> $ <?= $iva_aplicado; ?></p>
            <p><strong>Total:</strong> $ <?= $total_global; ?></p>
        </div>

        <!-- Firma -->
        <div style="margin-top: 40px; text-align: center;">
            <p>__________________________________</p>
            <p>Autorizado por</p>
        </div>

    </div>

</body>

</html>