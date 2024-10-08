function encodeForAjax(data) {
    return Object.keys(data).map(function(k){
      return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
    }).join('&')
  }

function removeItensCart(){
    const buttons = document.querySelectorAll('#shoppingCart tr button');
    
    if(buttons.length == 0){
        //the current page doesn't have this section
        return;
    }
    
    const totalPriceElem = document.getElementById("totalPrice");
    let totalPrice = parseFloat(totalPriceElem.innerHTML);

    for (const button of buttons) button.addEventListener('click', async function () {
        
        let row = button.parentElement.parentElement;

        //calculate the new price to display
        const priceElem = row.querySelector(".price");
        const productPrice = parseFloat(priceElem.innerHTML);
        totalPrice = parseFloat(totalPrice - productPrice).toFixed(2);
        totalPriceElem.textContent = totalPrice.toString();


        let product = row.getAttribute("id"); //tr (the product row)

        //sends a request to delete the product from the cart in the db
        const request = new XMLHttpRequest();
        request.open("get", "/../js_actions/api_remove_shopping_cart.php?" + encodeForAjax({productID: product}), true);
        request.send();

        row.remove();

        //check if there is still any item in the shopping cart
        const root = document.querySelector('#shoppingCart');
        const rows = document.querySelectorAll('#shoppingCart tr');
        if(rows.length === 2){
          const table = document.querySelector('.productsTable');
          const buyButton = document.querySelector('#buyButton');
          buyButton.remove();
          table.remove();


          const newArticle = document.createElement('article');
          const newP = document.createElement('p');
          newP.innerHTML = "No products in the cart";
          newArticle.appendChild(newP);
          root.appendChild(newArticle);
        }
      });
    
}

function addOptionsToPay(){
  const selectElement = document.querySelector("#paymentMethod");

  if(selectElement == null){
    //this isn't the correct section
    return;
  }
  const paymentlabel = document.querySelector("#payment label:nth-child(2");


  //create the input exclusive of credit option
  const newLabel = document.createElement('label');
  const newP = document.createElement('p');
  const newInput = document.createElement('input');
  newInput.setAttribute("type", "text");
  newInput.setAttribute("required", "true");
  newP.innerHTML = "Security Code";
  newLabel.appendChild(newP);
  newLabel.appendChild(newInput);


  selectElement.addEventListener("change", (event) => {
    if(event.target.value == "card"){
      paymentlabel.querySelector('p').innerHTML = "Card Number";
      paymentlabel.insertAdjacentElement( "afterend", newLabel);
    }
    else{
      newLabel.remove();
      paymentlabel.querySelector('p').innerHTML = "Account number";
    }
  });
}

removeItensCart();
addOptionsToPay();