from pydantic import BaseModel
from typing import Optional

class SupplierBase(BaseModel):
    name: str
    address: str
    phone_number: str
    contract_period: int
    contract_type: str

class SupplierUpdate(BaseModel):
    name: Optional[str] = None
    address: Optional[str] = None
    phone_number: Optional[str] = None
    contract_period: Optional[int] = None
    contract_type: Optional[str] = None

class SupplierCreate(SupplierBase):
    pass

class SupplierResponse(SupplierBase):
    id: int

    class Config:
        from_attributes = True