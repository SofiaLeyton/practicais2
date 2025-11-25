from locust import HttpUser, task, between

class VerProductoUser(HttpUser):
    wait_time = between(1, 3)

    @task
    def ver_productos(self):
        with self.client.get("/verProducto", catch_response=True) as response:
            if response.status_code == 200:
                try:
                    data = response.json()
                    if isinstance(data, list):
                        response.success()
                    else:
                        response.failure("Respuesta no es una lista de productos")
                except Exception as e:
                    response.failure(f"Error procesando JSON: {e}")
            else:
                response.failure(f"Fallo al obtener productos: {response.status_code} - {response.text}")
