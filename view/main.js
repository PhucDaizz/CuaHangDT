document.addEventListener('DOMContentLoaded', function() {
    fetch('http://localhost/CuaHangDT/api/sanpham/read.php?page=1&limit=10&sort_by=price&sort_order=ASC')
        .then(response => response.json())
        .then(data => {
            const productList = document.getElementById('product-list');
            data.data.forEach(product => {
                const productDiv = document.createElement('div');
                productDiv.className = 'product';
                productDiv.addEventListener('click', function() {
                    window.location.href = `product_detail.html?id=${product.product_id}`;
                });

                productDiv.innerHTML = `
                    <img src="${product.thumbnail_image}" alt="${product.thumbnail_image}">
                    <h3>${product.product_name}</h3>
                    <p>${product.description}</p>
                    <p class="price">${Number(product.price).toLocaleString()} VND</p>
                    <p>Stock: ${product.stock}</p>
                `;

                productList.appendChild(productDiv);
            });
        })
        .catch(error => console.error('Error fetching products:', error));
});
