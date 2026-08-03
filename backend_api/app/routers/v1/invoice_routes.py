from fastapi import APIRouter, HTTPException, Depends
from models.invoice import InvoiceCreate, InvoiceResponse, InvoiceJoinedResponse
from services.invoice_service import InvoiceService
from services.product_service import ProductService
from db.database import db_dependency
from sqlalchemy.exc import IntegrityError

router = APIRouter(prefix="/invoices")

def get_invoice_service(db_session: db_dependency) -> InvoiceService:
    return InvoiceService(db_session)

def get_product_service(db_session: db_dependency) -> ProductService:
    return ProductService(db_session)

# Rutas
@router.post("/", response_model=InvoiceResponse, status_code=201)
async def create_invoice(invoice_create: InvoiceCreate, service: InvoiceService = Depends(get_invoice_service),
        product_service: ProductService = Depends(get_product_service)):
    try:
        # Crea una nueva factura
        invoice = service.create_invoice(**invoice_create.model_dump(), product_service=product_service)

        # Si se retorno None, es mas probable que sea por la segunda validacion
        if not invoice:
            raise HTTPException(status_code=500, detail="El stock es insuficiente para realizar la compra")

        return invoice
    except IntegrityError:
        raise HTTPException(status_code=500, detail="El id del producto o el id del encargo no es valido")

@router.get("/", response_model=list[InvoiceJoinedResponse], status_code=200)
async def get_invoices(skip: int = 0, limit: int = 100, service: InvoiceService = Depends(get_invoice_service)):
    return service.get_invoices(skip=skip, limit=limit)

@router.get("/{invoice_id}", response_model=InvoiceJoinedResponse, status_code=200)
async def get_invoice_id(invoice_id: int, service: InvoiceService = Depends(get_invoice_service)):
    invoice = service.get_invoice_id(invoice_id)

    if not invoice:
        raise HTTPException(status_code=404, detail="No se encontro la factura")

    return invoice