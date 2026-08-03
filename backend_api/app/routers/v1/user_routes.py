from fastapi import APIRouter, HTTPException, Depends, Path, Body
from models.user import UserCreate, UserUpdate, UserResponse
from services.user_service import UserService
from db.database import db_dependency
from sqlalchemy.exc import IntegrityError

# Se crea un nuevo router
router = APIRouter(prefix="/users")

# Funcion "fabrica" para obtener el servicio
# db_dependency es un tipo de Session
def get_user_service(db_session: db_dependency) -> UserService:
    return UserService(db_session) # Retorna UserService ya con una Session

# Rutas
# Metodo POST para la creacion de un nuevo usuario
# Es decir, es tambien para el registro de usuarios
@router.post("/", response_model=UserResponse, status_code=201)
async def create_user(user_create: UserCreate, service: UserService = Depends(get_user_service)): # user es la clase del modelo Pydantic
    previous_user = service.get_user_name(user_create.username)

    # Valida si el usuario ya existe previamente buscando por medio del nombre de usuario
    if previous_user:
        raise HTTPException(status_code=400, detail="El nombre de usuario ya existe")

    # En caso contrario, procede a crear un nuevo usuario
    # Retorna inmediatamente el usuario creado
    return service.create_user(**user_create.model_dump()) 

# Metodo GET para obtener una cierta cantidad de usuarios
@router.get("/", response_model=list[UserResponse], status_code=200)
async def get_users(skip: int = 0, limit: int = 100, service: UserService = Depends(get_user_service)):
    # Retorna la lista de usuarios obtenidos
    return service.get_users(skip, limit)

# Metodo GET para obtener un usuario en base a su id
@router.get("/{id}", response_model=UserResponse, status_code=200)
async def get_user_id(user_id: int = Path(..., alias="id"), service: UserService = Depends(get_user_service)):
    user_finded = service.get_user_id(user_id)
    
    # Validacion en el caso que user_finded sea None, es decir, no se haya encontrado ninguna coincidencia
    if (not user_finded):
        raise HTTPException(status_code=404, detail='Usuario no encontrado')
    
    # En caso contrario, se retorna el usuario obtenido
    return user_finded

# Metodo PUT para actualizar la configuracion de un usuario creado previamente
@router.put("/{id}", response_model=UserResponse, status_code=200)
async def update_user(id_user: int = Path(..., alias="id"), 
        user_update: UserUpdate = Body(...), service: UserService = Depends(get_user_service)):
    try:
        # Validacion en el caso que no se envie una contrasena
        if not user_update.password:
            # Envia None como contrasena, 
            user_tmp = service.update_user(user_id=id_user, password=None, **user_update.model_dump(exclude={"password"}))
        else:
            user_tmp = service.update_user(user_id=id_user, **user_update.model_dump())

        # Validacion por si no se encontro el usuario
        if (not user_tmp):
            raise HTTPException(status_code=404, detail="Usuario no encontrado")

        return user_tmp
    except Exception:
        raise HTTPException(status_code=500, detail="Hubo un error al escribir en la base de datos al actualizar")

# Metodo DELETE, simplemente para eliminar un usuario de la base de datos en base a su id
@router.delete("/{id_user}", status_code=200)
async def delete_user(id_user: int, service: UserService = Depends(get_user_service)):
    try:
        confirmation = service.delete_user(id_user)

        if (not confirmation):
            raise HTTPException(status_code=404, detail="Usuario no encontrado")

        return {"resultado": True, "mensaje": "Usuario eliminado exitosamente"}
    except IntegrityError:
        raise HTTPException(status_code=500, detail="No se puede borrar el registro, debido a que esta relacionado a otras tablas")