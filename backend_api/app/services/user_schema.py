from pydantic import BaseModel
from typing import Optional

class UserBase(BaseModel):
    username: int
    password: str
    full_name: str
    email: str
    admin: int = 0 # Por defecto no son administradores

    # Se crea una clase anidada para especificas que se usara un ORM
    class Config:
        orm_mode = True

class UserUpdate(BaseModel):
    username: Optional[int] = None
    password: Optional[str] = None
    full_name: Optional[str] = None
    email: Optional[str] = None
    admin: Optional[str] = None

    class Config:
        orm_mode = True

class UserCreate(UserBase):
    pass

class UserResponse(UserBase):
    id: int