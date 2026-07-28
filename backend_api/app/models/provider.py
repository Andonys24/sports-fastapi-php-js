from sqlalchemy import Column, Integer, String
from app.database.database import Base

class Provider(Base):
    # Nombre de la tabla en la base de datos
    __tablename__ = 'proveedores'

    # Columnas respectivas de la clase
    id = Column(Integer, primary_key=True, index=True)
    name = Column(String, index=True)
    address = Column(String, index=True)
    phone_number = Column(String, index=True)
    contract_period = Column(Integer, index=True)
    contract_type = Column(String, index=True)