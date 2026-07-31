from sqlalchemy import String, ForeignKey
from sqlalchemy.orm import Mapped, mapped_column
from db.database import Base

class Product(Base):
    # Nombre de la tabla
    __tablename__ = 'productos'

    # Columnas respectivas a la tabla de esta clase
    id: Mapped[int] = mapped_column(primary_key=True)
    category_id: Mapped[int] = mapped_column(ForeignKey("categorias.id"))
    provider_id: Mapped[int] = mapped_column(ForeignKey("proveedores.id"))
    name: Mapped[str] = mapped_column(String, index=True)
    price: Mapped[float] = mapped_column()
    brand: Mapped[str] = mapped_column(String, index=True)
    stock: Mapped[int] = mapped_column(default=0)  
    img_url: Mapped[str] = mapped_column(String, index=True)