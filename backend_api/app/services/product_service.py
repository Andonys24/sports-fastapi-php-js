from sqlalchemy.orm import Session
from db.schemas.product_schema import Product

class ProductService:
    def __init__(self, db: Session) -> None:
        self._db = db

    # Definicion del metodo POST
    def create_product(self, category_id: int, supplier_id: int, name: str,
                price: float, brand: str, stock: int,
                img_url: str) -> Product:
        
        product = Product(
            category_id=category_id,
            supplier_id=supplier_id,
            name=name,
            price=price,
            brand=brand,
            stock=stock,
            img_url=img_url
        )

        self._db.add(product)
        self._db.commit()
        self._db.refresh(product)

        return product

    # Definicion del metodo GET
    def get_products(self, skip: int, limit: int) -> list[Product]:
        return self._db.query(Product).offset(skip).limit(limit).all()

    # Definicion del metodo GET para obtener solamente un usuario
    def get_product_id(self, product_id: int) -> Product | None:
        return self._db.query(Product).filter(Product.id == product_id).first()
    
    # Definicion del metodo PUT
    def update_product(self, product_id: int, category_id: int, supplier_id: int,
                    name: str, price: float, brand: str,
                    stock: int, img_url) -> Product | None:
        product = self.get_product_id(product_id)

        if not product:
            return None

        product.category_id = category_id
        product.supplier_id = supplier_id
        product.name = name
        product.price = price
        product.brand = brand
        product.stock = stock
        product.img_url = img_url

        self._db.commit()
        self._db.refresh(product)

        return product

    # Definicion del metodo DELETE
    def delete_product(self, product_id: int) -> bool:
        # Obtencion del usuario por medio del metodo get_product_id() definido previamente
        product = self.get_product_id(product_id)

        # En caso de que se haya retornado None, esta funcion retorna False
        # en forma de decir que no se pudo eliminar el usuario, debido a que no fue encontrado
        if not product:
            return False

        # En caso contrario, realiza la eliminacion normalmente
        self._db.delete(product)
        self._db.commit()

        return True

    def update_product_stock(self, product_id: int, new_stock: int) -> None:
        product = self.get_product_id(product_id)

        # Validacion por si no se encontro el producto
        if not product:
            return None

        product.stock = new_stock

        self._db.commit()
        self._db.refresh(product)