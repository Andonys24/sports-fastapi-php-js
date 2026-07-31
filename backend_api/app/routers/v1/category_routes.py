from fastapi import APIRouter, HTTPException, Depends
from models.category import CategoryCreate, CategoryUpdate, CategoryResponse
from services.category_service import CategoryService
from db.database import db_dependency

router = APIRouter()

def get_category_service(db_session: db_dependency) -> CategoryService:
    return CategoryService(db_session)

# Rutas
@router.post("/categories", response_model=CategoryResponse, status_code=201)
async def create_category(category_create: CategoryCreate, service: CategoryService = Depends(get_category_service)):
    return service.create_category(**category_create.model_dump())

@router.get("/categories", response_model=list[CategoryResponse], status_code=200)
async def get_categories(skip: int = 0, limit: int = 100, service: CategoryService = Depends(get_category_service), ):
    return service.get_categories(skip, limit)

@router.get("/categories/{id}", response_model=CategoryResponse, status_code=200)
async def get_category_id(category_id: int, service: CategoryService = Depends(get_category_service)):
    category = service.get_category_id(category_id)

    if not category:
        raise HTTPException(status_code=404, detail="Categoria no encontrada")

    return category

@router.put("/categories/{id}", response_model=CategoryResponse, status_code=200)
async def update_category(category_id: int, category_update: CategoryUpdate, service: CategoryService = Depends(get_category_service)):
    try:
        category = service.update_category(category_id=category_id, **category_update.model_dump())
        
        if not category:
            raise HTTPException(status_code=404, detail="Categoria no encontrada")

        return category
    except Exception as e:
        raise HTTPException(status_code=304, detail="Hubo un error al escribir en la base de datos al actualizar")

@router.delete("/categories/{id}", status_code=204)
async def delete_category(category_id: int, service: CategoryService = Depends(get_category_service)):
    try:
        category_confirmation = service.delete_category(category_id)
        
        if not category_confirmation:
            raise HTTPException(status_code=404, detail="Categoria no encontrada")

        return {"success": True}
    except Exception as e:
        raise HTTPException(status_code=304, detail="Hubo un error al escribir en la base de datos al actualizar")