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
            font-size: 14px;
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
                Marco Antonio Cardona<br>
                Iexe Universidad<br>
                Blvrd Esteban de Antuñano 2702, Reforma, 72160 Heroica Puebla de Zaragoza, Pue.<br>
                800 286 8464
            </div>
            <div class="info-box">
                <strong>Enviar a:</strong>
                Nombre<br>
                Compañía<br>
                Dirección<br>
                Ciudad, Estado, Código Postal<br>
                Teléfono
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>CANTIDAD</th>
                    <th>PESO POR</th>
                    <th>DESCRIPCIÓN</th>
                    <th>PRECIO UNITARIO</th>
                    <th>TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>kg</td>
                    <td>Producto A</td>
                    <td>$50.00</td>
                    <td>$50.00</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>kg</td>
                    <td>Producto B</td>
                    <td>$30.00</td>
                    <td>$60.00</td>
                </tr>
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