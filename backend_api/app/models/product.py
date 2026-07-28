from sqlalchemy import Column, Integer, String, REAL, ForeignKey
from app.database.database import Base

class Product(Base):
    # Nombre de la tabla
    __tablename__ = 'productos'

    # Columnas respectivas a la tabla de esta clase
    id = Column(Integer, primary_key=True, index=True)
    id_category = Column(Integer, ForeignKey("categorias.id"))
    id_provider = Column(Integer, ForeignKey("proveedores.id"))
    description = Column(String, index=True)
    price = Column(REAL, index=True)
    brand = Column(String, index=True)
    stock = Column(Integer, default=0)  