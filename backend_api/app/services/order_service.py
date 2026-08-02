from sqlalchemy import Row
from sqlalchemy.orm import Session
from db.schemas.order_schema import Order
from db.schemas.user_schema import User
from datetime import date, time

class OrderService:
    # Metodo constructor
    def __init__(self, db: Session) -> None:
        self._db = db

    # Metodo especificos
    def create_order(self, user_id: int, address: str, time_order: time,
            date_order: date, total: float) -> Order:
        order = Order(
            user_id=user_id,
            address=address,
            time_order=time_order,
            date_order=date_order,
            total=total
        )

        self._db.add(order)
        self._db.commit()
        self._db.refresh(order)

        return order

    def get_orders(self, skip: int, limit: int) -> list[Row]:
        return self._db.query(
                *Order.__table__.columns,
                User.username.label("client_name"),
                User.email.label("client_email")
            ).join(User, Order.user_id == User.id
            ).offset(skip).limit(limit).all()

    def get_order_id(self, order_id: int) -> Row | None:
        order = self._db.query(
                *Order.__table__.columns,
                User.username.label("client_name"),
                User.email.label("client_email")
            ).join(User, Order.user_id == User.id
            ).filter(Order.id == order_id).first()

        if not order:
            return None

        return order

    def delete_order(self, order_id: int) -> bool:
        order = self._db.query(Order).filter(Order.id == order_id).first()

        if not order:
            return False

        self._db.delete(order)
        self._db.commit()

        return True