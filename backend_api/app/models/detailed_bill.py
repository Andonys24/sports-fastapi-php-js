from sqlalchemy import Column, Integer, String, REAL, ForeignKey
from app.database.database import Base

class DetailedBill(Base):
    # Nombre de la tabla
    __tablename__ = 'detalles_facturas'

    # Columnas respectivas a la clase
    id = Column(Integer, primary_key=True, index=True)
    id_bill = Column(Integer, ForeignKey("facturas_clientes.id"))
    id_product = Column(Integer, ForeignKey("productos.id"))
    amount = Column(Integer, index=True)
    price_unit = Column(REAL, index=True)
    isv = Column(REAL, default=0.15)
    subtotal = Column(REAL, index=True)