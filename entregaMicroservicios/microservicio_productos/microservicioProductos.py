from flask import Flask, jsonify, request, send_file
from pymongo import MongoClient
from bson import ObjectId
import io
import pandas as pd
from flask_limiter import Limiter
from flask_limiter.util import get_remote_address
import requests

app = Flask(__name__)

# Rate limiter: 10 requests por minuto por IP
#limiter = Limiter(get_remote_address, app=app, default_limits=["1000 per minute"])

# Conexión a MongoDB
client = MongoClient("mongodb://localhost:27017/")
db = client['microservicios']
productos_collection = db['productos']

@app.before_request
def verificar_todo():
    # Estas rutas públicas no requieren token
    rutas_publicas = ["/", "/login", "/verProducto", "/health"]

    if request.path in rutas_publicas:
        return None  #Entonces se permite el acceso sin token

    # ✅ Verificación del token para el resto de rutas
    auth_header = request.headers.get("Authorization")
    if not auth_header or not auth_header.startswith("Bearer "):
        return jsonify({"error": "Token no proporcionado"}), 401

    token = auth_header.split(" ")[1]
    headers = {"Authorization": f"Bearer {token}", "Accept": "application/json"}

    try:
        response = requests.get("http://127.0.0.1:8000/api/user", headers=headers, timeout=2)
        print("Respuesta validación token:", response.status_code)
    except Exception as e:
        print("Error validando token:", e)
        return jsonify({"error": "Servicio de autenticación no disponible"}), 503

    if response.status_code != 200:
        return jsonify({"error": "Token inválido o expirado"}), 401


# Función para convertir MongoDB a JSON
def convert_mongo_to_json(data):
    if isinstance(data, list):
        return [convert_mongo_to_json(item) for item in data]
    elif isinstance(data, dict):
        return {key: convert_mongo_to_json(value) for key, value in data.items()}
    elif isinstance(data, ObjectId):
        return str(data)
    else:
        return data

@app.route("/crearProducto", methods=["POST"])
def crear_producto():
    data = request.get_json()
    result = productos_collection.insert_one({
        "nombre": data["nombre"],
        "precio": float(data["precio"]),
        "cantidad": float(data["cantidad"])
    })
    return jsonify({"mensaje": "Producto ingresado a la base de datos", "id": str(result.inserted_id)}), 201

@app.route("/eliminarProducto", methods=["DELETE"])
def eliminar_producto():
    data = request.get_json()
    nombre = data.get("nombre")
    if not nombre:
        return jsonify({"error": "Debes enviar el nombre del producto"}), 400
    resultado = productos_collection.delete_one({"nombre": nombre})
    if resultado.deleted_count == 1:
        return jsonify({"mensaje": f"Producto '{nombre}' eliminado correctamente"}), 200
    else:
        return jsonify({"mensaje": f"No se encontró el producto '{nombre}'"}), 404

@app.route("/modificarProducto", methods=["PUT"])
def modificar_producto():
    data = request.get_json()
    nombre = data.get("nombre")
    if not nombre:
        return jsonify({"error": "Debes enviar el nombre del producto"}), 400

    nuevos_datos = {}
    if "precio" in data:
        nuevos_datos["precio"] = float(data["precio"])
    if "cantidad" in data:
        nuevos_datos["cantidad"] = float(data["cantidad"])
    if "nuevo_nombre" in data:
        nuevos_datos["nombre"] = data["nuevo_nombre"]

    if not nuevos_datos:
        return jsonify({"error": "Debes enviar al menos un campo para modificar"}), 400

    resultado = productos_collection.update_one({"nombre": nombre}, {"$set": nuevos_datos})
    if resultado.matched_count == 0:
        return jsonify({"mensaje": f"No se encontró el producto '{nombre}'"}), 404

    return jsonify({"mensaje": f"Producto '{nombre}' actualizado correctamente"}), 200

@app.route("/verProducto", methods=['GET'])
def ver_productos():
    productos = list(productos_collection.find())
    return jsonify(convert_mongo_to_json(productos)), 200

@app.route("/verProducto/<string:nombre>", methods=['GET'])
def ver_producto(nombre):
    producto = productos_collection.find_one({"nombre": nombre})
    if not producto:
        return jsonify({"mensaje": f"No se encontró el producto '{nombre}'"}), 404
    return jsonify(convert_mongo_to_json([producto])[0]), 200

@app.route("/generarReportes", methods=["GET"])
def exportar_productos():
    productos = list(productos_collection.find())
    if not productos:
        return jsonify({"mensaje": "No hay productos en la base de datos"}), 404

    df = pd.DataFrame(convert_mongo_to_json(productos))
    formato = request.args.get("formato", "csv")
    buffer = io.BytesIO()

    if formato == "excel":
        df.to_excel(buffer, index=False)
        buffer.seek(0)
        return send_file(buffer, as_attachment=True, download_name="productos.xlsx",
                         mimetype="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
    else:
        df.to_csv(buffer, index=False)
        buffer.seek(0)
        return send_file(buffer, as_attachment=True, download_name="productos.csv", mimetype="text/csv")

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5000, debug=True)
