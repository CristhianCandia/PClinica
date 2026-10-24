<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Clínica Vida Salud')</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* HEADER */
        .header {
            background-color: #1e5799;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header-logo {
            font-size: 20px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .header-logo::before {
            content: "✚";
            color: #ff4444;
            font-size: 24px;
        }

        .header-user {
            display: flex;
            align-items: center;
            gap: 15px;
            font-size: 14px;
        }

        .header-user .username {
            font-weight: 600;
        }

        .header-user .role-badge {
            background-color: #2989d8;
            padding: 5px 12px;
            border-radius: 4px;
            font-size: 12px;
        }

        /* LAYOUT PRINCIPAL */
        .main-container {
            display: flex;
            flex: 1;
        }

        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background-color: white;
            border-right: 1px solid #e0e0e0;
            padding: 20px 0;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-menu li {
            padding: 0;
        }

        .sidebar-menu a {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover {
            background-color: #f0f4f8;
            border-left-color: #1e5799;
            color: #1e5799;
        }

        .sidebar-menu a.active {
            background-color: #e8f0fe;
            border-left-color: #1e5799;
            color: #1e5799;
            font-weight: 600;
        }

        .sidebar-section-title {
            padding: 15px 20px 8px;
            font-size: 11px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }

        /* CONTENIDO PRINCIPAL */
        .content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
        }

        .content-header {
            margin-bottom: 30px;
        }

        .content-header h1 {
            font-size: 28px;
            color: #333;
            margin-bottom: 5px;
        }

        .content-header p {
            color: #666;
            font-size: 14px;
        }

        /* TARJETAS DE ESTADÍSTICAS */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            border-left: 4px solid #1e5799;
        }

        .stat-card .stat-number {
            font-size: 36px;
            font-weight: bold;
            color: #1e5799;
            margin-bottom: 5px;
        }

        .stat-card .stat-label {
            color: #666;
            font-size: 14px;
        }

        /* ACCESOS RÁPIDOS */
        .quick-access {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .quick-access h3 {
            margin-bottom: 20px;
            color: #333;
            font-size: 18px;
        }

        .quick-access-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn-quick {
            padding: 10px 20px;
            background-color: #1e5799;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background 0.2s;
            cursor: pointer;
        }

        .btn-quick:hover {
            background-color: #164a8a;
        }

        /* FORMULARIO DE LOGOUT */
        .logout-form {
            display: inline;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <header class="header">
        <div class="header-logo">Clínica Vida Salud</div>
        <div class="header-user">
            <span class="username">{{ Auth::user()->nombre_usuario }}</span>
            <span class="role-badge">{{ Auth::user()->rol->nombre }}</span>
        </div>
    </header>

    <!-- CONTENEDOR PRINCIPAL -->
    <div class="main-container">
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li><a href="{{ route('admin.dashboard') }}" class="active">Inicio</a></li>
                
                <li class="sidebar-section-title">Sección</li>
                <li><a href="{{ route('admin.usuarios') }}">Usuarios</a></li>
                
                <li class="sidebar-section-title">Gestión Médica</li>
                <li><a href="{{ route('admin.especialidades') }}">Especialidades</a></li>
                <li><a href="{{ route('admin.medicos') }}">Médicos</a></li>
                <li><a href="{{ route('admin.horarios') }}">Horarios de atención</a></li>
                
                <li class="sidebar-section-title">Pacientes y Citas</li>
                <li><a href="{{ route('admin.pacientes') }}">Pacientes</a></li>
                <li><a href="{{ route('admin.disponibilidad') }}">Disponibilidad</a></li>
                <li><a href="{{ route('admin.citas') }}">Citas</a></li>
                <li><a href="{{ route('admin.agenda') }}">Agenda médica</a></li>
                
                <li class="sidebar-section-title">Reportes</li>
                <li><a href="{{ route('admin.reportes') }}">Reportes</a></li>
                
                <li class="sidebar-section-title">Sesión</li>
                <li>
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" style="background:none;border:none;color:#333;cursor:pointer;padding:12px 20px;width:100%;text-align:left;font-size:14px;">Cerrar sesión</button>
                    </form>
                </li>
            </ul>
        </aside>

        <!-- CONTENIDO -->
        <main class="content">
            @yield('content')
        </main>
    </div>
</body>
</html>