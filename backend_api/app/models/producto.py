class Producto:
    # Metodo constructor
    def __init__(self, id_producto: int, id_categoria: int, descripcion: str, 
                precio: float, marca: str) -> None:
        self._id_producto = id_producto
        self._id_categoria = id_categoria
        self._descripcion = descripcion
        self._precio = precio
        self._marca = marca

    # Metodos getter y setter
    # ID del producto
    @property
    def id_producto(self) -> int:
        return self._id_producto

    @id_producto.setter
    def id_producto(self, nuevo_id: int) -> None:
        self._id_producto = nuevo_id

    # ID de la categoria que esta asignada al producto
    @property
    def id_categoria(self) -> int:
        return self._id_categoria

    @id_categoria.setter
    def categoria(self, cambio_id_categoria: int) -> None:
        self._id_categoria = cambio_id_categoria

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