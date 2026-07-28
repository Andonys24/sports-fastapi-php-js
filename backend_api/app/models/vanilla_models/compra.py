class Compra:
    # Constantes
    _PORCENTAJE_IMPUESTO: float = 0.15

    # Metodo constructor
    def __init__(self, id_producto: int, cantidad: int, precio_unidad: float,
                fecha: str) -> None:
        self._id_producto = id_producto
        self._cantidad = cantidad
        self._precio_unidad = precio_unidad
        self._monto_total = 0.0   # El monto total empieza en 0, debido a que se necesita calcular
        self._fecha = fecha 

    # Metodos getter y setter
    # ID del producto que pertenece a la compra
    @property
    def id_producto(self) -> int:
        return self._id_producto

    # Cantidad del producto que fue comprada
    @property
    def cantidad(self) -> int:
        return self._cantidad

    @cantidad.setter
    def cantidad(self, nueva_cantidad: int) -> None:
        # Validacion para evitar numeros negativos 
        if (nueva_cantidad < 0):
            raise ValueError("No se puede agregar una cantidad negativa")

        self._cantidad = nueva_cantidad

    # Precio por unidad del producto
    @property
    def precio_unidad(self) -> float:
        return self._precio_unidad

    @precio_unidad.setter
    def precio_unidad(self, nuevo_precio: float):
        # Validacion de numeros negativos
        if (nuevo_precio < 0):
            raise ValueError("El precio no se puede ser negativo")

        self._precio_unidad = nuevo_precio

    # Monto total, despues de aplicar impuestos o descuentos
    def calcular_subtotal(self) -> float:
        return self._cantidad * self._precio_unidad

    def calcular_impuesto(self) -> float:
        return self.calcular_subtotal() * self._PORCENTAJE_IMPUESTO

    def calcular_monto_total(self) -> float:
        return self.calcular_subtotal() + self.calcular_impuesto()

    # Fecha de la compra
    @property
    def fecha(self) -> str:
        return self._fecha

    @fecha.setter
    def fecha(self, nueva_fecha: str) -> None:
        self._fecha = nueva_fecha