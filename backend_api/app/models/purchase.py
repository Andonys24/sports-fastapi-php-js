from pydantic import BaseModel
from datetime import date

class PurchaseBase(BaseModel):
    product_id: int
    supplier_id: int
    user_id: int
    quantity: int
    purchase_price: float
    date_purchase: date = date.today()

class PurchaseCreate(PurchaseBase):
    pass

class PurchaseResponse(PurchaseBase):
    id: int

    class Config:
        from_attributes = True

class PurchaseJoinedResponse(PurchaseResponse):
    product_name: str
    supplier_name: str
    username: str