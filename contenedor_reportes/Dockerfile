# Imagen base de Python
FROM python:3.12-slim

# Evitar archivos pyc y buffer de Python
ENV PYTHONDONTWRITEBYTECODE 1
ENV PYTHONUNBUFFERED 1

# Crear directorio de la app dentro del contenedor
WORKDIR /app/microservicio_reportes

# Copiar requirements.txt desde la carpeta contenedora
COPY requirements.txt /app/microservicio_reportes/

# Instalar dependencias
RUN pip install --upgrade pip
RUN pip install -r requirements.txt

# Copiar todo el proyecto al contenedor
COPY microservicio_reportes/ /app/microservicio_reportes/

# Exponer puerto de Django
EXPOSE 8000

# Comando por defecto para iniciar Django
CMD ["python", "manage.py", "runserver", "0.0.0.0:8002"]

#http://localhost:8002/reporte/productos/pdf/?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiZW1haWwiOiJzbGV5dG9uQHVuYWwuZWR1LmNvIiwicm9sZSI6ImFkbWluIiwiaWF0IjoxNzYzNDQ1NjM5LCJleHAiOjE3NjM0NjAwMzl9.ue-ba3YHAiKcVUKAtH6TSs7-jxsxOUtk0eyDccE91q8http://localhost:8002/reporte/productos/pdf/?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOiIyIiwiZW1haWwiOiJzbGV5dG9uQHVuYWwuZWR1LmNvIiwicm9sZSI6ImFkbWluIiwiaWF0IjoxNzYzNDQ1NjM5LCJleHAiOjE3NjM0NjAwMzl9.ue-ba3YHAiKcVUKAtH6TSs7-jxsxOUtk0eyDccE91q8