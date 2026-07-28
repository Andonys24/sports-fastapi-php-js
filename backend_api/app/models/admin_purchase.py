from sqlalchemy import Column, Integer, String, REAL, TIMESTAMP, ForeignKey
from app.database.database import Base

class AdminPurchase(Base):
    # Nombre de la tabla en la base de datos
    __tablename__ = 'compras_admin'

    # Columnas respectivas a la clase
    id = Column(Integer, primary_key=True, index=True)
    id_provider = Column(Integer, ForeignKey("proveedores.id"))
    id_product = Column(Integer, ForeignKey("productos.id"))
    amount = Column(Integer, index=True)
    price_unit = Column(REAL, index=True)
    subtotal = Column(REAL, index=True)
    datetime = Column(TIMESTAMP, index=True)