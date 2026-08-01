from pydantic import BaseModel
from typing import Optional

class UserBase(BaseModel):
    username: str
    full_name: str
    email: str
    admin: int = 0 # Por defecto no son administradores

class UserUpdate(BaseModel):
    username: Optional[str] = None
    password: Optional[str] = None
    full_name: Optional[str] = None
    email: Optional[str] = None
    admin: Optional[int] = None

class UserToken(BaseModel):
    access_token: str
    token_type: str = "bearer"

class UserTokenData(BaseModel):
    username: str

class UserCreate(UserBase):
    password: str
    pass

class UserResponse(UserBase):
    id: int

    class Config:
        from_attributes = True

