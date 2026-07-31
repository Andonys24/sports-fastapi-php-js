from pydantic import BaseModel
from typing import Optional

class ProductBase(BaseModel):
    category_id: int
    provider_id: int
    name: str
    price: float
    brand: str
    stock: int = 0
    img_url: str

class ProductUpdate(BaseModel):
    category_id: Optional[int] = None
    provider_id: Optional[int] = None
    name: Optional[str] = None
    price: Optional[float] = None
    brand: Optional[str] = None
    stock: Optional[int] = None
    img_url: Optional[str] = None

class ProductCreate(ProductBase):
    pass

class ProductResponse(ProductBase):
    id: int

    class Config:
        from_attributes = True