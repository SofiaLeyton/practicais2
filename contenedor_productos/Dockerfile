# Imagen base
FROM python:3.12-slim

# Evita interacciones al instalar paquetes
ENV DEBIAN_FRONTEND=noninteractive

# Actualiza e instala dependencias del sistema
RUN apt-get update && apt-get install -y \
    gcc \
    python3-dev \
    build-essential \
    && rm -rf /var/lib/apt/lists/*

# Actualiza pip
RUN pip install --no-cache-dir --upgrade pip

# Copia los archivos del microservicio
WORKDIR /app
COPY . /app

# Instala dependencias de Python
RUN pip install --no-cache-dir flask pymongo pandas flask-limiter requests openpyxl

# Expone el puerto del microservicio
EXPOSE 5000

# Comando de inicio
CMD ["python", "microservicioProductos.py"]
