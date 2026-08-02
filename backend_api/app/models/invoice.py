from pydantic import BaseModel

class InvoiceBase(BaseModel):
    product_id: int
    order_id: int
    quantity: int
    price: float

class InvoiceCreate(InvoiceBase):
    pass

class InvoiceResponse(InvoiceBase):
    id: int

    class Config:
        from_attributes = True

class InvoiceJoinedResponse(InvoiceResponse):
    product_name: str
    total: float