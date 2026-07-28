from sqlalchemy import Column, Integer, String
from app.database.database import Base

class Category(Base):
    # Nombre de la tabla
    __tablename__ = 'categorias'

    # Columnas respectivas a la clase
    id = Column(Integer, primary_key=True, index=True)
    nombre = Column(String, index=True)
    descripcion = Column(String, index=True)