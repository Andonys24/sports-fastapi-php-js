from fastapi import APIRouter, HTTPException, Depends
from models.product import ProductCreate, ProductUpdate, ProductResponse
from services.product_service import ProductService
from db.database import db_dependency

router = APIRouter()

def get_product_service(db_session: db_dependency) -> ProductService:
    return ProductService(db_session)

# Rutas
@router.post("/products", response_model=ProductResponse, status_code=201)
async def create_product(product_create: ProductCreate, service: ProductService = Depends(get_product_service)):
    return service.create_product(**product_create.model_dump())

@router.get("/products", response_model=list[ProductResponse], status_code=200)
async def get_products(skip: int = 0, limit: int = 100, service: ProductService = Depends(get_product_service), ):
    return service.get_products(skip, limit)

@router.get("/products/{id}", response_model=ProductResponse, status_code=200)
async def get_product_id(product_id: int, service: ProductService = Depends(get_product_service)):
    product = service.get_product_id(product_id)

    if not product:
        raise HTTPException(status_code=404, detail="Producto no encontrado")

    return product

@router.put("/products/{id}", response_model=ProductResponse, status_code=200)
async def update_product(product_id: int, product_update: ProductUpdate, service: ProductService = Depends(get_product_service)):
    try:
        product = service.update_product(product_id=product_id, **product_update.model_dump())
        
        if not product:
            raise HTTPException(status_code=404, detail="Producto no encontrado")

        return product
    except Exception as e:
        raise HTTPException(status_code=304, detail="Hubo un error al escribir en la base de datos al actualizar")

@router.delete("/products/{id}", status_code=204)
async def delete_product(product_id: int, service: ProductService = Depends(get_product_service)):
    try:
        product_confirmation = service.delete_product(product_id)
        
        if not product_confirmation:
            raise HTTPException(status_code=404, detail="Producto no encontrado")

        return {"success": True}
    except Exception as e:
        raise HTTPException(status_code=304, detail="Hubo un error al escribir en la base de datos al actualizar")