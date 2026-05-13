    (function() {
        const modalaula = document.querySelector('#techModal-aula');
        const openaula = document.querySelector('.btn-abrir-ficha-aula');
        const closeaula = document.querySelector('.btn-cerrar-ficha-aula');

        if (openaula) openaula.onclick = () => modalaula.showModal();
        if (closeaula) closeaula.onclick = () => modalaula.close();

        const modalactivo = document.querySelector('#techModal-activos');
        const openactivo = document.querySelector('.btn-abrir-ficha-activos');
        const closeactivo = document.querySelector('.btn-cerrar-ficha-activos');

        if (openactivo) openactivo.onclick = () => modalactivo.showModal();
        if (closeactivo) closeactivo.onclick = () => modalactivo.close();
    })();