from fastapi import APIRouter, HTTPException, Depends
from models.supplier import SupplierCreate, SupplierUpdate, SupplierResponse
from services.supplier_service import SupplierService
from db.database import db_dependency

router = APIRouter()

def get_supplier_service(db_session: db_dependency) -> SupplierService:
    return SupplierService(db_session)

# Rutas
@router.post("/suppliers", response_model=SupplierResponse, status_code=201)
async def create_supplier(supplier_create: SupplierCreate, service: SupplierService = Depends(get_supplier_service)):
    return service.create_supplier(**supplier_create.model_dump())

@router.get("/suppliers", response_model=list[SupplierResponse], status_code=200)
async def get_suppliers(skip: int = 0, limit: int = 100, service: SupplierService = Depends(get_supplier_service), ):
    return service.get_suppliers(skip, limit)

@router.get("/suppliers/{id}", response_model=SupplierResponse, status_code=200)
async def get_supplier_id(supplier_id: int, service: SupplierService = Depends(get_supplier_service)):
    supplier = service.get_supplier_id(supplier_id)

    if not supplier:
        raise HTTPException(status_code=404, detail="Proveedor no encontrado")

    return supplier

@router.put("/suppliers/{id}", response_model=SupplierResponse, status_code=200)
async def update_supplier(supplier_id: int, supplier_update: SupplierUpdate, service: SupplierService = Depends(get_supplier_service)):
    try:
        supplier = service.update_supplier(supplier_id=supplier_id, **supplier_update.model_dump())
        
        if not supplier:
            raise HTTPException(status_code=404, detail="Proveedor no encontrado")

        return supplier
    except Exception as e:
        raise HTTPException(status_code=304, detail="Hubo un error al escribir en la base de datos al actualizar")

@router.delete("/suppliers/{id}", status_code=204)
async def delete_supplier(supplier_id: int, service: SupplierService = Depends(get_supplier_service)):
    try:
        supplier_confirmation = service.delete_supplier(supplier_id)
        
        if not supplier_confirmation:
            raise HTTPException(status_code=404, detail="Proveedor no encontrado")

        return {"success": True}
    except Exception as e:
        raise HTTPException(status_code=304, detail="Hubo un error al escribir en la base de datos al actualizar")