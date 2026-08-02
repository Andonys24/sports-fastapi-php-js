from fastapi import FastAPI
from fastapi.middleware.cors import CORSMiddleware
from db.database import engine_db, Base
from routers.v1 import user_routes, product_routes, category_routes, supplier_routes
from routers.v1 import authentication_routes, purchase_routes, order_routes, invoice_routes

Base.metadata.create_all(bind=engine_db)

app = FastAPI(
    title="API de tienda deportiva", 
    description="Esta es la primera version de la API para el proyecto de la tienda de deportes", 
    version="1.0.0")

# Implementacion de CORS Middleware
# Para poder realizar la conexion con el frontend en Javascript
app.add_middleware(
    CORSMiddleware,
    allow_origins=["http://127.0.0.1:5500", "http://localhost:5500"],
    allow_methods=["*"],
    allow_headers=["*"],
)

# Se incluyen todas las rutas utilizadas
app.include_router(authentication_routes.router, prefix="/api/v1", tags=["Autenticacion"])
app.include_router(user_routes.router, prefix="/api/v1", tags=["Users"])
app.include_router(product_routes.router, prefix="/api/v1", tags=["Products"])
app.include_router(category_routes.router, prefix="/api/v1", tags=["Categories"])
app.include_router(supplier_routes.router, prefix="/api/v1", tags=["Suppliers"])
app.include_router(purchase_routes.router, prefix="/api/v1", tags=["Compras"])
app.include_router(order_routes.router, prefix="/api/v1", tags=["Encargos"])
app.include_router(invoice_routes.router, prefix="/api/v1", tags=["Facturas"])