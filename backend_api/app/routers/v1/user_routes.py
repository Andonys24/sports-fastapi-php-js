from fastapi import APIRouter, HTTPException, Depends
from models.user import UserCreate, UserUpdate, UserResponse
from services.user_service import UserService
from db.database import db_dependency

# Se crea un nuevo router
router = APIRouter()

# Funcion "fabrica" para obtener el servicio
# db_dependency es un tipo de Session
def get_user_service(db_session: db_dependency) -> UserService:
    return UserService(db_session) # Retorna UserService ya con una Session

# Rutas
# Metodo POST para la creacion de un nuevo usuario
@router.post("/users", response_model=UserResponse, status_code=201)
async def create_user(user_create: UserCreate, service: UserService = Depends(get_user_service)): # user es la clase del modelo Pydantic
    return service.create_user(**user_create.model_dump()) # Retorna inmediatamente el usuario creado

# Metodo GET para obtener una cierta cantidad de usuarios
@router.get("/users", response_model=list[UserResponse], status_code=200)
async def get_users(skip: int = 0, limit: int = 100, service: UserService = Depends(get_user_service)):
    return service.get_users(skip, limit) # Retorna la lista de usuarios obtenidos

# Metodo GET para obtener un usuario en base a su id
@router.get("/users/{id}", response_model=UserResponse, status_code=200)
async def get_user_id(user_id: int, service: UserService = Depends(get_user_service)):
    user_finded = service.get_user_id(user_id)
    
    # Validacion en el caso que user_finded sea None, es decir, no se haya encontrado ninguna coincidencia
    if (not user_finded):
        raise HTTPException(status_code=404, detail='Usuario no encontrado')
    
    # En caso contrario, se retorna el usuario obtenido
    return user_finded

# Metodo PUT para actualizar la configuracion de un usuario creado previamente
@router.put("/users/{id}", response_model=UserResponse, status_code=200)
async def update_user(id_user: int, user_update: UserUpdate, service: UserService = Depends(get_user_service)):
    try:
        user_tmp = service.update_user(id_user, **user_update.model_dump())

        if (not user_tmp):
            raise HTTPException(status_code=404, detail="Usuario no encontrado")

        return user_tmp
    except Exception as e:
        raise HTTPException(status_code=304, detail="Hubo un error al escribir en la base de datos al actualizar")

# Metodo DELETE, simplemente para eliminar un usuario de la base de datos en base a su id
@router.delete("/users/{id}", status_code=204)
async def delete_user(id_user: int, service: UserService = Depends(get_user_service)):
    try:
        confirmation = service.delete_user(id_user)

        if (not confirmation):
            raise HTTPException(status_code=404, detail="Usuario no encontrado")

        return {"success": True}
    except Exception as e:
        raise HTTPException(status_code=304, detail="Hubo un error al escribir en la base de datos al eliminar")