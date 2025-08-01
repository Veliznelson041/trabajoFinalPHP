<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Proveedores - Suplementos Dynamite</title>
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
            --border: #ddd;
            --shadow: 0 4px 12px rgba(0,0,0,0.08);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            background-color: #f0f2f5;
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
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        .container h1 {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--dark);
            font-size: 2.2rem;
            position: relative;
            padding-bottom: 0.8rem;
        }
        
        .container h1::after {
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
        
        /* Proveedores Grid */
        .proveedor-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 1.8rem;
            margin-top: 1rem;
        }
        
        .proveedor-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: transform 0.3s, box-shadow 0.3s;
            border: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        
        .proveedor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }
        
        .proveedor-header {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 1.2rem;
        }
        
        .proveedor-card h2 {
            font-size: 1.4rem;
            margin-bottom: 0.3rem;
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }
        
        .proveedor-card h2 i {
            font-size: 1.2rem;
        }
        
        .proveedor-content {
            padding: 1.5rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }
        
        .proveedor-info {
            margin-bottom: 0.8rem;
            display: flex;
            align-items: flex-start;
            gap: 0.7rem;
        }
        
        .proveedor-info i {
            color: var(--primary);
            font-size: 1.1rem;
            min-width: 20px;
            margin-top: 3px;
        }
        
        .proveedor-info p {
            color: var(--dark);
        }
        
        .proveedor-info strong {
            color: #555;
            font-weight: 600;
        }
        
        /* Form Styles */
        .add-form {
            padding: 1.5rem;
        }
        
        .add-form h2 {
            color: var(--primary);
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }
        
        .form-group {
            margin-bottom: 1.3rem;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #555;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 0.8rem 1rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        button[type="submit"] {
            background: linear-gradient(135deg, var(--secondary), #e65100);
            color: white;
            border: none;
            padding: 0.9rem 1.8rem;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.3s;
            display: block;
            width: 100%;
            margin-top: 0.5rem;
            box-shadow: 0 4px 6px rgba(255, 109, 0, 0.3);
        }
        
        button[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 10px rgba(255, 109, 0, 0.4);
        }
        
        button[type="submit"]:active {
            transform: translateY(0);
        }
        
        .add-card {
            border: 2px dashed var(--primary);
            background: rgba(26, 115, 232, 0.03);
        }
        
        .add-card .proveedor-header {
            background: transparent;
            color: var(--primary);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .proveedor-container {
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
                <li><a href="productos/listado.php"><i class="fas fa-box"></i> Productos</a></li>
                <li><a href="seguimiento.php"><i class="fas fa-truck"></i> Seguimiento de Paquetes</a></li>
            </ul>
        </nav>
    </div>
    
    <div class="container">
        <h1>Lista de Proveedores</h1>
        <div class="proveedor-container">
            <!-- Proveedor 1 -->
            <div class="proveedor-card">
                <div class="proveedor-header">
                    <h2><i class="fas fa-truck-loading"></i> Suplementos Dynamo</h2>
                </div>
                <div class="proveedor-content">
                    <div class="proveedor-info">
                        <i class="fas fa-phone"></i>
                        <p><strong>Teléfono:</strong> (011) 1234-5678</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-envelope"></i>
                        <p><strong>Email:</strong> info@suplementosdynamo.com</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-pills"></i>
                        <p><strong>Suplemento Proveído:</strong> Proteína de Suero</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-industry"></i>
                        <p><strong>Información de la Fábrica:</strong> Ubicada en Buenos Aires, especializada en productos de alta calidad.</p>
                    </div>
                </div>
            </div>
            
            <!-- Proveedor 2 -->
            <div class="proveedor-card">
                <div class="proveedor-header">
                    <h2><i class="fas fa-truck-loading"></i> NutriPro</h2>
                </div>
                <div class="proveedor-content">
                    <div class="proveedor-info">
                        <i class="fas fa-phone"></i>
                        <p><strong>Teléfono:</strong> (011) 8765-4321</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-envelope"></i>
                        <p><strong>Email:</strong> contacto@nutripro.com</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-pills"></i>
                        <p><strong>Suplemento Proveído:</strong> Creatina Monohidratada</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-industry"></i>
                        <p><strong>Información de la Fábrica:</strong> Fábrica con más de 10 años de experiencia en el sector de suplementos.</p>
                    </div>
                </div>
            </div>
            
            <!-- Proveedor 3 -->
            <div class="proveedor-card">
                <div class="proveedor-header">
                    <h2><i class="fas fa-truck-loading"></i> VitaMax</h2>
                </div>
                <div class="proveedor-content">
                    <div class="proveedor-info">
                        <i class="fas fa-phone"></i>
                        <p><strong>Teléfono:</strong> (011) 5678-1234</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-envelope"></i>
                        <p><strong>Email:</strong> ventas@vitamax.com</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-pills"></i>
                        <p><strong>Suplemento Proveído:</strong> Multivitaminas</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-industry"></i>
                        <p><strong>Información de la Fábrica:</strong> Ubicada en Córdoba, conocida por su innovación en productos saludables.</p>
                    </div>
                </div>
            </div>
            
            <!-- Proveedor 4 -->
            <div class="proveedor-card">
                <div class="proveedor-header">
                    <h2><i class="fas fa-truck-loading"></i> BioSupplements</h2>
                </div>
                <div class="proveedor-content">
                    <div class="proveedor-info">
                        <i class="fas fa-phone"></i>
                        <p><strong>Teléfono:</strong> (011) 3456-7890</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-envelope"></i>
                        <p><strong>Email:</strong> contacto@biosupplements.com</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-pills"></i>
                        <p><strong>Suplemento Proveído:</strong> Omega-3 y Ácidos Grasos</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-industry"></i>
                        <p><strong>Información de la Fábrica:</strong> Fabrica suplementos naturales y orgánicos en La Plata.</p>
                    </div>
                </div>
            </div>
            
            <!-- Proveedor 5 -->
            <div class="proveedor-card">
                <div class="proveedor-header">
                    <h2><i class="fas fa-truck-loading"></i> MuscleGrow</h2>
                </div>
                <div class="proveedor-content">
                    <div class="proveedor-info">
                        <i class="fas fa-phone"></i>
                        <p><strong>Teléfono:</strong> (011) 2345-6789</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-envelope"></i>
                        <p><strong>Email:</strong> soporte@musclegrow.com</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-pills"></i>
                        <p><strong>Suplemento Proveído:</strong> BCAA y Glutamina</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-industry"></i>
                        <p><strong>Información de la Fábrica:</strong> Empresa con sede en Rosario, conocida por su tecnología avanzada en aminoácidos.</p>
                    </div>
                </div>
            </div>
            
            <!-- Proveedor 6 -->
            <div class="proveedor-card">
                <div class="proveedor-header">
                    <h2><i class="fas fa-truck-loading"></i> HealthZone</h2>
                </div>
                <div class="proveedor-content">
                    <div class="proveedor-info">
                        <i class="fas fa-phone"></i>
                        <p><strong>Teléfono:</strong> (011) 4567-8901</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-envelope"></i>
                        <p><strong>Email:</strong> info@healthzone.com</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-pills"></i>
                        <p><strong>Suplemento Proveído:</strong> Vitaminas y Minerales</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-industry"></i>
                        <p><strong>Información de la Fábrica:</strong> Fábrica en Mendoza, líder en suplementos para el sistema inmune.</p>
                    </div>
                </div>
            </div>
            
            <!-- Proveedor 7 -->
            <div class="proveedor-card">
                <div class="proveedor-header">
                    <h2><i class="fas fa-truck-loading"></i> FitPower</h2>
                </div>
                <div class="proveedor-content">
                    <div class="proveedor-info">
                        <i class="fas fa-phone"></i>
                        <p><strong>Teléfono:</strong> (011) 6789-0123</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-envelope"></i>
                        <p><strong>Email:</strong> ventas@fitpower.com</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-pills"></i>
                        <p><strong>Suplemento Proveído:</strong> Pre-entrenos</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-industry"></i>
                        <p><strong>Información de la Fábrica:</strong> Ubicada en Mar del Plata, especializada en productos para rendimiento deportivo.</p>
                    </div>
                </div>
            </div>
            
            <!-- Proveedor 8 -->
            <div class="proveedor-card">
                <div class="proveedor-header">
                    <h2><i class="fas fa-truck-loading"></i> PureLife</h2>
                </div>
                <div class="proveedor-content">
                    <div class="proveedor-info">
                        <i class="fas fa-phone"></i>
                        <p><strong>Teléfono:</strong> (011) 7890-1234</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-envelope"></i>
                        <p><strong>Email:</strong> contacto@purelife.com</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-pills"></i>
                        <p><strong>Suplemento Proveído:</strong> Extractos Herbales</p>
                    </div>
                    <div class="proveedor-info">
                        <i class="fas fa-industry"></i>
                        <p><strong>Información de la Fábrica:</strong> Planta en Salta, centrada en suplementos a base de plantas naturales.</p>
                    </div>
                </div>
            </div>
            
            <!-- Formulario para nuevo proveedor -->
            <div class="proveedor-card add-card">
                <div class="proveedor-header">
                    <h2><i class="fas fa-plus-circle"></i> Añadir Nuevo Proveedor</h2>
                </div>
                <div class="add-form">
                    <form>
                        <div class="form-group">
                            <label for="nombreProveedor"><i class="fas fa-building"></i> Nombre:</label>
                            <input type="text" id="nombreProveedor" name="nombreProveedor" placeholder="Nombre del proveedor" required>
                        </div>
                        <div class="form-group">
                            <label for="telefonoProveedor"><i class="fas fa-phone"></i> Teléfono:</label>
                            <input type="text" id="telefonoProveedor" name="telefonoProveedor" placeholder="Teléfono del proveedor" required>
                        </div>
                        <div class="form-group">
                            <label for="emailProveedor"><i class="fas fa-envelope"></i> Email:</label>
                            <input type="email" id="emailProveedor" name="emailProveedor" placeholder="Email del proveedor" required>
                        </div>
                        <div class="form-group">
                            <label for="suplementoProveedor"><i class="fas fa-pills"></i> Suplemento Proveído:</label>
                            <input type="text" id="suplementoProveedor" name="suplementoProveedor" placeholder="Tipo de suplemento" required>
                        </div>
                        <div class="form-group">
                            <label for="infoProveedor"><i class="fas fa-info-circle"></i> Información de la Fábrica:</label>
                            <textarea id="infoProveedor" name="infoProveedor" placeholder="Detalles sobre la fábrica" required></textarea>
                        </div>
                        <button type="submit"><i class="fas fa-plus"></i> Añadir Proveedor</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
        
        // Animación para el botón de añadir proveedor
        document.querySelector('button[type="submit"]').addEventListener('mouseover', function() {
            this.style.transform = 'translateY(-2px)';
            this.style.boxShadow = '0 6px 10px rgba(255, 109, 0, 0.4)';
        });
        
        document.querySelector('button[type="submit"]').addEventListener('mouseout', function() {
            this.style.transform = 'translateY(0)';
            this.style.boxShadow = '0 4px 6px rgba(255, 109, 0, 0.3)';
        });
    </script>
</body>
</html>