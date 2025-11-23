🧱 Microservicios Incluidos
🔑 1. Microservicio de Seguridad (seguridad_app)

Tecnologías: Laravel + PHP-FPM

Funcionalidades:
Registro de usuarios/manejo de roles
Login
Logout
Recuperación de contraseña
Corre en el puerto interno 80 del contenedor (mapeado a 8003 local)

📦 2. Microservicio de Productos (microservicio_productos)
Tecnologías: Python (Flask / FastAPI)
Funcionalidades:
CRUD de productos
Puerto interno: 5000

🧾 3. Microservicio de Reportes (microservicio_reportes)
Tecnologías: Python + Django
Funcionalidades:
Generación de reportes solo si el usuario es admin
Puerto interno: 8002

📬 4. Microservicio de Correos (microservicio_correos)
Tecnologías: Laravel
Funcionalidades:
Envío de correos electrónicos, funciona cuando se realiza un pedido, es decir un correo de confirmacion se envia automatico si se realiza un pedido
Puerto interno: 8000

🛒 5. Microservicio de Pedidos (pedidos_api)
Tecnologías: Python
Funcionalidades:
Gestión de pedidos
Puerto interno: 5001

🌐 API Gateway (gateway)
✔️ Función
Centraliza todas las peticiones del frontend y las enruta entre los microservicios.
✔️ Características principales
Servicio desarrollado en Laravel
Usa un único archivo de rutas para exponer endpoints externos
Redirige las peticiones según las URLs configuradas en el archivo .env
Puerto interno: 80
Puerto externo: 9000

Ejemplo de endpoint accesible desde el cliente:
http://localhost:9000/api/pedidos/crearPedido

🐳Cómo Ejecutar el Proyecto
1. Levantar toda la infraestructura:  sudo docker compose up -d --build
2. Confirmacion de levantamiento: sudo docker compose ps

EJEMPLO CON GATEWAY:
POST http://localhost:9000/api/pedidos/crearPedido
Content-Type: application/json
{
  "cliente": "Sofia Leyton",
  "email": "sleyton@unal.edu.cp",
  "producto": "Mouse",
  "cantidad": 80,
  "total": 125000
}

RESPUESTA: 
{
  "mensaje": "Pedido creado correctamente y guardado en MongoDB",
  "pedido": {
    "_id": "69236e3c43e42e82c6c12f54",
    "cantidad": 80,
    "cliente": "Sofia Leyton",
    "email": "sleyton@unal.edu.co",
    "producto": "Mouse",
    "total": 125000
  }
} Y SE ENVIA EL CORREO AUTOMATICAMENTE AL GMAIL DE SLEYTON@UNAL.EDU.CO

NOTAS SOBRE EL PROYECTO:
El gateway: Es la única entrada pública del sistema → http://localhost:9000, centraliza todas las solicitudes de frontend.
Evita exponer los microservicios directamente y reenvía la petición según: 
GET/POST → /api/seguridad/*
GET/POST → /api/productos/*
GET/POST → /api/pedidos/*
etc...
Si cambia un microservicio o contenedor, se debe solo reconstruir ese servicio.
Los puertos externos NO deben usarse dentro de los contenedores; siempre usa los nombres de servicio.
