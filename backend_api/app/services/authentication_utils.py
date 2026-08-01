from pwdlib import PasswordHash
from pwdlib.hashers.bcrypt import BcryptHasher
from jose import jwt
from datetime import datetime, timedelta, timezone

from dotenv import load_dotenv
import os

# Se cargan las variables de entorno
load_dotenv()

# Se definen las variables necesarias para la creacion de los tokens de autenticacion
SECRET_KEY = os.getenv('SECRET_KEY')
ALGORITHM = os.getenv('ALGORITHM')
TOKEN_EXPIRE_MINUTES = 30

pwd_context = PasswordHash((BcryptHasher(), ))

# Funciones de utilidad para crear y validar los tokens
# Comprueba si una contrasena en texto plano coincide con un valor hash en especifico
def verify_password(normal_passwd: str, hashed_passwd: str) -> bool:
    return pwd_context.verify(normal_passwd, hashed_passwd)

# Recibe una contrasena en texto plano, y la cifra en formato hash
def hash_password(normal_passwd: str) -> str:
    return pwd_context.hash(password=normal_passwd)

# Crea un nuevo token de acceso JWT
def create_access_token(data: dict) -> str:
    to_encode = data.copy()

    # Tiempo de expiracion
    # Calcula los proximos 30 minutos a partir de la fecha actual de la computadora
    expire_value = datetime.now(timezone.utc) + timedelta(minutes=TOKEN_EXPIRE_MINUTES)
    to_encode.update({"exp": expire_value})

    if (not SECRET_KEY or not ALGORITHM):
        raise ValueError("Hubo un error al crear un nuevo token")

    # Retorna el nuevo token creado por JWT
    return jwt.encode(
        claims=to_encode, 
        key=SECRET_KEY, 
        algorithm=ALGORITHM)