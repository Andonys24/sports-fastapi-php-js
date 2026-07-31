from fastapi import FastAPI
import uvicorn
from db.database import engine_db, Base
from routers.v1 import user_routes, product_routes, category_routes, supplier_routes

Base.metadata.create_all(bind=engine_db)

app = FastAPI(
    title="API de tienda deportiva", 
    description="Esta es la primera version de la API para el proyecto de la tienda de deportes", 
    version="1.0.0")

app.include_router(user_routes.router, prefix="/api/v1", tags=["Users"])
app.include_router(product_routes.router, prefix="/api/v1", tags=["Products"])
app.include_router(category_routes.router, prefix="/api/v1", tags=["Categories"])
app.include_router(supplier_routes.router, prefix="/api/v1", tags=["Suppliers"])

if __name__ == "__main__":
    uvicorn.run("main:app", host="localhost", reload=True)