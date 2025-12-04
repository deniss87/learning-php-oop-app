function showProductSpecs() {
  const listLength = document.querySelectorAll("#productType option").length;
  const selectedId = document.querySelector("option:checked").value;
  const selectedName = document.querySelectorAll("#productType > option")[
    selectedId
  ].innerText;

  for (let i = 1; i < listLength; i++) {
    let name = document.querySelectorAll("#productType > option")[i].innerText;
    let id = document.querySelectorAll("#productType > option")[i].value;

    if (name !== selectedName) {
      document.getElementById(name).style.display = "none";
      // remove 'required' attribute from selected category fields
      let inputRequiredCount = document.querySelectorAll(
        `#${name} > input`,
      ).length;
      let inputRequired = document.querySelectorAll(`#${name} > input`);
      for (let c = 0; c < inputRequiredCount; c++) {
        requiredId = document.querySelectorAll(`#${name} > input`)[c].id;
        document.getElementById(requiredId).removeAttribute("required");
      }
    } else {
      document.getElementById(name).style.display = "block";
      // set 'required' attribute to selected category fields
      let inputRequiredCount = document.querySelectorAll(
        `#${name} > input`,
      ).length;
      let inputRequired = document.querySelectorAll(`#${name} > input`);
      for (let c = 0; c < inputRequiredCount; c++) {
        requiredId = document.querySelectorAll(`#${name} > input`)[c].id;
        document.getElementById(requiredId).setAttribute("required", "");
      }
    }
  }
}
function formSubmit(formId) {
  return document.getElementById(formId).submit();
}
function formVerify() {
  let messageBox = document.querySelector(".message-text");
  messageBox.innerHTML = "";
  const formList = document.querySelectorAll("form input, select");

  for (let i = 0; i < formList.length; i++) {
    if (formList[i].required & (formList[i].value == "")) {
      console.log("Please enter ", formList[i].getAttribute("inputname"));
      messageBox.innerHTML = `<div class="message-error"><p>Please fill in the "${formList[
        i
      ].getAttribute("inputname")}" field </p>`;
      formList[i].focus();
      return;
    } else if (formList[i].required & (formList[i].value == 0)) {
      console.log("Please select product category");
      messageBox.innerHTML = `<div class="message-error"><p>Please select "Product category"</p>`;
      formList[i].focus();
      return;
    }
  }
  formSubmit("product_form");
}
function formSortSelect() {
  const UrlSortValue = new URLSearchParams(window.location.search).get("sort");
  if (UrlSortValue !== null) {
    const sortList = document.querySelectorAll("#selectSort > option");

    for (let i = 0; i < sortList.length; i++) {
      let sortListId = sortList[i].value;

      if (sortListId == UrlSortValue) {
        sortList[i].setAttribute("selected", "");
      }
    }
  }
}

// document.addEventListener("DOMContentLoaded", () => {
//   const productCards = document.querySelectorAll(".product-item");

//   productCards.forEach((card) => {
//     card.addEventListener("click", (e) => {
//       if (e.target.tagName === "A") return;

//       const checkbox = card.querySelector(".delete-checkbox");
//       if (checkbox) {
//         checkbox.checked = !checkbox.checked;
//       }
//     });
//   });
// });

document.addEventListener("DOMContentLoaded", () => {
  const items = document.querySelectorAll(".product-item");

  items.forEach((item) => {
    item.addEventListener("click", () => {
      const checkbox = item.querySelector(".delete-checkbox");

      checkbox.checked = !checkbox.checked;

      if (checkbox.checked) {
        item.classList.add("active");
      } else {
        item.classList.remove("active");
      }
    });
  });
});
