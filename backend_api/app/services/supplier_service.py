from db.database import Session
from db.schemas.supplier_schema import Supplier # Obtiene el usuario a partir del cual se crearon las tablas

class SupplierService:
    def __init__(self, db: Session) -> None:
        self._db = db

    # Definicion del metodo POST
    def create_supplier(self, name: str, address: str, phone_number: str,
                contract_period: int, contract_type: str) -> Supplier:
        # Crea un nuevo objeto de tipo Supplier, con los datos que le sean pasados como atributos
        supplier = Supplier(
            name=name,
            address=address,
            phone_number=phone_number,
            contract_period=contract_period,
            contract_type=contract_type   
        )

        self._db.add(supplier) # Agrega el nuevo usuario a la base de datos
        self._db.commit()
        self._db.refresh(supplier)

        # Se retorna siempre el usuario creado
        return supplier

    # Definicion del metodo GET para obtener todos los usuarios
    def get_suppliers(self, skip: int, limit: int) -> list[Supplier]:
        return self._db.query(Supplier).offset(skip).limit(limit).all()

    # Definicion del metodo GET para obtener solamente un usuario por su id
    # Puede retornar None en caso que el usuario no se haya encontrado
    def get_supplier_id(self, supplier_id: int) -> Supplier | None:
        return self._db.query(Supplier).filter(Supplier.id == supplier_id).first()
    
    # Definicion del metodo PUT
    def update_supplier(self, supplier_id: int, name: str, address: str, phone_number: str,
                contract_period: int, contract_type: str) -> Supplier | None:
        supplier = self.get_supplier_id(supplier_id)

        if not supplier:
            return None

        supplier.name=name
        supplier.address=address
        supplier.phone_number=phone_number
        supplier.contract_period=contract_period
        supplier.contract_type=contract_type  

        self._db.commit()
        self._db.refresh(supplier)

        return supplier

    # Definicion del metodo DELETE
    def delete_supplier(self, supplier_id: int) -> bool:
        # Obtencion del usuario por medio del metodo get_Supplier_id() definido previamente
        supplier = self.get_supplier_id(supplier_id)

        # En caso de que se haya retornado None, esta funcion retorna False
        # en forma de decir que no se pudo eliminar el usuario, debido a que no fue encontrado
        if not supplier:
            return False

        # En caso contrario, realiza la eliminacion normalmente
        self._db.delete(supplier)
        self._db.commit()

        return True