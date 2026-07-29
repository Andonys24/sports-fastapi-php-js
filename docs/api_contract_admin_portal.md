# Contrato API Propuesto - Portal Administrativo PHP

Este documento define el contrato minimo que el portal administrativo en PHP espera consumir desde FastAPI. La idea es que sirva como base comun para backend y frontend, para evitar incongruencias en nombres de campos, respuestas y rutas.

## Criterios generales

- Base URL sugerida: `http://localhost:8000`
- Formato de intercambio: JSON
- Todas las respuestas exitosas deben devolver JSON valido
- Todos los errores deben devolver una respuesta consistente con una clave `detail`
- Para el portal admin, el usuario autenticado debe tener `role = 1` o `ROLE_ADMIN`
- Los identificadores numericos usan `id`
- Las rutas del portal PHP no deben mezclarse con las rutas de FastAPI; el PHP solo consume la API

## Formato de error comun

```json
{
  "detail": "Mensaje de error descriptivo"
}
```

## Autenticacion

### `POST /login`

Uso: autenticar administrador del portal.

Request:

```json
{
  "email": "admin@correo.com",
  "password": "123456"
}
```

Response exitosa:

```json
{
  "id": 1,
  "name": "Administrador",
  "email": "admin@correo.com",
  "role": 1
}
```

Response de error:

```json
{
  "detail": "Credenciales incorrectas o acceso no autorizado"
}
```

## Dashboard / Encargos

### `GET /orders?date=YYYY-MM-DD`

Uso: listar encargos o ventas del dia para el panel admin.

Response esperada:

```json
[
  {
    "id": 10,
    "time": "12:30:00",
    "client": "Juan Perez",
    "email": "juan@mail.com",
    "place": "Tegucigalpa",
    "product": "Balon de Futbol Nike",
    "price": 45.0,
    "quantity": 2
  }
]
```

### `GET /categories`

Response esperada:

```json
[
  {
    "id": 1,
    "name": "Futbol",
    "description": "Articulos de futbol"
  }
]
```

### `GET /categories/{id}`

Response esperada:

```json
{
  "id": 1,
  "name": "Futbol",
  "description": "Articulos de futbol"
}
```

### `POST /categories`

Request:

```json
{
  "name": "Futbol",
  "description": "Articulos de futbol"
}
```

Response exitosa:

```json
{
  "id": 1,
  "name": "Futbol",
  "description": "Articulos de futbol"
}
```

### `PUT /categories/{id}`

Request:

```json
{
  "name": "Futbol",
  "description": "Articulos de futbol"
}
```

Response exitosa:

```json
{
  "id": 1,
  "name": "Futbol",
  "description": "Articulos de futbol"
}
```

### `DELETE /categories/{id}`

Response sugerida:

```json
{
  "resultado": true,
  "mensaje": "Categoria eliminada exitosamente"
}
```

## Productos

### `GET /products`

Response esperada:

```json
[
  {
    "id": 1,
    "name": "Balon de Futbol Nike",
    "price": 45.0,
    "category_id": 1
  }
]
```

### `GET /products/{id}`

Response esperada:

```json
{
  "id": 1,
  "name": "Balon de Futbol Nike",
  "price": 45.0,
  "category_id": 1
}
```

### `POST /products`

Request actual del portal PHP:

```json
{
  "name": "Balon de Futbol Nike",
  "price": 45.0,
  "category_id": 1
}
```

Response exitosa:

```json
{
  "id": 1,
  "name": "Balon de Futbol Nike",
  "price": 45.0,
  "category_id": 1
}
```

### `PUT /products/{id}`

Request actual del portal PHP:

```json
{
  "name": "Balon de Futbol Nike",
  "price": 45.0,
  "category_id": 1
}
```

Response exitosa:

```json
{
  "id": 1,
  "name": "Balon de Futbol Nike",
  "price": 45.0,
  "category_id": 1
}
```

### `DELETE /products/{id}`

Response sugerida:

```json
{
  "resultado": true,
  "mensaje": "Producto eliminado exitosamente"
}
```

## Endpoints pendientes por definir con backend

Estos no estan implementados aun en el portal PHP, pero conviene dejarlos previstos en el acuerdo para no improvisar despues.

### Usuarios

- `GET /users`
- `GET /users/{id}`
- `POST /users`
- `PUT /users/{id}`
- `DELETE /users/{id}`

### Proveedores

- `GET /suppliers`
- `GET /suppliers/{id}`
- `POST /suppliers`
- `PUT /suppliers/{id}`
- `DELETE /suppliers/{id}`

### Compras para inventario

- `GET /purchases`
- `POST /purchases`
- `GET /purchases/{id}`

### Inventario

- `GET /inventory`
- `GET /inventory/{id}`
- `PUT /inventory/{id}`

### Facturas / ventas

- `GET /invoices`
- `GET /invoices/{id}`
- `POST /invoices`

## Observaciones de implementacion para PHP

- Si una respuesta de API llega vacia o invalida, el portal debe mostrar un mensaje de error claro.
- El portal actual ya trabaja con `detail` como error general.
- Para eliminar registros desde el frontend, la respuesta debe mantener `resultado` y `mensaje`.
- Si el backend decide cambiar alguna clave, debe avisarse antes de integrar para evitar romper el portal.

## Prioridad recomendada para el backend

1. `POST /login`
2. `GET /categories`, `POST /categories`, `PUT /categories/{id}`, `DELETE /categories/{id}`
3. `GET /products`, `POST /products`, `PUT /products/{id}`, `DELETE /products/{id}`
4. `GET /orders?date=YYYY-MM-DD`
5. Endpoints de usuarios, proveedores, compras e inventario
