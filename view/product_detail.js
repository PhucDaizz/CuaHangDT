document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id');

    fetch(`http://localhost/CuaHangDT/api/sanpham/show.php?id=${productId}`)
        .then(response => response.json())
        .then(data => {
            const productDetail = document.getElementById('product-detail');
            const product = data;

            productDetail.innerHTML = `
                <img src="${product.thumbnail_image}" alt="${product.product_name}">
                <img src="${product.detail_image1}" alt="${product.product_name}">
                <h3>${product.product_name}</h3>
                <p>${product.description}</p>
                <p class="price">${Number(product.price).toLocaleString()} VND</p>
                <p>Stock: ${product.stock}</p>
            `;
        })
        .catch(error => console.error('Error fetching product detail:', error));
});
