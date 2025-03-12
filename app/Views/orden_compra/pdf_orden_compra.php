<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden de Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .container {
            width: 100%;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            width: 150px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .table th {
            background-color: #f4f4f4;
        }

        .totales {
            margin-top: 15px;
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Encabezado -->
        <div class="header">
            <img src="<?= base_url('assets/img/logo.png'); ?>" alt="Logo Empresa">
            <h2>Orden de Compra</h2>
            <p><strong>Fecha:</strong> <?= date('Y-m-d'); ?></p>
        </div>

        <!-- Datos de la Empresa -->
        <table class="table">
            <tr>
                <td><strong>Empresa:</strong> Mi Empresa S.A. de C.V.</td>
                <td><strong>RFC:</strong> XAXX010101000</td>
            </tr>
            <tr>
                <td><strong>Dirección:</strong> Calle Falsa 123, CDMX</td>
                <td><strong>Teléfono:</strong> 55-5555-5555</td>
            </tr>
        </table>

        <!-- Datos del Proveedor -->
        <h3>Datos del Proveedor</h3>
        <table class="table">
            <tr>
                <td><strong>Proveedor:</strong></td>
                <td><strong>ID:</strong></td>
            </tr>
            <tr>
                <td><strong>Dirección:</strong> </td>
                <td><strong>Teléfono:</strong></td>
            </tr>
        </table>

        <!-- Detalles de Productos -->
        <h3>Productos</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Unidad</th>
                    <th>Precio Unitario</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

        <!-- Totales -->
        <div class="totales">
            <p><strong>Subtotal:</strong> $</p>
            <p><strong>IVA (16%):</strong> $</p>
            <p><strong>Total:</strong> $</p>
        </div>

        <!-- Firma -->
        <div style="margin-top: 40px; text-align: center;">
            <p>__________________________________</p>
            <p>Autorizado por</p>
        </div>
    </div>
</body>

</html>