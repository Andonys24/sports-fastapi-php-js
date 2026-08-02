from fastapi import APIRouter, HTTPException, Depends
from models.order import OrderCreate, OrderResponse, OrderJoinedResponse
from services.order_service import OrderService
from db.database import db_dependency
from sqlalchemy.exc import IntegrityError

router = APIRouter(prefix="/orders")

def get_order_service(db_session: db_dependency) -> OrderService:
    return OrderService(db_session)

# Rutas
@router.post("/", response_model=OrderResponse, status_code=201)
async def create_order(order_create: OrderCreate, service: OrderService = Depends(get_order_service)):
    try:
        # Crea una nueva orden con sus respectivos datos
        return service.create_order(**order_create.model_dump())
    except IntegrityError:
        raise HTTPException(status_code=500, detail="El id del usuario proporcionado no existe")

@router.get("/", response_model=list[OrderJoinedResponse], status_code=200)
async def get_orders(skip: int = 0, limit: int = 100, service: OrderService = Depends(get_order_service)):
    return service.get_orders(skip=skip, limit=limit)

@router.get("/{order_id}", response_model=OrderJoinedResponse, status_code=200)
async def get_order_id(order_id: int, service: OrderService = Depends(get_order_service)):
    order = service.get_order_id(order_id)

    if not order:
        raise HTTPException(status_code=404, detail="No se encontro la orden")

    return order

@router.delete("/{order_id}", status_code=200)
async def delete_order(order_id: int, service: OrderService = Depends(get_order_service)):
    try:
        order_deleted = service.delete_order(order_id)

        if not order_deleted:
            raise HTTPException(status_code=404, detail="No se encontro la orden")

        return {"resultado": True, "mensaje": "Orden eliminada"}
    except Exception:
        raise HTTPException(status_code=500, detail="Hubo un error al escribir en la base de datos")