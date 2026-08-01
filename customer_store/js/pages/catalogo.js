class Catalogo {
    constructor(apiService) {
        this.api = apiService;
        this.contenedor = document.getElementById('catalogo-productos');
        this.selectFiltro = document.getElementById('filtro-categoria');
        
        this.productosTodos = []; 
        this.categorias = [];
        
        this.formateadorMoneda = new Intl.NumberFormat('es-HN', {
            style: 'currency', currency: 'HNL'
        });

        if (this.selectFiltro) {
            // El filtro ahora trabajará con IDs (números), no con nombres de texto
            this.selectFiltro.addEventListener('change', (e) => this.filtrarProductos(e.target.value));
        }
    }

    async inicializar() {
        // Cargar categorías y productos en paralelo para mayor eficiencia
        const [categorias, productos] = await Promise.all([
            this.api.get('/categories'),
            this.api.get('/products')
        ]);

        this.categorias = categorias || [];
        this.productosTodos = productos || [];

        this.cargarOpcionesDeFiltro();
        this.filtrarProductos('Todas'); 
    }

    cargarOpcionesDeFiltro() {
        if (!this.selectFiltro) return;
        
        this.selectFiltro.innerHTML = '<option value="Todas">Todas las categorías</option>';
        
        this.categorias.forEach(cat => {
            const option = document.createElement('option');
            option.value = cat.id;
            option.textContent = cat.name;
            this.selectFiltro.appendChild(option);
        });
    }

    filtrarProductos(categoriaId) {
        let productosFiltrados = this.productosTodos;
        
        if (categoriaId !== 'Todas') {
            // Convertimos a entero porque el value del select es un string
            productosFiltrados = this.productosTodos.filter(p => p.category_id === parseInt(categoriaId));
        }
        
        this.renderizar(productosFiltrados);
    }

    renderizar(productos) {
        this.contenedor.innerHTML = '';
        if (productos.length === 0) {
            this.contenedor.innerHTML = '<p>No hay productos disponibles.</p>';
            return;
        }

        productos.forEach(producto => {
            // Buscar el nombre de la categoría para mostrarlo visualmente
            const categoria = this.categorias.find(c => c.id === producto.category_id);
            const nombreCategoria = categoria ? categoria.name : 'General';
            
            // Lógica para deshabilitar el botón si no hay stock
            const hayStock = producto.stock > 0;
            const textoBoton = hayStock ? 'Añadir al Carrito' : 'Agotado';
            const botonDisabled = hayStock ? '' : 'disabled style="background-color: #94a3b8; cursor: not-allowed;"';

            // Manejo de imagen con fallback en caso de error 404 de FastAPI
            const imgUrl = producto.img_url || 'https://via.placeholder.com/280x200?text=Sin+Imagen';

            const div = document.createElement('div');

            const div = document.createElement('div');
            div.className = 'producto-card';
            div.innerHTML = `
                <div style="height: 200px; overflow: hidden; border-radius: 8px; margin-bottom: 1rem;">
                    <img src="${imgUrl}" alt="${producto.name}" onerror="this.src='https://via.placeholder.com/280x200?text=Error+de+Imagen'" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <span style="background:#e2e8f0; padding:2px 8px; border-radius:10px; font-size:0.8rem;">
                        ${nombreCategoria}
                    </span>
                    <span style="font-size:0.8rem; color: #64748b;">
                        Stock: ${producto.stock}
                    </span>
                </div>
                
                <h3>${producto.name}</h3>
                <p style="font-size: 0.9rem; color: #64748b; margin-bottom: 0.5rem;">Marca: ${producto.brand || 'N/A'}</p>
                <p style="font-size: 0.85rem; color: #475569; margin-bottom: 1rem;">
                ${producto.descripcion || 'Sin descripción disponible.'}</p>
                <p><strong>${this.formateadorMoneda.format(producto.price)}</strong></p>
                
                <button ${botonDisabled} onclick="carrito.agregar(${producto.id}, '${producto.name}', ${producto.price})">
                    ${textoBoton}
                </button>
            `;
            this.contenedor.appendChild(div);
        });
    }
}

// Modificar la inicialización en el router o archivo
if (window.location.pathname === '/catalogo') {
    const catalogo = new Catalogo(api);
    document.addEventListener('DOMContentLoaded', () => catalogo.inicializar());
}