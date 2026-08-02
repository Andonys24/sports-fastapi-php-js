from fastapi import APIRouter, HTTPException, Depends, Path
from fastapi import Body
from models.product import ProductCreate, ProductUpdate, ProductResponse
from sqlalchemy.exc import IntegrityError
from services.product_service import ProductService
from db.database import db_dependency

router = APIRouter(prefix="/products")

def get_product_service(db_session: db_dependency) -> ProductService:
    return ProductService(db_session)

# Rutas
@router.post("/", response_model=ProductResponse, status_code=201)
async def create_product(product_create: ProductCreate, service: ProductService = Depends(get_product_service)):
    try:
        return service.create_product(**product_create.model_dump())
    # Tipo de excepcion cuando las ForeignKeys no son validas
    except IntegrityError:
        raise HTTPException(status_code=500, detail="Los id de categoria o de proveedor no existen")

@router.get("/", response_model=list[ProductResponse], status_code=200)
async def get_products(skip: int = 0, limit: int = 100, service: ProductService = Depends(get_product_service), ):
    return service.get_products(skip, limit)

@router.get("/{id}", response_model=ProductResponse, status_code=200)
async def get_product_id(product_id: int = Path(..., alias="id"), service: ProductService = Depends(get_product_service)):
    product = service.get_product_id(product_id)

    if not product:
        raise HTTPException(status_code=404, detail="Producto no encontrado")

    return product

@router.put("/{id}", response_model=ProductResponse, status_code=200)
async def update_product(product_id: int = Path(..., alias="id"), product_update: ProductUpdate = Body(...), service: ProductService = Depends(get_product_service)):
    try:
        product = service.update_product(product_id=product_id, **product_update.model_dump())
        
        if not product:
            raise HTTPException(status_code=404, detail="Producto no encontrado")

        return product
    except Exception:
        raise HTTPException(status_code=500, detail="Hubo un error al escribir en la base de datos al actualizar")

@router.delete("/{id}", status_code=200)
async def delete_product(product_id: int = Path(..., alias="id"), service: ProductService = Depends(get_product_service)):
    try:
        product_confirmation = service.delete_product(product_id)
        
        if not product_confirmation:
            raise HTTPException(status_code=404, detail="Producto no encontrado")

        return {"resultado": True, "mensaje": "Producto eliminado exitosamente"}
    except IntegrityError:
        raise HTTPException(status_code=500, detail="No se puede borrar el registro, debido a que esta relacionado a otras tablas")