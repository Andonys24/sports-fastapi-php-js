(function () {
    // Seleccionar todos los botones con la clase 'boton-imprimir'
    const btnsImprimir = document.querySelectorAll(".boton-imprimir");
    btnsImprimir.forEach(btnImprimir => {
        btnImprimir.addEventListener("click", function () {
            try {
                // Selecciona las secciones que deseas imprimir
                const li = btnImprimir.parentNode.parentNode;
                const fecha = document.querySelector("#fecha-admin");

                // Clonar el nodo li para modificarlo sin afectar el DOM original
                const liClone = li.cloneNode(true);

                // Eliminar el botón de impresión y otros elementos no deseados
                const printButton = liClone.querySelector(".boton-imprimir");
                if (printButton) {
                    printButton.remove();
                }
                const deleteButton = liClone.querySelector(".boton-eliminar");
                if (deleteButton) {
                    deleteButton.remove();
                }

                // Extraer el valor de la fecha
                const fechaValor = fecha.value;

                // Crea una ventana nueva para imprimir
                const printWindow = window.open("", "", "height=600,width=800");
                printWindow.document.write("<html><head><title>Reporte dia: " + fechaValor + "</title>");
                printWindow.document.write("<style>");
                printWindow.document.write("@page { size: A5; margin: 10mm; }");
                printWindow.document.write("body { font-family: Arial, sans-serif; text-align: center; margin: 0; padding: 0; }");
                printWindow.document.write(".factura { border: 2px solid #333; padding: 20px; margin: 20px; }");
                printWindow.document.write("li { list-style-type: none; text-align: left; margin-bottom: 10px; }");
                printWindow.document.write("p { margin: 5px 0; }");
                printWindow.document.write(".total { font-weight: bold; }");
                printWindow.document.write("</style>");
                printWindow.document.write("</head><body>");
                printWindow.document.write("<div class='factura'>");
                printWindow.document.write("<h2>Dia Entrega: " + fechaValor + "</h2>");
                printWindow.document.write(liClone.outerHTML);
                printWindow.document.write("</div>");
                printWindow.document.close();

                // Asegurar el cierre de la ventana después de la impresión
                printWindow.onafterprint = function () {
                    printWindow.close();
                };

                printWindow.print();
            } catch (error) {
                console.error("Error al intentar imprimir:", error);
                alert("Ocurrió un error al intentar imprimir. Por favor, inténtalo de nuevo.");
            }
        });
    });
})();