// script.js

function updateCartCount(count) {
    const cartButton = document.getElementById('cart-button');
    cartButton.textContent = `View Cart (${count})`;
}

function addToCart(productId) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'add_to_cart.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            updateCartCount(xhr.responseText);
        }
    };
    xhr.send(`product_id=${productId}`);
}
