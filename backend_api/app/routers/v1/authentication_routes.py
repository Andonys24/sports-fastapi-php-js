from fastapi import HTTPException, APIRouter, Depends
from fastapi.security import OAuth2PasswordBearer, OAuth2PasswordRequestForm
from jose import JWTError, jwt
from services.user_service import UserService
from models.user import UserResponse, UserBase
from db.database import db_dependency
from models.user import UserToken, UserTokenData
from services.authentication_utils import create_access_token, SECRET_KEY, ALGORITHM

router = APIRouter(prefix="/auth")

oauth2_scheme = OAuth2PasswordBearer(tokenUrl="/api/v1/auth/login")

# Funcion "fabrica" para obtener el servicio del usuario
def get_user_service(db_session: db_dependency) -> UserService:
    return UserService(db_session)

async def get_current_user(token: str = Depends(oauth2_scheme), service: UserService = Depends(get_user_service)):
    try:
        if (not SECRET_KEY or not ALGORITHM):
            raise HTTPException(
                status_code=500, 
                detail="Hubo un error en el token",
                headers={"WWW-Authenticate": "Bearer"})

        payload = jwt.decode(token=token, key=SECRET_KEY, algorithms=[ALGORITHM])
        username = payload.get("sub")

        if not username:
            raise HTTPException(
                status_code=401, 
                detail="No se pudieron validar las credenciales",
                headers={"WWW-Authenticate": "Bearer"})

        token_data = UserTokenData(username=username)

        user = service.get_user_name(token_data.username) 

        if not user:
            raise HTTPException(
                status_code=401, 
                detail="No se pudieron validar las credenciales",
                headers={"WWW-Authenticate": "Bearer"})

        return user # Se retorna el usuario actual, despues de una serie de validaciones
    except JWTError:
        raise HTTPException(
                status_code=401, 
                detail="No se pudieron validar las credenciales",
                headers={"WWW-Authenticate": "Bearer"})

@router.post("/login", response_model=UserToken)
async def login(form_data: OAuth2PasswordRequestForm = Depends(), service: UserService = Depends(get_user_service)):
    user = service.authenticate_user(form_data.username, form_data.password)

    if not user:
        raise HTTPException(
                status_code=401, 
                detail="Nombre de usuario o contrasena incorrectos",
                headers={"WWW-Authenticate": "Bearer"})

    # Se crea un nuevo token de acceso para el usuario autenticado previamente
    access_token = create_access_token(data={"sub": user.username})

    # Se retorna un tipo de "UserToken", cumpliendo con los atributos descritos en este
    return {"access_token": access_token, "token_type": "bearer"} 

# Ruta protegida a la que solamente puede acceder el usuario que inicio sesion
# Cambiar si se necesita en otra ruta
@router.get("/me", response_model=UserResponse)
async def read_actual_user(current_user: UserBase = Depends(get_current_user)):
    return current_user