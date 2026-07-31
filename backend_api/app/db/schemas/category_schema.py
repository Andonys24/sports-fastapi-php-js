from sqlalchemy import Integer, String
from sqlalchemy.orm import Mapped, mapped_column
from db.database import Base

class Category(Base):
    # Nombre de la tabla
    __tablename__ = 'categorias'

    # Columnas respectivas a la clase
    id: Mapped[int] = mapped_column(Integer, primary_key=True)
    name: Mapped[str] = mapped_column(String, index=True)
    description: Mapped[str] = mapped_column(String, index=True)