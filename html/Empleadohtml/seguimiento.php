<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suplementos Dynamite - Seguimiento de Paquetes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #1a73e8;
            --primary-dark: #0d47a1;
            --secondary: #ff6d00;
            --light: #f5f7fa;
            --dark: #333;
            --gray: #777;
            --success: #4caf50;
            --warning: #ff9800;
            --danger: #f44336;
            --border: #e0e0e0;
            --shadow: 0 4px 12px rgba(0,0,0,0.08);
            --card-bg: #ffffff;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f8f9fc;
            color: #333;
            line-height: 1.6;
        }
        
        /* Header Styles */
        header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 0.8rem 2rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .header-container {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .logo {
            height: 70px;
            width: auto;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        .header-container h1 {
            font-size: 1.8rem;
            font-weight: 600;
        }
        
        #date-time {
            text-align: right;
            font-size: 0.9rem;
            margin-top: -1.5rem;
            opacity: 0.9;
        }
        
        .nav-bar {
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            margin-top: 0.8rem;
            padding: 0.5rem;
        }
        
        .nav-bar ul {
            display: flex;
            list-style: none;
            justify-content: space-between;
        }
        
        .nav-bar li a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: background 0.3s;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-bar li a:hover {
            background: rgba(255,255,255,0.2);
        }
        
        .btn-login {
            background: rgba(255,255,255,0.15);
            padding: 0.5rem 1rem !important;
        }
        
        /* Navigation Styles */
        .nav-empleado {
            background: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            margin-top: 0.5rem;
        }
        
        .nav-empleado ul {
            display: flex;
            list-style: none;
            gap: 1.5rem;
        }
        
        .nav-empleado li a {
            color: var(--dark);
            text-decoration: none;
            padding: 0.5rem 0;
            position: relative;
            font-weight: 500;
        }
        
        .nav-empleado li a:hover {
            color: var(--primary);
        }
        
        .nav-empleado li a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary);
            transition: width 0.3s;
        }
        
        .nav-empleado li a:hover::after {
            width: 100%;
        }
        
        /* Main Content */
        main {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        main h2 {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--dark);
            font-size: 2.2rem;
            position: relative;
            padding-bottom: 0.8rem;
        }
        
        main h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: var(--secondary);
            border-radius: 2px;
        }
        
        /* Tracking Cards */
        .tracking-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.8rem;
            margin-top: 1rem;
        }
        
        .tracking-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
        }
        
        .tracking-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        
        .tracking-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 1.2rem;
        }
        
        .tracking-card h3 {
            font-size: 1.4rem;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }
        
        .tracking-card h3 i {
            font-size: 1.2rem;
        }
        
        .tracking-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .tracking-info {
            margin-bottom: 0.8rem;
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
        }
        
        .tracking-info i {
            color: var(--primary);
            font-size: 1.1rem;
            min-width: 20px;
            margin-top: 3px;
        }
        
        .tracking-info p {
            color: var(--dark);
        }
        
        .tracking-info strong {
            color: #555;
            font-weight: 600;
            min-width: 150px;
            display: inline-block;
        }
        
        /* Status Styles */
        .status {
            display: inline-block;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            text-align: center;
        }
        
        .status-delivered {
            background-color: rgba(76, 175, 80, 0.15);
            color: var(--success);
            border: 1px solid var(--success);
        }
        
        .status-pending {
            background-color: rgba(255, 152, 0, 0.15);
            color: var(--warning);
            border: 1px solid var(--warning);
        }
        
        .status-intransit {
            background-color: rgba(26, 115, 232, 0.15);
            color: var(--primary);
            border: 1px solid var(--primary);
        }
        
        /* Progress Bar */
        .progress-container {
            margin-top: 1rem;
            background: #f0f0f0;
            border-radius: 10px;
            height: 10px;
            overflow: hidden;
        }
        
        .progress-bar {
            height: 100%;
            border-radius: 10px;
            transition: width 0.5s ease;
        }
        
        .progress-intransit {
            width: 60%;
            background: linear-gradient(90deg, var(--primary), #64b5f6);
        }
        
        .progress-delivered {
            width: 100%;
            background: linear-gradient(90deg, var(--success), #81c784);
        }
        
        /* Timeline */
        .timeline {
            position: relative;
            margin-top: 1.5rem;
            padding-left: 30px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 10px;
            top: 0;
            height: 100%;
            width: 2px;
            background: var(--primary);
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 1.5rem;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -30px;
            top: 5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: white;
            border: 3px solid var(--primary);
        }
        
        .timeline-item:last-child {
            margin-bottom: 0;
        }
        
        .timeline-item.delivered::before {
            background: var(--success);
            border-color: var(--success);
        }
        
        .timeline-item.current::before {
            background: var(--primary);
            border-color: var(--primary);
        }
        
        .timeline-content {
            background: #f5f7fa;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            border-left: 3px solid var(--primary);
        }
        
        .timeline-content h4 {
            color: var(--primary);
            margin-bottom: 0.3rem;
        }
        
        .timeline-date {
            font-size: 0.85rem;
            color: var(--gray);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .tracking-container {
                grid-template-columns: 1fr;
            }
            
            .nav-bar ul, .nav-empleado ul {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .header-container {
                flex-direction: column;
                text-align: center;
                gap: 0.8rem;
            }
            
            #date-time {
                text-align: center;
                margin-top: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRt_eZYMvS26mdHNwVQw-zHWqDRdSz5XzAVHQ&s" alt="Logo de Suplementos Dynamite" class="logo">
            <h1>Suplementos Dynamite</h1>
        </div>
        <p id="date-time"></p>
        <nav class="nav-bar">
            <ul>
                <li><a href="empleado.php"><i class="fas fa-home"></i> Inicio</a></li>                         
                <li><a href="#" class="btn-login"><i class="fas fa-user"></i> Empleado #12</a></li>
            </ul>
        </nav>
    </header>

    <div>
        <nav class="nav-empleado">
            <ul>
                <li><a href="consultas/buzon.php"><i class="fas fa-inbox"></i> Buzón de Consultas</a></li>
                <li><a href="productos/listado.php"><i class="fas fa-box"></i> Listado de Productos</a></li>
                <li><a href="proveedores.php"><i class="fas fa-truck-loading"></i> Proveedores</a></li>
            </ul>
        </nav>
    </div>
    
    <main>
        <h2>Seguimiento de Envíos</h2>
        
        <div class="tracking-container">
            <!-- Envío 1 -->
            <div class="tracking-card">
                <div class="tracking-header">
                    <h3><i class="fas fa-box-open"></i> Creatina Monohidratada</h3>
                </div>
                <div class="tracking-content">
                    <div class="tracking-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <p><strong>Ubicación Actual:</strong> Centro de Distribución, Buenos Aires</p>
                    </div>
                    <div class="tracking-info">
                        <i class="fas fa-info-circle"></i>
                        <p><strong>Estado:</strong> <span class="status status-intransit">En Tránsito</span></p>
                    </div>
                    <div class="tracking-info">
                        <i class="fas fa-paper-plane"></i>
                        <p><strong>Fecha de Envío:</strong> 01/11/2024</p>
                    </div>
                    <div class="tracking-info">
                        <i class="fas fa-calendar-check"></i>
                        <p><strong>Fecha de Entrega:</strong> 05/11/2024</p>
                    </div>
                    <div class="tracking-info">
                        <i class="fas fa-truck-loading"></i>
                        <p><strong>Llegó a Destino:</strong> <span class="status status-pending">En Camino</span></p>
                    </div>
                    
                    <div class="progress-container">
                        <div class="progress-bar progress-intransit"></div>
                    </div>
                    
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <h4>Pedido procesado</h4>
                                <p>El pedido ha sido preparado para envío</p>
                                <div class="timeline-date">28/10/2024</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <h4>En tránsito</h4>
                                <p>El paquete ha salido del centro de distribución</p>
                                <div class="timeline-date">30/10/2024</div>
                            </div>
                        </div>
                        <div class="timeline-item current">
                            <div class="timeline-content">
                                <h4>En camino</h4>
                                <p>El paquete está en ruta hacia el destino</p>
                                <div class="timeline-date">01/11/2024</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <h4>Entrega programada</h4>
                                <p>Entrega estimada para el 05/11/2024</p>
                                <div class="timeline-date">05/11/2024</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Envío 2 -->
            <div class="tracking-card">
                <div class="tracking-header">
                    <h3><i class="fas fa-box-open"></i> Proteína Whey 1Kg</h3>
                </div>
                <div class="tracking-content">
                    <div class="tracking-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <p><strong>Ubicación Actual:</strong> Centro Logístico, Córdoba</p>
                    </div>
                    <div class="tracking-info">
                        <i class="fas fa-info-circle"></i>
                        <p><strong>Estado:</strong> <span class="status status-intransit">En Tránsito</span></p>
                    </div>
                    <div class="tracking-info">
                        <i class="fas fa-paper-plane"></i>
                        <p><strong>Fecha de Envío:</strong> 30/10/2024</p>
                    </div>
                    <div class="tracking-info">
                        <i class="fas fa-calendar-check"></i>
                        <p><strong>Fecha de Entrega:</strong> 03/11/2024</p>
                    </div>
                    <div class="tracking-info">
                        <i class="fas fa-truck-loading"></i>
                        <p><strong>Llegó a Destino:</strong> <span class="status status-pending">En Camino</span></p>
                    </div>
                    
                    <div class="progress-container">
                        <div class="progress-bar progress-intransit"></div>
                    </div>
                    
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <h4>Pedido procesado</h4>
                                <p>El pedido ha sido preparado para envío</p>
                                <div class="timeline-date">27/10/2024</div>
                            </div>
                        </div>
                        <div class="timeline-item current">
                            <div class="timeline-content">
                                <h4>En tránsito</h4>
                                <p>El paquete ha llegado al centro logístico de Córdoba</p>
                                <div class="timeline-date">30/10/2024</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <h4>En camino</h4>
                                <p>El paquete será despachado próximamente</p>
                                <div class="timeline-date">Próximamente</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <h4>Entrega programada</h4>
                                <p>Entrega estimada para el 03/11/2024</p>
                                <div class="timeline-date">03/11/2024</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Envío 3 -->
            <div class="tracking-card">
                <div class="tracking-header">
                    <h3><i class="fas fa-box-open"></i> Pre-Entreno Pump 3D</h3>
                </div>
                <div class="tracking-content">
                    <div class="tracking-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <p><strong>Ubicación Actual:</strong> Destinatario Final, Rosario</p>
                    </div>
                    <div class="tracking-info">
                        <i class="fas fa-info-circle"></i>
                        <p><strong>Estado:</strong> <span class="status status-delivered">Entregado</span></p>
                    </div>
                    <div class="tracking-info">
                        <i class="fas fa-paper-plane"></i>
                        <p><strong>Fecha de Envío:</strong> 28/10/2024</p>
                    </div>
                    <div class="tracking-info">
                        <i class="fas fa-calendar-check"></i>
                        <p><strong>Fecha de Entrega:</strong> 01/11/2024</p>
                    </div>
                    <div class="tracking-info">
                        <i class="fas fa-truck-loading"></i>
                        <p><strong>Llegó a Destino:</strong> <span class="status status-delivered">Entregado</span></p>
                    </div>
                    
                    <div class="progress-container">
                        <div class="progress-bar progress-delivered"></div>
                    </div>
                    
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <h4>Pedido procesado</h4>
                                <p>El pedido ha sido preparado para envío</p>
                                <div class="timeline-date">25/10/2024</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <h4>En tránsito</h4>
                                <p>El paquete ha salido del centro de distribución</p>
                                <div class="timeline-date">27/10/2024</div>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-content">
                                <h4>En camino</h4>
                                <p>El paquete está en ruta hacia el destino</p>
                                <div class="timeline-date">29/10/2024</div>
                            </div>
                        </div>
                        <div class="timeline-item delivered">
                            <div class="timeline-content">
                                <h4>Entregado</h4>
                                <p>El paquete fue entregado al destinatario</p>
                                <div class="timeline-date">01/11/2024</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Función para mostrar fecha y hora actual
        function updateDateTime() {
            const now = new Date();
            const options = { 
                weekday: 'long', 
                year: 'numeric', 
                month: 'long', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            };
            document.getElementById('date-time').textContent = now.toLocaleDateString('es-ES', options);
        }
        
        // Actualizar cada segundo
        setInterval(updateDateTime, 1000);
        updateDateTime(); // Inicializar
        
        // Animación para las tarjetas
        document.querySelectorAll('.tracking-card').forEach(card => {
            card.addEventListener('mouseover', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 8px 20px rgba(0,0,0,0.12)';
            });
            
            card.addEventListener('mouseout', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.08)';
            });
        });
    </script>
</body>
</html>