from db.database import Session
from db.schemas.user_schema import User # Obtiene el usuario a partir del cual se crearon las tablas
from services.authentication_utils import verify_password, hash_password

class UserService:
    def __init__(self, db: Session) -> None:
        self._db = db

    # Definicion del metodo POST
    def create_user(self, username: str, password: str, full_name: str,
                email: str, admin: int = 0) -> User:
        # Crea un nuevo objeto de tipo User, con los datos que le sean pasados como atributos
        # Encripta la contrasena enviada siempre, cada vez que se cree un usuario
        hashed_passwd = hash_password(password)

        user = User(
            username=username,
            password=hashed_passwd, 
            full_name=full_name,
            email=email,
            admin=admin
        )

        self._db.add(user) # Agrega el nuevo usuario a la base de datos
        self._db.commit()
        self._db.refresh(user)

        # Se retorna siempre el usuario creado
        return user

    # Definicion del metodo GET para obtener todos los usuarios
    def get_users(self, skip: int, limit: int) -> list[User]:
        return self._db.query(User).offset(skip).limit(limit).all()

    # Definicion del metodo GET para obtener solamente un usuario por su id
    # Puede retornar None en caso que el usuario no se haya encontrado
    def get_user_id(self, user_id: int) -> User | None:
        return self._db.query(User).filter(User.id == user_id).first()

    # Metodo para encontrar un usuario en base a su nombre de usuario, en vez de su id
    def get_user_name(self, username: str) -> User | None:
        return self._db.query(User).filter(User.username == username).first()
    
    # Definicion del metodo PUT
    def update_user(self, user_id: int, username: str, password: str | None, 
                full_name: str, email: str, admin: int) -> User | None:
        user = self.get_user_id(user_id)

        # En el caso de no encontrar el usuario
        if not user:
            return None

        user.username = username
        user.full_name = full_name
        user.email = email
        user.admin = admin

        # Validacion para evitar actualizar la contrasena en el caso que se haya enviado vacia
        # Comprueba si se envio una contrasena, la cual puede ser opcional
        if (password and password.strip()):
            # En el caso que se envie una contrasena, la encriptada y la guarda encriptada en la base de datos
            hashed_passwd = hash_password(password)

            # Actualiza la contrasena del usuario
            user.password = hashed_passwd

        # En caso contrario, no actualiza la contrasena pero si los demas campos, actualizando en la base de datos
        self._db.commit()
        self._db.refresh(user)

        return user

    # Definicion del metodo DELETE
    def delete_user(self, user_id: int) -> bool:
        # Obtencion del usuario por medio del metodo get_user_id() definido previamente
        user = self.get_user_id(user_id)

        # En caso de que se haya retornado None, esta funcion retorna False
        # en forma de decir que no se pudo eliminar el usuario, debido a que no fue encontrado
        if not user:
            return False

        # En caso contrario, realiza la eliminacion normalmente
        self._db.delete(user)
        self._db.commit()

        return True

    # Funcion para validar un usuario
    def authenticate_user(self, username: str, password: str):
        user = self.get_user_name(username)

        if not user:
            return None

        if not verify_password(
            normal_passwd=password,
            hashed_passwd=user.password):
            return None

        return user