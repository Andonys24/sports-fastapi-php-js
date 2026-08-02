from fastapi import Depends
from sqlalchemy import Row
from sqlalchemy.orm import Session
from db.schemas.invoice_schema import Invoice
from db.schemas.order_schema import Order
from db.schemas.product_schema import Product
from services.product_service import ProductService

class InvoiceService:
    # Metodo constructor
    def __init__(self, db: Session) -> None:
        self._db = db

    # Metodo especificos
    def create_invoice(self, order_id: int, product_id: int, quantity: int, price: float,
            product_service: ProductService) -> Invoice | None:
        invoice = Invoice(
            product_id=product_id,
            order_id=order_id,
            quantity=quantity,
            price=price
        )

        product_tmp = product_service.get_product_id(invoice.product_id)

        # Validacion
        # Obtiene el producto despues de crear la factura. Si el producto no existe, quiere decir que 
        # el id de producto no es correcto, lo cual daria una excepcion por ser ForeignKey, por lo que 
        # es poco probable que se retorne None en esta parte
        if not product_tmp:
            return None

        # Comprueba si el stock de dicho producto es insuficiente para la cantidad que se quiere comprar
        # antes de agregarlo en la base de datos
        if product_tmp.stock < invoice.quantity:
            return None

        # En caso contrario, procede a realizar los respectivos cambios en la base de
        self._db.add(invoice)
        self._db.commit()
        self._db.refresh(invoice)

        # En caso que se haya agregado correctamente la factura, se procede a disminuir el stock del producto
        new_stock = product_tmp.stock - invoice.quantity

        # Aplica el cambio de stock al producto
        product_service.update_product_stock(product=product_tmp, new_stock=new_stock)

        return invoice

    def get_invoices(self, skip: int, limit: int) -> list[Row]:
        return self._db.query(
            *Invoice.__table__.columns,
            Product.name.label("product_name"),
            Order.total.label("total")
        ).join(Product, Invoice.product_id == Product.id
        ).join(Order, Invoice.order_id == Order.id
        ).offset(skip).limit(limit).all()

    def get_invoice_id(self, invoice_id: int) -> Row | None:
        invoice = self._db.query(
            *Invoice.__table__.columns,
            Product.name.label("product_name"),
            Order.total.label("total")
        ).join(Product, Invoice.product_id == Product.id
        ).join(Order, Invoice.order_id == Order.id
        ).filter(Invoice.id == invoice_id).first()

        if not invoice:
            return None

        return invoice

    def delete_invoice(self, invoice_id: int) -> bool:
        invoice = self._db.query(Invoice).filter(Invoice.id == invoice_id).first()

        if not invoice:
            return False

        self._db.delete(invoice)
        self._db.commit()

        return invoice