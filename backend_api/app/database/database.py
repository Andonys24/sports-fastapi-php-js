from fastapi import Depends
from sqlalchemy import create_engine
from sqlalchemy.orm import sessionmaker, Session
from sqlalchemy.ext.declarative import declarative_base
from typing import Annotated

from dotenv import load_dotenv
import os

# Carga las variables de entorno desde un archivo como si estuvieran en el sistema
load_dotenv()

# Obtiene la variable de entorno especificamente para la URL de la conexion con el servidor PostgreSQL
DATABASE_URL = os.getenv('DATABASE_URL')

# Validacion por negacion para evitar que DATABASE_URL sea pasada como None
if not DATABASE_URL:
    raise ValueError("No se encontro la URL para la conexion con la base de datos")

engine_db = create_engine(DATABASE_URL) # Crea el motor de la base de datos en base a la sesion obtenida por la URL

SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine_db) 
Base = declarative_base()

# Permite obtener la sesion de la base de datos
def get_db():
    database = SessionLocal() # Obtiene la sesion local actual
    try:
        yield database
    # Cierra la sesion de la base de datos, independientemente de que haya 
    # sucedido una excepcion o no
    finally:
        database.close() 

# Se especifica que la sesion va a depender de la funcion get_db()
db_dependency = Annotated[Session, Depends(get_db)]