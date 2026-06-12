<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte VEN 911</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #b91c1c;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo-container {
            width: 120px;
            float: left;
        }
        .logo-container img {
            width: 100%;
            height: auto;
        }
        .title-container {
            float: right;
            text-align: right;
            width: 60%;
        }
        .title-container h1 {
            color: #1e293b;
            font-size: 18px;
            text-transform: uppercase;
            margin: 0 0 5px 0;
        }
        .title-container p {
            color: #64748b;
            font-size: 10px;
            margin: 0;
        }
        .clear {
            clear: both;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            font-size: 11px;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9px;
            color: white;
            text-transform: uppercase;
            display: inline-block;
        }
        .badge-red { background-color: #ef4444; }
        .badge-green { background-color: #22c55e; }
        .badge-blue { background-color: #3b82f6; }
        .badge-amber { background-color: #f59e0b; }
        .badge-bright-green { background-color: #10b981; border: 1px solid #059669; }
        
        /* Utility classes */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .mt-20 { margin-top: 20px; }
        
        .footer {
            position: fixed;
            bottom: 0px;
            left: 0px;
            right: 0px;
            height: 30px;
            border-top: 1px solid #cbd5e1;
            padding-top: 5px;
            text-align: center;
            font-size: 9px;
            color: #64748b;
        }
        .page-number:after { content: counter(page); }
    </style>
</head>
<body>
    <?php
        $path = public_path('img/logo_ven911.png');
        $base64 = '';
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
    ?>
    <div class="header">
        <div class="logo-container">
            @if($base64)
                <img src="{{ $base64 }}" alt="VEN 911 Logo">
            @else
                <h2>VEN 911</h2>
            @endif
        </div>
        <div class="title-container">
            <h1>{{ $tituloReporte ?? 'Reporte de Sistema PBX' }}</h1>
            <p>Generado el: {{ date('d/m/Y H:i:s') }}</p>
            @if(isset($filtro))
                <p>Filtro: {{ $filtro }}</p>
            @endif
        </div>
        <div class="clear"></div>
    </div>

    <div class="content">
        @yield('content')
    </div>

    <div class="footer">
        PBX Receptor - Monitoreo Avanzado | Página <span class="page-number"></span>
    </div>
</body>
</html>
