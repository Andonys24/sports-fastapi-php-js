from fastapi import APIRouter, HTTPException, Depends
from models.order import OrderCreate, OrderResponse, OrderJoinedResponse, OrderDaily
from services.order_service import OrderService
from db.database import db_dependency
from sqlalchemy.exc import IntegrityError
from datetime import date

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

@router.get("/daily/{date_order}", response_model=list[OrderDaily], status_code=200)
async def get_daily_order(actual_day: date = date.today(), service: OrderService = Depends(get_order_service)):
    # Agrega la fecha obtenida como parametro, con el formato YYYY-MM-DD
    orders = service.get_orders_date(actual_day)

    if not orders:
        raise HTTPException(status_code=404, detail="No hay compras este dia")

    return orders