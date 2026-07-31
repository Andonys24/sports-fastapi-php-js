from sqlalchemy import Integer, String
from sqlalchemy.orm import Mapped, mapped_column
from db.database import Base

class User(Base):
    # Nombre de la tabla
    __tablename__ = 'usuarios'

    # Crea la columna que pertenece a la Primary Key de la tabla, siendo el id unico de cada objetivo
    # en la base de datos
    id: Mapped[int] = mapped_column(Integer, primary_key=True)
    username: Mapped[str] = mapped_column(String, index=True)
    password: Mapped[str] = mapped_column(String, index=True)
    full_name: Mapped[str] = mapped_column(String, index=True)
    email: Mapped[str] = mapped_column(String, index=True)
    admin: Mapped[int] = mapped_column(Integer, default=0)