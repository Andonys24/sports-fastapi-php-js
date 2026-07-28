from app.models.vanilla_models.compra import Compra

class CompraCliente(Compra):
    # Metodo constructor
    def __init__(self, id_producto: int, cantidad: int, precio_unidad: float, 
                fecha: str, id_usuario: int, entrega_domicilio: bool, direccion_entrega: str) -> None:
        # Inicializacion del metodo constructor de la clase padre
        super().__init__(id_producto, cantidad, precio_unidad, fecha)
        #Inicializacion de los atributos especificos
        self._id_usuario = id_usuario
        self._entrega_domicilio = entrega_domicilio
        self._direccion_entrega = direccion_entrega

    # Metodos getter especificos
    # ID del usuario que realizo la compra
    @property
    def id_usuario(self) -> int:
        return self._id_usuario

    # Valor booleano en el que se determina si el cliente escogio uan entrega a domicilio o no
    @property
    def entrega_domicilio(self) -> bool:
        return self._entrega_domicilio

    # Direccion de la entrega, en el caso que se haya escogido un envio a domicilio
    @property
    def direccion_entrega(self) -> str:
        return self._direccion_entrega