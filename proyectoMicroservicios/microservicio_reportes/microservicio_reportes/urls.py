from django.urls import path
from reportes import views

urlpatterns = [
    path("reporte/productos/pdf/", views.reporte_productos_pdf),
    path("reporte/productos/excel/", views.reporte_productos_excel),
    path("reporte/pedidos/pdf/", views.reporte_pedidos_pdf),
    path("reporte/pedidos/excel/", views.reporte_pedidos_excel),
    path("reporte/usuarios/pdf/", views.reporte_usuarios_pdf),
    path("reporte/usuarios/excel/", views.reporte_usuarios_excel),
]
