
function validateRegistration()
{
    var name     = document.getElementById("reg_name").value.trim();
    var email    = document.getElementById("reg_email").value.trim();
    var password = document.getElementById("reg_password").value;
    var address  = document.getElementById("address").value.trim();
    var phone    = document.getElementById("phone").value.trim();

    if(name == ""){ alert("Full name is required"); return false; }
    if(email == ""){ alert("Email is required"); return false; }
    if(password == ""){ alert("Password is required"); return false; }
    if(address == ""){ alert("Address is required"); return false; }
    if(phone == ""){ alert("Phone number is required"); return false; }
    if(!/^[0-9]+$/.test(phone)){ alert("Phone number must contain digits only"); return false; }
    if(password.length < 8){ alert("Password must be at least 8 characters"); return false; }
    if(email.indexOf("@") == -1 || email.indexOf(".") == -1){ alert("Enter a valid email address"); return false; }
    return true;
}

function validateLogin()
{
    var email    = document.getElementById("email").value.trim();
    var password = document.getElementById("password").value;
    if(email == ""){ alert("Email is required"); return false; }
    if(password == ""){ alert("Password is required"); return false; }
    return true;
}

function validateProfile()
{
    var name    = document.getElementById("name").value.trim();
    var email   = document.getElementById("email").value.trim();
    var address = document.getElementById("address").value.trim();
    var phone   = document.getElementById("phone").value.trim();
    var currentPassword = document.getElementById("current_password").value;
    var newPassword     = document.getElementById("new_password").value;

    if(name == "" || email == "" || address == "" || phone == ""){
        alert("Name, email, address and phone are required"); return false;
    }
    if(email.indexOf("@") == -1 || email.indexOf(".") == -1){ alert("Enter a valid email"); return false; }
    if(newPassword != "" && currentPassword == ""){ alert("Current password required"); return false; }
    if(newPassword != "" && newPassword.length < 8){ alert("New password must be at least 8 characters"); return false; }
    return true;
}

function searchProducts()
{
    var list = document.getElementById("productList");
    if(!list){ return; }

    var q        = document.getElementById("searchText").value.trim();
    var gender   = document.getElementById("genderFilter").value;
    var category = document.getElementById("categoryFilter").value;

    var url = "../control/product_search.php?q=" + encodeURIComponent(q) +
              "&gender=" + encodeURIComponent(gender) +
              "&category=" + encodeURIComponent(category);

    list.innerHTML = "<div class='no-results'>Searching...</div>";

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function(){
        if(this.readyState == 4){
            if(this.status == 200){
                var data = JSON.parse(this.responseText);
                if(data.success){ renderProductCards(data.products); }
                else{ list.innerHTML = "<div class='no-results'>Search failed.</div>"; }
            } else {
                list.innerHTML = "<div class='no-results'>Search failed.</div>";
            }
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
}

function renderProductCards(products)
{
    var list = document.getElementById("productList");
    list.innerHTML = "";
    if(products.length == 0){
        list.innerHTML = "<div class='no-results'>No products found.</div>";
        return;
    }
    for(var i = 0; i < products.length; i++){
        var p = products[i];
        var card = document.createElement("div");
        card.className = "product-card";
        var title = document.createElement("h3");
        var link = document.createElement("a");
        link.href = "product_detail.php?id=" + p.id;
        link.textContent = p.name;
        title.appendChild(link);
        var badge = document.createElement("span");
        badge.className = "badge badge-gender";
        badge.textContent = p.gender;
        var cat = document.createElement("p");
        cat.textContent = "Category: " + (p.category_name || "");
        var stock = document.createElement("p");
        stock.textContent = "Stock: " + p.stock + " units";
        var price = document.createElement("p");
        price.className = "product-price";
        price.textContent = "BDT " + p.price;
        var detail = document.createElement("a");
        detail.className = "btn-link";
        detail.href = "product_detail.php?id=" + p.id;
        detail.textContent = "View Details";
        card.appendChild(title);
        card.appendChild(badge);
        card.appendChild(cat);
        card.appendChild(stock);
        card.appendChild(price);
        card.appendChild(detail);
        list.appendChild(card);
    }
}
