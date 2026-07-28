from sqlalchemy import Column, Integer, String, REAL, TIMESTAMP, ForeignKey
from app.database.database import Base

class ClientBill(Base):
    # Nombre de la tabla
    __tablename__ = 'facturas_clientes'

    # Columnas respectivas a la clase
    id = Column(Integer, primary_key=True, index=True)
    id_user = Column(Integer, ForeignKey("usuarios.id"))
    datetime = Column(TIMESTAMP, index=True)
    address = Column(String, index=True)
    delivery = Column(Integer, default=0)