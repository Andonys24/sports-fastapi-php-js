class Proveedor:
    # Metodo constructor
    def __init__(self, nombre: str, direccion: str, telefono: str, 
                plazo_contrato: int, tipo_contrato: str) -> None:
        self._nombre = nombre
        self._direccion = direccion
        self._telefono = telefono
        self._plazo_contrato = plazo_contrato
        self._tipo_contrato = tipo_contrato

    # Metodos getter y setter
    # Nombre del proveedor
    @property
    def nombre(self) -> str:
        return self._nombre

    @nombre.setter
    def nombre(self, nuevo_nombre: str) -> None:
        self._nombre = nuevo_nombre

    # Direccion del proveedor
    @property
    def direccion(self) -> str:
        return self._direccion

    @direccion.setter
    def direccion(self, nueva_direccion: str) -> None:
        self._direccion = nueva_direccion

    # Numero de telefono del proveedor
    @property
    def telefono(self) -> str:
        return self._telefono

    @telefono.setter
    def telefono(self, nuevo_telefono: str) -> None:
        self._telefono = nuevo_telefono

    # Tipo del contrato (contrato de precio fijo, por costes reembolsables, por tiempo y materiales)
    @property 
    def tipo_contrato(self) -> str:
        return self._tipo_contrato

    @tipo_contrato.setter
    def tipo_contrato(self, nuevo_tipo_contrato: str) -> None:
        self._tipo_contrato = nuevo_tipo_contrato

    # Plazo del contrato (3, 6, 9 o 12 meses)
    @property
    def plazo_contrato(self) -> int:
        return self._plazo_contrato

    @plazo_contrato.setter
    def plazo_contrato(self, nuevo_plazo: int) -> None:
        self._plazo_contrato = nuevo_plazo