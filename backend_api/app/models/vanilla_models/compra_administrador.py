from app.models.vanilla_models.compra import Compra

class CompraAdministrador(Compra):
    # Metodo constructor
    def __init__(self, id_producto: int, cantidad: int, precio_unidad: float, 
                fecha: str, id_proveedor: int) -> None:
        # Inicializacion del metodo constructor de la clase padre
        super().__init__(id_producto, cantidad, precio_unidad, fecha)
        # Inicializacion de los atributos especificos
        self._id_proveedor = id_proveedor

    # Metodos getter especificos
    @property
    def id_proveedor(self) -> int:
        return self._id_proveedor