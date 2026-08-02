from pydantic import BaseModel
from typing import Optional

class InventoryBase(BaseModel):
    product_id: int

class InventoryUpdate(BaseModel):
    stock: int

class InventoryJoinedReponse(InventoryBase):
    product_name: str
    category_name: str
    category_description: str
    stock: int
    price: float

    class Config:
        from_attributes = True