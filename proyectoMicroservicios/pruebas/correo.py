from locust import HttpUser, task, between

class NotificacionesUser(HttpUser):
    wait_time = between(0.1, 0.3)

    @task
    def enviar_notificacion(self):
        payload = {
            "tipo_evento": "nuevo_pedido",
            "datos": {
                "cliente": "Sofía Leyton",
                "email": "sofialeytongonzalez@gmail.com",
                "producto": "Jabones ciruela y flor de vainilla",
                "cantidad": 3,
                "total": 15000
            }
        }
        with self.client.post("/api/notificaciones", json=payload, catch_response=True) as response:
            if response.status_code != 200:
                response.failure(f"Error {response.status_code}: {response.text}")
