class Proveedor:
    # Metodo constructor
    def __init__(self, id_proveedor: int, nombre: str, tipo_contrato: str, plazo_contrato: int,
                pais: str) -> None:
        self._id_proveedor = id_proveedor
        self._nombre = nombre
        self._tipo_contrato = tipo_contrato
        self._plazo_contrato = plazo_contrato
        self._pais = pais

    # Metodos getter y setter
    # ID del proveedor
    @property
    def id_proveedor(self) -> int:
        return self._id_proveedor

    @id_proveedor.setter
    def id_proveedor(self, nuevo_id: int) -> None:
        self._id_proveedor = nuevo_id

    # Nombre del proveedor
    @property
    def nombre(self) -> str:
        return self._nombre

    @nombre.setter
    def nombre(self, nuevo_nombre: str) -> None:
        self._nombre = nuevo_nombre

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

    # Comprobacion sobre si un proveedor es nacional o internacional
    @property
    def pais(self) -> str:
        return self._pais

    @pais.setter
    def pais(self, cambio_pais: str) -> None:
        self._pais = cambio_pais