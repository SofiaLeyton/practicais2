from locust import HttpUser, task, between

class SeguridadUser(HttpUser):
    wait_time = between(0.1, 0.2)

    @task
    def login(self):
        payload = {
            "email": "sofialeytongonzalez@gmail.com",
            "password": "123456"
        }
        self.client.post("/api/login", json=payload)