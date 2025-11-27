from locust import HttpUser, task, between

class PedidosUser(HttpUser):
    wait_time = between(0.1, 0.3) 

    @task
    def listar_pedidos(self):
        response = self.client.get("/verPedidos")

        if response.status_code != 200:
            print(f"❌ Error al listar pedidos ({response.status_code}): {response.text}")