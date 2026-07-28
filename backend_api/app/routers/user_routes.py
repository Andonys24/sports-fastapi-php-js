from fastapi import APIRouter, HTTPException
from app.database.database import db_dependency
from app.services.response import Response
from app.services.user_schema import UserCreate, UserUpdate, UserResponse
from app.models.user import User

# Se crea un nuevo router
router = APIRouter(prefix="/usuarios", tags=["Usuarios"])

# Rutas
@router.post("/", response_model=UserResponse)
async def create_user(db: db_dependency, user_c: UserCreate):
    # Crea un nuevo objeto de tipo Usuario, en base a los atributos que obtiene
    # del modelo Pydantic
    db_user = User(
        username=user_c.username,
        password=user_c.password,
        full_name=user_c.full_name,
        email=user_c.email,
        admin=user_c.admin 
    )

    db.add(db_user)
    db.commit()
    db.refresh(db_user)
    
    return Response(
        status="Ok", 
        code="200", 
        message="El usuario fue creado correctamente",
        result=user_c).dict(exclude_none=True)

#@router.get()

#@router.get()

#@router.put()

#@router.delete()