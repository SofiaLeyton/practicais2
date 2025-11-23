import jwt
from django.http import JsonResponse
from django.conf import settings
from functools import wraps

#aqui se maneja la autenticacion de usuarios, solo los admin pueden acceder a este microservicio en general
def admin_required(view_func):
    @wraps(view_func)
    def wrapper(request, *args, **kwargs):
        auth_header = request.headers.get("Authorization")

        token = None
        if auth_header and auth_header.startswith("Bearer "):
            token = auth_header.split(" ")[1]
        elif "token" in request.GET:
            token = request.GET.get("token")

        print("TOKEN RECIBIDO:", token)

        if not token:
            return JsonResponse({"error": "Token no proporcionado"}, status=401)

        try:
            payload = jwt.decode(token, settings.JWT_SECRET, algorithms=["HS256"])

            if payload.get("role") != "admin":
                return JsonResponse({"error": "Acceso denegado. Solo administradores."}, status=403)

            request.user_data = payload

        except jwt.ExpiredSignatureError:
            return JsonResponse({"error": "Token expirado"}, status=401)
        except jwt.InvalidTokenError:
            return JsonResponse({"error": "Token inválido"}, status=401)

        return view_func(request, *args, **kwargs)
    return wrapper
