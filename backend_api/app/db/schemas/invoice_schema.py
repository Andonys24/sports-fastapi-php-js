from sqlalchemy import ForeignKey
from sqlalchemy.orm import Mapped, mapped_column, relationship
from db.database import Base

class Invoice(Base):
    # Nombre de la tabla
    __tablename__ = "detalle_facturas"

    # Columnas respectivas a la tabla
    id: Mapped[int] = mapped_column(primary_key=True)

    # ForeignKeys
    product_id: Mapped[int] = mapped_column(ForeignKey("productos.id"))
    order_id: Mapped[int] = mapped_column(ForeignKey("encargos.id"))

    quantity: Mapped[int] = mapped_column()
    price: Mapped[float] = mapped_column()

    product = relationship("Product", foreign_keys=[product_id])
    order = relationship("Order", foreign_keys=[order_id])