from fastapi import Depends
from sqlalchemy import Row
from sqlalchemy.orm import Session
from db.schemas.purchase_schema import Purchase
from db.schemas.product_schema import Product
from db.schemas.supplier_schema import Supplier
from services.product_service import ProductService
from db.schemas.user_schema import User
from datetime import date

class PurchaseService:
    # Metodo constructor
    def __init__(self, db: Session) -> None:
        self._db = db

    # Metodos especificos
    def new_purchase(self, product_id: int, supplier_id: int, user_id: int,
            quantity: int, purchase_price: float, date_purchase: date,
            product_service: ProductService) -> Purchase | None:
        # Crea una nueva compra, pero sin pasarle una fecha
        # De forma que utilice la fecha por defecto, que es la fecha en que se creo
        purchase = Purchase(
            product_id=product_id,
            supplier_id=supplier_id,
            user_id=user_id,
            quantity=quantity,
            purchase_price=purchase_price,
            date_purchase=date_purchase
        )

        product_tmp = product_service.get_product_id(purchase.product_id)

        # Validacion por si el producto no es encontrado, o no existe
        # Debe lanzarse una excepcion si el id del producto no existe en la base de datos, debido a la ForeignKey
        if not product_tmp:
            return None

        self._db.add(purchase)
        self._db.commit()
        self._db.refresh(purchase)

        # Calcula el nuevo stock, y lo envia a la base de datos
        new_stock = product_tmp.stock + purchase.quantity
        product_service.update_product_stock(product_id=product_tmp.id, new_stock=new_stock)

        return purchase

    def get_purchases(self, skip: int, limit: int) -> list[Row]:
        return self._db.query(
                *Purchase.__table__.columns, 
                Product.name.label("product_name"), 
                Supplier.name.label("supplier_name"), 
                User.username.label("username")
            ).join(User, Purchase.user_id == User.id
            ).join(Product, Purchase.product_id == Product.id
            ).join(Supplier, Purchase.supplier_id == Supplier.id
            ).offset(skip).limit(limit).all()

    def get_purchase_id(self, purchase_id: int) -> Row | None:
        purchase = self._db.query(
                *Purchase.__table__.columns, 
                Product.name.label("product_name"), 
                Supplier.name.label("supplier_name"), 
                User.username.label("username")
            ).join(User, Purchase.user_id == User.id
            ).join(Product, Purchase.product_id == Product.id
            ).join(Supplier, Purchase.supplier_id == Supplier.id
            ).filter(Purchase.id == purchase_id).first()

        if not purchase:
            return None

        return purchase

    def delete_purchase(self, purchase_id: int) -> bool:
        purchase = self._db.query(Purchase).filter(Purchase.id == purchase_id).first()

        if not purchase:
            return False

        self._db.delete(purchase)
        self._db.commit()

        return True