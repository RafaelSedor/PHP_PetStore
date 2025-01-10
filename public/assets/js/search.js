document.getElementById('search-button').addEventListener('click', function () {
    const name = document.getElementById('search-name').value;
    const category = document.getElementById('search-category').value;

    fetch(`/api/products/search?name=${encodeURIComponent(name)}&category=${encodeURIComponent(category)}`)
        .then(response => response.json())
        .then(data => {
            const productResults = document.getElementById('product-results');
            productResults.innerHTML = '';

            if (data.products.length === 0) {
                productResults.innerHTML = '<p class="text-center text-muted">Nenhum produto encontrado.</p>';
                return;
            }

            data.products.forEach(product => {
                const card = `
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <img src="${product.image_url}" class="card-img-top" alt="${product.name}" style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">${product.description}</p>
                                <p class="card-text fw-bold mt-auto">R$ ${parseFloat(product.price).toFixed(2).replace('.', ',')}</p>
                            </div>
                        </div>
                    </div>
                `;
                productResults.insertAdjacentHTML('beforeend', card);
            });
        })
        .catch(error => console.error('Erro ao buscar produtos:', error));
});
