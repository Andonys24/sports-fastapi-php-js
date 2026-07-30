from sqlalchemy import Integer, String
from sqlalchemy.orm import Mapped, mapped_column
from db.database import Base

class Supplier(Base):
    # Nombre de la tabla en la base de datos
    __tablename__ = 'proveedores'

    # Columnas respectivas de la clase
    id: Mapped[int] = mapped_column(primary_key=True)
    name: Mapped[str] = mapped_column(String, index=True)
    address: Mapped[str] = mapped_column(String, index=True)
    phone_number: Mapped[str] = mapped_column(String, index=True)
    contract_period: Mapped[int] = mapped_column(Integer, index=True)
    contract_type: Mapped[str] = mapped_column(String, index=True)