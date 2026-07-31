from sqlalchemy.orm import Session
from db.schemas.category_schema import Category

class CategoryService:
    # Metodo constructor para el servicio
    def __init__(self, db: Session) -> None:
        self._db = db

    # Metodos especificos para el CRUD
    # Metodo para POST
    def create_category(self, name: str, description: str) -> Category:
        category = Category(
            name=name,
            description=description
        )

        self._db.add(category)
        self._db.commit()
        self._db.refresh(category)

        return category

    # Metodo para GET de todas las categorias
    def get_categories(self, skip: int, limit: int) -> list[Category]:
        return self._db.query(Category).offset(skip).limit(limit).all()

    # Metodo para GET de un unico usuario
    def get_category_id(self, category_id: int) -> Category | None:
        return self._db.query(Category).filter(Category.id == category_id).first()

    # Metodo para PUT de un unico usuario
    def update_category(self, category_id: int, name: str, description: str) -> Category | None:
        category = self.get_category_id(category_id)

        if not category:
            return None

        category.name = name
        category.description = description

        self._db.commit()
        self._db.refresh(category)

        return category


    # Metodo para DELETE de un unico usuario por su id
    def delete_category(self, category_id: int) -> bool:
        category = self.get_category_id(category_id)

        # En el caso que la categoria no exista, se retorna None
        if not category:
            return False

        self._db.delete(category)
        self._db.commit()

        return category