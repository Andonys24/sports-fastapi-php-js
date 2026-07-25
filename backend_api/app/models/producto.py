class Producto:
    # Metodo constructor
    def __init__(self, id_proveedor: int, id_categoria: int, descripcion: str, 
                precio: float, marca: str) -> None:
        self._id_proveedor = id_proveedor
        self._id_categoria = id_categoria
        self._descripcion = descripcion
        self._precio = precio
        self._marca = marca
        self._stock = 0 # El stock empieza en 0


    # Metodos getter y setter
    # ID del proveedor
    @property
    def id_proveedor(self) -> int:
        return self._id_proveedor

    # ID de la categoria que esta asignada al producto
    @property
    def id_categoria(self) -> int:
        return self._id_categoria

    # Descripcion del producto
    @property
    def descripcion(self) -> str:
        return self._descripcion

    @descripcion.setter
    def descripcion(self, nueva_descripcion: str) -> None:
        self._descripcion = nueva_descripcion

    # Precio
    @property
    def precio(self) -> float:
        return self._precio

    @precio.setter
    def precio(self, cambio_precio: float) -> None:
        self._precio = cambio_precio

    # Marca
    @property
    def marca(self) -> str:
        return self._marca

    @marca.setter
    def marca(self, marca_nueva: str) -> None:
        self._marca = marca_nueva

    # Stock actual de dicho producto
    @property
    def stock(self) -> int:
        return self._stock

    @stock.setter
    def stock(self, nuevo_stock) -> None:
        # Validacion para evitar que disminuya el stock cuando este ya es 0
        if (self._stock < 0):
            raise ValueError("El stock no puede disminuir mas")

        # En caso contrario, este puede aumentar o disminuir sin problema
        self._stock = nuevo_stock