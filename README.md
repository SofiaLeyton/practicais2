🧱 Microservicios Incluidos 🔑 1. Microservicio de Seguridad (seguridad_app)
Tecnologías: Laravel + PHP-FPM
Funcionalidades: Registro de usuarios/manejo de roles Login Logout Recuperación de contraseña Corre en el puerto interno 80 del contenedor (mapeado a 8003 local)

⚙️ Instalación
# Entrar al directorio del microservicio
cd microservicio_seguridad
# Instalar dependencias
composer install
# Copiar archivo de entorno y configurar la conexión a MySQL
cp .env.example .env
# Generar la key de la aplicación
php artisan key:generate
# Ejecutar migraciones
php artisan migrate

📦 2. Microservicio de Productos (microservicio_productos) Tecnologías: Python (Flask / FastAPI) Funcionalidades: CRUD de productos Puerto interno: 5000

🧾 3. Microservicio de Reportes (microservicio_reportes) Tecnologías: Python + Django Funcionalidades: Generación de reportes solo si el usuario es admin Puerto interno: 8002

📬 4. Microservicio de Correos (microservicio_correos) Tecnologías: Laravel Funcionalidades: Envío de correos electrónicos, funciona cuando se realiza un pedido, es decir un correo de confirmacion se envia automatico si se realiza un pedido Puerto interno: 8000

🛒 5. Microservicio de Pedidos (pedidos_api) Tecnologías: Python Funcionalidades: Gestión de pedidos Puerto interno: 5001

🧪 Pruebas Realizadas
Se hicieron pruebas funcionales de:
autenticación (Seguridad),
CRUD de productos,
creación de pedidos,
envío de correos,
generación de reportes.
Además se implementaron pruebas de carga con Locust para medir:
cantidad máxima de usuarios concurrentes,
tiempos de respuesta por microservicio,
estabilidad bajo carga.

▶️ Cómo iniciar cada microservicio
🔐 1. Microservicio de Seguridad (Laravel – PHP)
cd microservicio_seguridad
php artisan serve

📦 2. Microservicio de Productos (Flask – Python)
Entra a su carpeta: cd microservicio_productos
Ejecuta el servicio: python microservicioProductos.py

🛒 3. Microservicio de Pedidos (Flask – Python)
cd microservicio_pedidos
python microservicioPedidos.py

📧 4. Microservicio de Correos (Laravel - php)
Este no es necesario iniciarlo, cuando se realiza un pedido el microservicio correos envia correo de confirmacion automaticamente.

📄 5. Microservicio de Reportes (Django – Python)
cd microservicio_reportes
python microservicioR.py runserver

EJEMPLO DE PRUEBA CON THUNDER: 
POST http://127:0.0.1.5001/crearPedido Content-Type: application/json { "cliente": "Sofia Leyton", "email": "sleyton@unal.edu.cp", "producto": "Mouse", "cantidad": 80, "total": 125000 }
RESPUESTA: { "mensaje": "Pedido creado correctamente y guardado en MongoDB", "pedido": { "_id": "69236e3c43e42e82c6c12f54", "cantidad": 80, "cliente": "Sofia Leyton", "email": "sleyton@unal.edu.co", "producto": "Mouse", "total": 125000 } } Y SE ENVIA EL CORREO AUTOMATICAMENTE AL GMAIL DE SLEYTON@UNAL.EDU.CO
