from flask import Flask, jsonify, request
from pymongo import MongoClient
from bson import ObjectId
import jwt
import requests
import os

app = Flask(__name__)

# Conexión a MongoDB
client = MongoClient("mongodb://localhost:27017/")
db = client['microservicios']
productos_collection = db['productos']
pedidos_collection = db['pedidos']

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

def notificar_evento(evento, data):
    url = "http://localhost:8000/api/webhook/evento"
    try:
        requests.post(url, json={"evento": evento, "data": data})
    except Exception as e:
        print("Error al notificar:", e)


@app.route("/crearPedido", methods=["POST"])
def crear_pedido():
    data = request.get_json()
    campos_requeridos = ["cliente", "email", "producto", "cantidad", "total"]
    if not all(campo in data for campo in campos_requeridos):
        return jsonify({"error": "Faltan datos en la solicitud"}), 400

    pedido = {
        "cliente": data["cliente"],
        "email": data["email"],
        "producto": data["producto"],
        "cantidad": data["cantidad"],
        "total": data["total"]
    }

    # Guardar el pedido en MongoDB
    resultado = pedidos_collection.insert_one(pedido)
    pedido["_id"] = str(resultado.inserted_id)

    # Notificar al microservicio de correos (Laravel)
    try:
        webhook_url = "http://127.0.0.1:8000/api/notificaciones"
        response = requests.post(webhook_url, json={
        "tipo_evento": "nuevo_pedido",
        "datos": pedido
        })
        print("📤 Enviado al microservicio de correos:", response.status_code, response.text)
    except Exception as e:
         print("⚠️ Error enviando webhook:", e)

    return jsonify({
        "mensaje": "Pedido creado correctamente y guardado en MongoDB",
        "pedido": pedido
    }), 201

#Ver todos los pedidos
@app.route("/verPedidos", methods=["GET"])
def listar_pedidos():
    pedidos = list(pedidos_collection.find())
    return jsonify(convert_mongo_to_json(pedidos)), 200

if __name__ == "__main__":
    app.run(host="0.0.0.0", port=5001, debug=True)
