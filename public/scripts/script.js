function formSubmit(formId) {
  return document.getElementById(formId).submit();
}

function submitAction(formId) {
  const form = document.getElementById(formId);
  const checked = Array.from(
    document.querySelectorAll(".delete-checkbox:checked"),
  );

  if (checked.length === 0) {
    alert("Please select product(s).");
    return;
  }

  if (formId === "formEdit" && checked.length !== 1) {
    alert("Please select exactly one product to edit.");
    return;
  }

  form.querySelectorAll("input[name='product[]']").forEach((el) => el.remove());

  checked.forEach((cb) => {
    const input = document.createElement("input");
    input.type = "hidden";
    input.name = "product[]";
    input.value = cb.value;
    form.appendChild(input);
  });

  form.submit();
}

function updateActionButtons() {
  const items = document.querySelectorAll(".product-item");
  const editBtn = document.getElementById("edit-product-btn");
  const deleteBtn = document.getElementById("delete-product-btn");

  const checkedCount = Array.from(items).filter((item) => {
    const cb = item.querySelector(".delete-checkbox");
    return cb && cb.checked;
  }).length;

  if (checkedCount === 0) {
    editBtn.disabled = true;
    deleteBtn.disabled = true;
  } else if (checkedCount === 1) {
    editBtn.disabled = false;
    deleteBtn.disabled = false;
  } else {
    editBtn.disabled = true;
    deleteBtn.disabled = false;
  }
}

function showProductSpecs() {
  const select = document.getElementById("productType");
  const selectedId = select.value;
  const selectedName = select.options[select.selectedIndex].text.trim();

  const categories = ["DVD", "Furniture", "Book"];

  categories.forEach((cat) => {
    const block = document.getElementById(cat);
    if (!block) return;

    const inputs = block.querySelectorAll("input");

    if (cat.toLowerCase() === selectedName.toLowerCase()) {
      block.style.display = "block";
      inputs.forEach((i) => i.setAttribute("required", ""));
    } else {
      block.style.display = "none";
      inputs.forEach((i) => i.removeAttribute("required"));
    }
  });
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

      updateActionButtons();
    });
  });

  updateActionButtons();
});
