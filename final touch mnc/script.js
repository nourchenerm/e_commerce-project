var selectedSize = ""; 

function selectSize(size) {
    selectedSize = size; 
    var addToCartButton = document.getElementById('addToCartButton');
    event.preventDefault();     // Annulation de l'événement par défaut (dans ce contexte, c'est probablement un événement de clic)
    addToCartButton.style.display = 'block';
}

function addToCart(productName, price, size) {
    if (size) {
        addToCartToCart(productName, price, size);
        alert('Added to cart: ' + productName + ' - ' + size);
        updateCartCount();
    } else {
        alert('Please select a size.');
    }
}

function addToCartToCart(productName, price, size) {
    // Création d'un objet
    var item = {
        name: productName,
        price: price,
        size: size,
        quantity: 1
    };
    // Récupération du contenu actuel du panier depuis le stockage local, ou initialisation d'un tableau vide si le panier est vide
    var cart = JSON.parse(localStorage.getItem('cart')) || [];
     // Recherche de l'indice de l'article dans le panier. Si l'article est déjà dans le panier, renvoie son indice ; sinon, renvoie -1
    let itemIndex = cart.findIndex(i => i.name === productName && i.size === size);
    if (itemIndex === -1) {
        cart.push(item);
    } else {
        cart[itemIndex].quantity++;
    }
    // Mettre à jour le contenu du panier dans le stockage local en convertissant le tableau en format JSON
    localStorage.setItem('cart', JSON.stringify(cart));
}

let cartCount = 0;

function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    cartCount = cart.reduce((total, item) => total + item.quantity, 0);  // Calcul du nombre total d'articles dans le panier en utilisant la méthode reduce() pour additionner les quantités de tous les articles
    const cartCountElement = document.querySelector('.cart-count'); // Sélection de l'élément HTML avec la classe 'cart-count', qui affiche le nombre d'articles dans le panier
    cartCountElement.textContent = cartCount;   // Mise à jour du contenu de l'élément HTML avec le nombre total d'articles dans le panier
}
// Ajout d'un événement qui se déclenche lorsque la fenêtre est entièrement chargée
window.onload = function() {
  // Appel de la fonction updateCartCount pour mettre à jour le nombre d'articles dans le panier dès que la page est chargée
    updateCartCount();
}


//////////////////////////// script panier 
