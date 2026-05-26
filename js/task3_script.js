

function addToCart(productId)
{
    var qtyInput = document.getElementById("qty-input-" + productId);
    var quantity = qtyInput ? parseInt(qtyInput.value) : 1;
    var stock    = parseInt(qtyInput ? qtyInput.getAttribute("data-stock") : "0");

    if(isNaN(quantity) || quantity <= 0){
        alert("Quantity must be at least 1");
        return;
    }
    if(quantity > stock){
        alert("Only " + stock + " units available in stock");
        return;
    }

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4){
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                if(data.success){
                    var navCount = document.getElementById("navCartCount");
                    if(navCount){
                        navCount.textContent = data.cart_count;
                    }
                    alert(data.message);
                } else {
                    alert(data.message || "Failed to add to cart");
                }
            } else {
                alert("Server error. Please try again.");
            }
        }
    };

    xhttp.open("POST", "../control/cart_add_api.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.send(
        "product_id=" + encodeURIComponent(productId) +
        "&quantity=" + encodeURIComponent(quantity)
    );
}

function updateCart(cartId, newQty, maxStock)
{
    if(newQty <= 0){
        removeFromCart(cartId);
        return;
    }
    if(newQty > maxStock){
        alert("Only " + maxStock + " units available in stock");
        return;
    }

    var msgDiv = document.getElementById("cartMsg");
    if(msgDiv){ msgDiv.innerHTML = ""; }

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4){
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                if(data.success){
                    location.reload();
                } else {
                    if(msgDiv){
                        msgDiv.innerHTML = "<div class='msg-error'>" + (data.message || "Failed to update") + "</div>";
                    }
                }
            } else {
                if(msgDiv){
                    msgDiv.innerHTML = "<div class='msg-error'>Server error. Please try again.</div>";
                }
            }
        }
    };

    xhttp.open("POST", "../control/cart_update_api.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.send(
        "cart_id=" + encodeURIComponent(cartId) +
        "&quantity=" + encodeURIComponent(newQty)
    );
}

function removeFromCart(cartId)
{
    if(!confirm("Remove this item from cart?")){
        return;
    }

    var msgDiv = document.getElementById("cartMsg");
    if(msgDiv){ msgDiv.innerHTML = ""; }

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4){
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                if(data.success){
                    location.reload();
                } else {
                    if(msgDiv){
                        msgDiv.innerHTML = "<div class='msg-error'>" + (data.message || "Failed to remove") + "</div>";
                    }
                }
            } else {
                if(msgDiv){
                    msgDiv.innerHTML = "<div class='msg-error'>Server error. Please try again.</div>";
                }
            }
        }
    };

    xhttp.open("POST", "../control/cart_remove_api.php", true);
    xhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhttp.send(
        "cart_id=" + encodeURIComponent(cartId)
    );
}

function addCartButtonsToProductCards(products)
{
    for(var i = 0; i < products.length; i++){
        var m = products[i];
        if(parseInt(m.stock) <= 0){
            continue;
        }

        var card = document.querySelector('.product-card[data-product-id="' + m.id + '"]');
        if(!card || card.querySelector(".add-cart-form")){
            continue;
        }

        var cartForm = document.createElement("div");
        cartForm.className = "add-cart-form";

        var qtyInput = document.createElement("input");
        qtyInput.type = "number";
        qtyInput.className = "add-cart-qty";
        qtyInput.id = "qty-input-" + m.id;
        qtyInput.value = "1";
        qtyInput.min = "1";
        qtyInput.max = m.stock;
        qtyInput.setAttribute("data-stock", m.stock);

        var btn = document.createElement("button");
        btn.type = "button";
        btn.className = "btn-add-cart";
        btn.textContent = "Add to Cart";
        btn.setAttribute("onclick", "addToCart(" + m.id + ")");

        cartForm.appendChild(qtyInput);
        cartForm.appendChild(btn);
        card.appendChild(cartForm);
    }
}

if(typeof renderProductCards !== "undefined"){
    var task1RenderProductCards = renderProductCards;
    renderProductCards = function(products){
        task1RenderProductCards(products);
        addCartButtonsToProductCards(products);
    };
}
