from sqlalchemy import Column, Integer, String, REAL, TIMESTAMP, ForeignKey
from db.database import Base

class Purchase(Base):
    # Nombre de la tabla en la base de datos
    __tablename__ = 'compras_admin'

    # Columnas respectivas a la clase
    id = Column(Integer, primary_key=True, index=True)
    provider_id = Column(Integer, ForeignKey("proveedores.id"))
    product_id = Column(Integer, ForeignKey("productos.id"))
    quantity = Column(Integer, index=True)
    price = Column(REAL, index=True)
    subtotal = Column(REAL, index=True)
    datetime = Column(TIMESTAMP, index=True)