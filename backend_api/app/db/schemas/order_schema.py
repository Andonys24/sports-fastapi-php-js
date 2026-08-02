from sqlalchemy import String, ForeignKey
from sqlalchemy.orm import Mapped, mapped_column, relationship
from db.database import Base
from datetime import date, datetime, time

class Order(Base):
    # Nombre de la tabla
    __tablename__ = "encargos"

    # Columnas respectivas de la tabla
    id: Mapped[int] = mapped_column(primary_key=True)

    # ForeignKeys
    user_id: Mapped[int] = mapped_column(ForeignKey("usuarios.id"))
    address: Mapped[str] = mapped_column(String, index=True)
    time_order: Mapped[time] = mapped_column(default=datetime.now().time())
    date_order: Mapped[date] = mapped_column(default=date.today())
    total: Mapped[float] = mapped_column()

    user = relationship("User", foreign_keys=[user_id])