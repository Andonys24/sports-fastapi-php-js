from sqlalchemy import Column, Integer, String
from app.database.database import Base

class User(Base):
    # Nombre de la tabla
    __tablename__ = 'usuarios'

    # Crea la columna que pertenece a la Primary Key de la tabla, siendo el id unico de cada objetivo
    # en la base de datos
    id = Column(Integer, primary_key=True, index=True) 
    username = Column(String, index=True)
    password = Column(String, index=True)
    full_name = Column(String, index=True)
    email = Column(String, index=True)
    admin = Column(Integer, default=0)