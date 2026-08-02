from pydantic import BaseModel
from datetime import datetime, date, time

class OrderBase(BaseModel):
    user_id: int
    address: str
    time_order: time = datetime.now().time()
    date_order: date = date.today()
    total: float

class OrderCreate(OrderBase):
    pass

class OrderResponse(OrderBase):
    id: int

    class Config:
        from_attributes = True

class OrderJoinedResponse(OrderResponse):
    client_name: str
    client_email: str