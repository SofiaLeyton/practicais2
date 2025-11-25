from locust import HttpUser, task, between

class ReporteProductosPDFUser(HttpUser):
    wait_time = between(1, 3) 

    @task
    def generar_reporte_pdf(self):
        """
        Prueba de carga para el endpoint /reporte_productos_pdf
        - Solicita el PDF de productos
        - Verifica que la respuesta sea correcta (200)
        - Mide tiempos de respuesta y tamaño del archivo
        """
        with self.client.get("/reporte/productos/pdf/", catch_response=True, stream=True) as response:
            if response.status_code == 200 and response.headers.get("Content-Type") == "application/pdf":
                if len(response.content) > 100:
                    response.success()
                else:
                    response.failure("PDF vacío o incompleto")
            else:
                response.failure(f"Error {response.status_code}: {response.text}")
