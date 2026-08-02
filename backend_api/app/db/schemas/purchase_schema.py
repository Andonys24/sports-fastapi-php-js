from sqlalchemy import ForeignKey, Date
from sqlalchemy.orm import Mapped, mapped_column, relationship
from db.database import Base
from datetime import date

# Es un tipo de compra general para los usuarios
class Purchase(Base):
    # Nombre de la tabla
    __tablename__ = "compras"

    # Atributos de la clase respectivos para cada columna
    id: Mapped[int] = mapped_column(primary_key=True)

    # ForeignKeys que permiten relacionar la compra con un respectivo producto, proveedor y usuario
    product_id: Mapped[int] = mapped_column(ForeignKey("productos.id"))
    supplier_id: Mapped[int] = mapped_column(ForeignKey("proveedores.id"))
    user_id: Mapped[int] = mapped_column(ForeignKey("usuarios.id"))

    quantity: Mapped[int] = mapped_column()
    purchase_price: Mapped[float] = mapped_column()
    date_purchase: Mapped[date] = mapped_column(Date, default=date.today()) 

    product = relationship("Product", foreign_keys=[product_id])
    supplier = relationship("Supplier", foreign_keys=[supplier_id])
    user = relationship("User", foreign_keys=[user_id])