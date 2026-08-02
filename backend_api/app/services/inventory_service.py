from sqlalchemy.orm import Session
from sqlalchemy import Row
from db.schemas.product_schema import Product
from db.schemas.category_schema import Category

class InventoryService:
    # Metodo constructor
    def __init__(self, db: Session) -> None:
        self._db = db

    # Metodo especificos
    def get_inventory(self, skip: int, limit: int) -> list[Row]:
        return self._db.query(
            Product.id.label("product_id"),
            Product.name.label("product_name"),
            Category.name.label("category_name"),
            Category.description.label("category_description"),
            Product.stock.label("stock"),
            Product.price.label("price")
        ).join(Product, Category.id == Product.category_id
        ).offset(skip).limit(limit).all()