from fastapi import APIRouter, HTTPException, Depends, Path
from fastapi import Body
from models.supplier import SupplierCreate, SupplierUpdate, SupplierResponse
from services.supplier_service import SupplierService
from db.database import db_dependency

router = APIRouter(prefix="/suppliers")

def get_supplier_service(db_session: db_dependency) -> SupplierService:
    return SupplierService(db_session)

# Rutas
@router.post("/", response_model=SupplierResponse, status_code=201)
async def create_supplier(supplier_create: SupplierCreate, service: SupplierService = Depends(get_supplier_service)):
    return service.create_supplier(**supplier_create.model_dump())

@router.get("/", response_model=list[SupplierResponse], status_code=200)
async def get_suppliers(skip: int = 0, limit: int = 100, service: SupplierService = Depends(get_supplier_service), ):
    return service.get_suppliers(skip, limit)

@router.get("/{id}", response_model=SupplierResponse, status_code=200)
async def get_supplier_id(supplier_id: int = Path(..., alias="id"), service: SupplierService = Depends(get_supplier_service)):
    supplier = service.get_supplier_id(supplier_id)

    if not supplier:
        raise HTTPException(status_code=404, detail="Proveedor no encontrado")

    return supplier

@router.put("/{id}", response_model=SupplierResponse, status_code=200)
async def update_supplier(supplier_id: int = Path(..., alias="id"), supplier_update: SupplierUpdate = Body(...), service: SupplierService = Depends(get_supplier_service)):
    try:
        supplier = service.update_supplier(supplier_id=supplier_id, **supplier_update.model_dump())
        
        if not supplier:
            raise HTTPException(status_code=404, detail="Proveedor no encontrado")

        return supplier
    except Exception:
        raise HTTPException(status_code=500, detail="Hubo un error al escribir en la base de datos al actualizar")

@router.delete("/{id}", status_code=200)
async def delete_supplier(supplier_id: int = Path(..., alias="id"), service: SupplierService = Depends(get_supplier_service)):
    try:
        supplier_confirmation = service.delete_supplier(supplier_id)
        
        if not supplier_confirmation:
            raise HTTPException(status_code=404, detail="Proveedor no encontrado")

        return {"resultado": True, "mensaje": "Proveedor eliminado exitosamente"}
    except Exception:
        raise HTTPException(status_code=500, detail="Hubo un error al escribir en la base de datos al actualizar")