# Esta clase tiene un modelo pydantic, y tendra la funcion de permitir retornar 
# una respuesta, tanto de confirmacion o en forma de error
from pydantic import BaseModel
from typing import Optional, TypeVar

class Response(BaseModel):
    code: str
    status: str
    message: str    
    result: Optional[BaseModel]