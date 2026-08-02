from fastapi import APIRouter, Depends, HTTPException
from models.inventory import InventoryUpdate, InventoryJoinedReponse
from services.inventory_service import InventoryService
from services.product_service import ProductService
from db.database import db_dependency

router = APIRouter(prefix="/inventory")

def get_inventory_service(db_session: db_dependency) -> InventoryService:
    return InventoryService(db_session)

def get_product_service(db_session: db_dependency) -> ProductService:
    return ProductService(db_session)

# =========== RUTAS ===========
@router.get("/", response_model=list[InventoryJoinedReponse], status_code=200)
async def get_inventory(skip: int = 0, limit: int = 100, 
        service: InventoryService = Depends(get_inventory_service)):
    return service.get_inventory(skip=skip, limit=limit)

@router.put("/{product_id}", response_model=InventoryUpdate, status_code=200)
async def change_stock_product(product_id: int, new_stock: int,
        product_service: ProductService = Depends(get_product_service)):
    # Al cambiar el stock directamente, valida que el valor dado no sea negativo
    if new_stock < 0:
        raise HTTPException(status_code=500, detail="El stock no puede ser un valor negativo")

    # En caso contrario, procede a cambiar el stock
    product_tmp = product_service.get_product_id(product_id)

    if not product_tmp:
        raise HTTPException(status_code=404, detail="No se encontro el producto")

    # Aplica el cambio de stock con una funcion que no retorna nada
    product_service.update_product_stock(product=product_tmp, new_stock=new_stock)

    # Retorna el nuevo stock del producto como un diccionario
    return {"stock": product_tmp.stock}