from fastapi import APIRouter, HTTPException, Depends
from models.purchase import PurchaseCreate, PurchaseResponse, PurchaseJoinedResponse
from services.purchase_service import PurchaseService
from services.product_service import ProductService
from db.database import db_dependency
from sqlalchemy.exc import IntegrityError

router = APIRouter(prefix="/purchases")

def get_purchases_services(db_session: db_dependency) -> PurchaseService:
    return PurchaseService(db_session)

def get_product_services(db_session: db_dependency) -> ProductService:
    return ProductService(db_session)

@router.post("/", response_model=PurchaseResponse, status_code=201)
async def new_purchase(
    purchase_create: PurchaseCreate, 
    service: PurchaseService = Depends(get_purchases_services),
    product_service: ProductService = Depends(get_product_services)):

    try:
        # Crea y agrega una nueva compra en la base de datos, aumentando el stock inmediamente
        return service.new_purchase(**purchase_create.model_dump(), product_service=product_service)
    # Error en las ForeignKeys
    except IntegrityError:
        raise HTTPException(status_code=500, detail="Los id de usuario, producto o proveedor no existen")

@router.get("/", response_model=list[PurchaseJoinedResponse], status_code=200)
async def get_purchases(skip: int = 0, limit: int = 100, service: PurchaseService = Depends(get_purchases_services)):
    return service.get_purchases(skip=skip, limit=limit)

@router.get("/{purchase_id}", response_model=PurchaseJoinedResponse, status_code=200)
async def get_purchase_id(purchase_id: int, service: PurchaseService = Depends(get_purchases_services)):
    purchase = service.get_purchase_id(purchase_id)

    if not purchase:
        raise HTTPException(status_code=404, detail="No se encontro la compra")

    return purchase