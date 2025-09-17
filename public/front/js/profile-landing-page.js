// Get data from window variable
const profileData = window["profile-landing-page"];
const user = profileData.userPlan;
const userId = profileData.userId;
const isFreeUser = profileData.isFreeUser;
document.addEventListener("DOMContentLoaded", function () {
    const tabs = document.querySelectorAll(".tab");
    const contentWrapper = document.getElementById("meal-cards-wrapper");
    const scrollContainer = document.getElementById("meal-cards-wrapper");
    const leftArrow = document.querySelector(".left-arrow");
    const rightArrow = document.querySelector(".right-arrow");
    const scrollAmount = 300;

    // Show arrows only if 4 or more cards exist
    // Show arrows only if 4 or more cards exist
    function updateArrowVisibility() {
        if (!scrollContainer || !leftArrow || !rightArrow) {
            return;
        }

        const cards = scrollContainer.querySelectorAll(".challenge-card");
        const shouldShowArrows = cards.length > 4;

        if (shouldShowArrows) {
            leftArrow.style.display = "block";
            rightArrow.style.display = "block";
            leftArrow.classList.remove('hidden');
            rightArrow.classList.remove('hidden');
        } else {
            leftArrow.style.display = "none";
            rightArrow.style.display = "none";
            leftArrow.classList.add('hidden');
            rightArrow.classList.add('hidden');
        }
    }

    // Scroll behavior
    if (leftArrow && rightArrow && scrollContainer) {
        leftArrow.addEventListener("click", () => {
            scrollContainer.scrollBy({
                left: -scrollAmount,
                behavior: "smooth",
            });
        });

        rightArrow.addEventListener("click", () => {
            scrollContainer.scrollBy({
                left: scrollAmount,
                behavior: "smooth",
            });
        });
    }

    // Call once after load
    updateArrowVisibility();

    function loadMeals(planId, categoryId) {
        contentWrapper.innerHTML = "<p>Loading meals...</p>";

        // Laravel route with placeholders
        const baseUrl = profileData.routes.getProfileMeals;
        const fetchUrl = baseUrl
            .replace("PLAN_ID", planId)
            .replace("CATEGORY_ID", categoryId);

        fetch(fetchUrl, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((response) => {
                if (!response.ok) throw new Error("Fetch failed");
                return response.text();
            })
            .then((html) => {
                contentWrapper.innerHTML = html;
                // 🔥 Important: Wait for DOM to update, then check arrows
                requestAnimationFrame(() => {
                    updateArrowVisibility();
                });
            })
            .catch(() => {
                contentWrapper.innerHTML = "<p>Error loading meals.</p>";
            });
    }

    // Click event for each tab
    tabs.forEach((tab) => {
        tab.addEventListener("click", function () {
            // Remove active class from all
            tabs.forEach((t) => t.classList.remove("active"));
            this.classList.add("active");

            const categoryId = this.dataset.categoryId;
            const planId = this.dataset.planId;
            loadMeals(planId, categoryId);
        });
    });

    // 🔥 Load meals for the first tab by default
    const firstTab = document.querySelector(".tab.active");
    if (firstTab) {
        loadMeals(firstTab.dataset.planId, firstTab.dataset.categoryId);
    }

    $("#start-chat-link, #chat-to-virtual-kez-btn").click(function () {
        $("#delphi-bubble-trigger").click();
    });
});

function showLoader() {
    $("#loader").removeClass("d-none");
}

function hideLoader() {
    $("#loader").addClass("d-none");
}

$(document).ready(function () {
    // Open Bootstrap modal on meal click
    $("body").on("click", ".quick-view-btn", function () {
        if (isFreeUser == 1) {
            return;
        }

        const user_meal_id = $(this).data("meal-id");
        const user_plan_id = $(this).data("user-plan-id");
        const user_sub_category_id = $(this).data("sub-category-id");
        const user_category_id = $(this).data("category-id");
        showLoader();

        $.ajax({
            url: profileData.routes.mealDetails,
            type: "POST",
            data: {
                _token: profileData.csrfToken,
                user_meal_id,
                user_plan_id,
                user_sub_category_id,
                user_category_id,
            },
            success: function (response) {
                const meal = response.meal;

                // 🖼️ Set meal title and description
                $("#recipeDialogModal .modal-body .dialog-header h2").text(
                    meal.meal.title || "Meal"
                );
                $("#recipeDialogModal .modal-body .dialog-header p").text(
                    meal.meal.description || ""
                );

                const imageUrl = meal.meal.image
                    ? profileData.assets.storage + "/" + meal.meal.image
                    : profileData.assets.frontImages + "/placeholder.png";
                $("#recipeDialogModal .modal-body .dialog-img").attr(
                    "src",
                    imageUrl
                );

                // 🥣 Ingredients
                let ingredientsHtml = "";
                meal.user_items.forEach(function (userItem) {
                    const item = userItem.item;
                    if (!item) return;

                    const selectedUnits = item.selected_qty_unit || [];
                    const selected =
                        selectedUnits.find((u) => u.checked) || null;

                    let qty = "";
                    let unit = "";

                    if (selected) {
                        qty = selected.qty;
                        unit = selected.unit?.trim();
                    } else {
                        qty = item.qty;
                        unit = item.unit?.trim();
                    }

                    const noSpaceUnits = ["g", "ml", "mL"];
                    const space = noSpaceUnits.includes(unit) ? "" : " ";

                    ingredientsHtml += `<li>${qty}${space}${unit} ${item.title}</li>`;
                });

                if (response.isFreeUser) {
                    $("#recipeDialogModal .modal-body .smart-swap-btn").hide();
                } else {
                    $("#recipeDialogModal .modal-body .smart-swap-btn").show();
                }

                $("#recipeDialogModal .modal-body ul").html(ingredientsHtml);

                // 📝 Instructions / Note
                if (meal.meal.note && meal.meal.note.trim() !== "") {
                    $("#recipeDialogModal .modal-body .note")
                        .html(`<strong>Note:</strong> ${meal.meal.note}`)
                        .show();
                    $(
                        '#recipeDialogModal .modal-body h3:contains("Instructions")'
                    ).show();
                } else {
                    $("#recipeDialogModal .modal-body .note").hide();
                    $(
                        '#recipeDialogModal .modal-body h3:contains("Instructions")'
                    ).hide();
                }
                // $('#recipeDialogModal .modal-body h3:contains("Instructions")').hide();

                // 🔢 Nutrition Info
                $("#recipeDialogModal .modal-body .nutrition-info").html(`
                    <span style="color: #a60015">●  <span style="color:rgba(59, 59, 59, 1)">Protein: ${(
                        Number(response.totalProtein) || 0
                    ).toFixed(2)} g</span></span>
                    <span style="color: #3e8e00">●  <span style="color:rgba(59, 59, 59, 1)">Carb: ${(
                        Number(response.totalCarbs) || 0
                    ).toFixed(2)} g</span></span>
                    <span style="color: #0077b6">●  <span style="color:rgba(59, 59, 59, 1)">Fat: ${(
                        Number(response.totalFats) || 0
                    ).toFixed(2)} g</span></span>
                    <span style="color: #967500">●  <span style="color:rgba(59, 59, 59, 1)">Energy: ${(
                        Number(response.totalEnergy) || 0
                    ).toFixed(2)} kJ</span></span>
                `);

                // Set data attributes for Smart Swap
                $("#recipeDialogModal .modal-body .smart-swap-btn")
                    .attr("data-meal-id", user_meal_id)
                    .attr("data-user-plan-id", user_plan_id)
                    .attr("data-sub-category-id", user_sub_category_id)
                    .attr("data-category-id", user_category_id)
                    .attr("data-meal-name", meal.meal.title);

                // 👁️ Show Bootstrap modal
                const modal = new bootstrap.Modal(
                    document.getElementById("recipeDialogModal")
                );
                modal.show();
                hideLoader();
            },
            error: function () {
                $("#errormodalmain").modal("show");
                hideLoader();
            },
        });
    });

    $(document).on("hide.bs.modal", "#recipeDialogModal", function () {
        // Clear the modal content when it is closed
        $("#recipeDialogModal .modal-body .dialog-header h2").text("");
        $("#recipeDialogModal .modal-body .dialog-header p").text("");
        $("#recipeDialogModal .modal-body .dialog-img").attr("src", "");
        $("#recipeDialogModal .modal-body ul").empty();
        $("#recipeDialogModal .modal-body .note").hide();
        $('#recipeDialogModal .modal-body h3:contains("Instructions")').hide();
        $("#recipeDialogModal .modal-body .nutrition-info").empty();
        $(".modal-backdrop").remove();
    });

    $(document).on("click", ".meal-item-btn", function () {
        const $btn = $(this);

        const meal_id = $btn.attr("data-meal-id");
        const meal_name = $btn.attr("data-meal-name");
        const user_meal_id = $btn.attr("data-meal-id");
        const userPlanId = $btn.attr("data-user-plan-id");
        const userSubCategoryId = $btn.attr("data-sub-category-id");
        const userCategoryId = $btn.attr("data-category-id");

        $("#recipeDialogModal").modal("hide");
        mealItemModelReload(
            meal_id,
            meal_name,
            user_meal_id,
            userSubCategoryId,
            userPlanId,
            userCategoryId
        );
    });

    function mealItemModelReload(
        meal_id,
        meal_name,
        user_meal_id,
        userSubCategoryId,
        userPlanId,
        userCategoryId
    ) {
        const modalEl = $("#mealItemModel");
        const modal = new bootstrap.Modal(modalEl[0]);
        modal.show();

        const $mealItemsModalLabel = $(".swap-title"); // Set meal name here
        const $mealItemsContainer = $(".swap-list"); // Container for item cards
        const $mealItemsLoadingSpinner = $("#mealItemsLoadingSpinner"); // Optional: add loading spinner if you want

        if (!user_meal_id || !meal_name) {
            console.error("Invalid meal data.");
            return;
        }

        $mealItemsModalLabel.text(meal_name);
        $mealItemsContainer.empty();

        $.ajax({
            url:
                profileData.routes.mealsItems.replace(":mealId", meal_id) +
                `?user_meal_id=${user_meal_id}&user_plan_id=${userPlanId}&user_sub_category_id=${userSubCategoryId}&user_category_id=${userCategoryId}`,
            method: "GET",
            dataType: "json",
            success: function (data) {
                if (data.items && data.items.length > 0) {
                    $.each(data.items, function (index, item) {
                        let displayQty = "";
                        let selectedUnits = [];

                        try {
                            selectedUnits =
                                typeof item.selected_qty_unit === "string"
                                    ? JSON.parse(item.selected_qty_unit)
                                    : Array.isArray(item.selected_qty_unit)
                                    ? item.selected_qty_unit
                                    : [];
                        } catch (e) {
                            console.warn(
                                "Failed to parse selected_qty_unit for item:",
                                item.name,
                                e
                            );
                        }

                        const checkedUnits = selectedUnits.filter(
                            (u) =>
                                u.checked === true ||
                                u.checked === "true" ||
                                u.checked === 1 ||
                                u.checked === "1"
                        );

                        if (checkedUnits.length > 0) {
                            const formattedUnits = checkedUnits.map((u) => {
                                let qtyText = u.qty?.toString().trim() || "";
                                const unitText = (u.unit || "").trim();
                                const needsSpace = !["g", "ml", "mL"].includes(
                                    unitText.toLowerCase()
                                );

                                const numericQty = Number(qtyText);
                                if (!isNaN(numericQty)) {
                                    qtyText =
                                        numericQty % 1 === 0
                                            ? numericQty.toFixed(0)
                                            : numericQty.toFixed(1);
                                }

                                return `${qtyText}${
                                    needsSpace ? " " : ""
                                }${unitText}`;
                            });

                            displayQty = formattedUnits.join(" or ");
                        }

                        if (!displayQty && item.qty && item.unit) {
                            const unit = item.unit.toString();
                            const needsSpace = !["g", "ml", "mL"].includes(
                                unit.toLowerCase()
                            );
                            displayQty = `${item.qty}${
                                needsSpace ? " " : ""
                            }${unit}`;
                        }

                        const itemCard = `
                            <div class="swap-item">
                                <img src="${item.image}" alt="${
                            item.name
                        }" class="swap-item-img" />
                                <div class="flex-wrapper">

                                <div class="swap-item-info">
                                    <div class="swap-item-name">${
                                        item.name
                                    }</div>
                                    <div class="swap-item-qty"><b>Qty :</b> ${displayQty}</div>
                                </div>
                                <div class="swap-item-actions">
                                    ${
                                        item.swapItems?.length > 0
                                            ? `
                                            <button class="smart-swap-btn item-swap-btn"
                                                data-item-id="${item.id}"
                                                data-item-name="${item.name}"
                                                data-user-item-id="${item.user_item_id}"
                                                data-user-meal-id="${item.user_meal_id}"
                                                data-user-plan-id="${userPlanId}"
                                                data-sub-category-id="${userSubCategoryId}"
                                                data-user-category-id="${userCategoryId}">
                                                <img src="${profileData.assets.frontAssets}/images/dialog/swap.svg" style="width: 18px; vertical-align: middle; margin-right: 4px;" />
                                                <span>Swap</span>
                                            </button>`
                                            : ""
                                    }
                                    ${
                                        item.description
                                            ? `
                                            <button class="smart-swap-btn" data-bs-toggle="tooltip" title="${item.description}">
                                                <img src="${profileData.assets.frontAssets}/images/dialog/Info.svg" alt="Info" style="width: 24px; vertical-align: middle" />
                                            </button>`
                                            : ""
                                    }
                                </div>
                                </div>
                                </div>
                            </div>
                        `;

                        $mealItemsContainer.append(itemCard);
                    });
                    $('[data-bs-toggle="tooltip"]').tooltip();
                } else {
                    $mealItemsContainer.html(
                        '<p class="text-center">No foods available in this meal.</p>'
                    );
                }

                // Optional: hide spinner
                // $mealItemsLoadingSpinner.hide();
            },
            error: function () {
                $mealItemsContainer.html(
                    '<p class="text-danger text-center">Failed to load foods.</p>'
                );
                // $mealItemsLoadingSpinner.hide();
            },
        });
    }

    $(document).on("click", ".meal-item-modal-close", function () {
        const modalEl = $("#mealItemModel")[0];
        const modalInstance = bootstrap.Modal.getInstance(modalEl);

        if (modalInstance) {
            modalInstance.hide();
        } else {
            // fallback if instance wasn't created by Bootstrap JS
            const newModal = new bootstrap.Modal(modalEl);
            newModal.hide();
        }
    });

    $(document).on("click", ".item-swap-btn", function () {
        const itemId = $(this).data("item-id");
        const itemName = $(this).data("item-name");
        const userItemId = $(this).data("user-item-id");
        const userMealId = $(this).data("user-meal-id");
        const userPlanId = $(this).data("user-plan-id");
        const userSubCategoryId = $(this).data("sub-category-id");
        const userCategoryId = $(this).data("user-category-id");

        if (!itemId || !itemName) {
            console.error("Invalid item data.");
            return;
        }

        $(".apply-changes-btn").attr("data-user-item-id", userItemId);
        $(".apply-changes-btn").attr("data-user-meal-id", userMealId);
        $(".apply-changes-btn").attr("data-user-plan-id", userPlanId);
        $(".apply-changes-btn").attr(
            "data-user-sub-category-id",
            userSubCategoryId
        );
        $(".apply-changes-btn").attr("data-user-category-id", userCategoryId);

        // Show the modal first
        const modal = new bootstrap.Modal(
            document.getElementById("smartSwapModal")
        );
        modal.show();

        // Update the title in the modal
        $("#smartSwapModalLabel").text(`Swap: ${itemName}`);

        // Clear existing items
        const $swapList = $("#smartSwapModal .swap-list");
        $swapList.html(`
            <div class="py-4 text-center">
                <div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>
            </div>
        `);

        // Perform AJAX request to fetch swap items
        $.ajax({
            url:
                profileData.routes.itemsSwapItems.replace(":id", itemId) +
                `?user_meal_id=${userMealId}&user_item_id=${userItemId}&user_plan_id=${userPlanId}&sub_category_id=${userSubCategoryId}&user_category_id=${userCategoryId}`,
            method: "GET",
            dataType: "json",
            success: function (data) {
                if (!data || !data.items || !data.items.length) {
                    $swapList.html(
                        '<p class="text-muted text-center">No swap items available.</p>'
                    );
                    return;
                }

                // ✅ Helper: Format Qty Unit String
                function formatQtyUnit(unitsArray, fallbackQty, fallbackUnit) {
                    if (!unitsArray || !unitsArray.length) {
                        return formatUnitText(fallbackQty, fallbackUnit);
                    }

                    const checked = unitsArray.find((u) => u.checked);
                    if (checked) {
                        return formatUnitText(checked.qty, checked.unit);
                    }

                    return formatUnitText(fallbackQty, fallbackUnit);
                }

                function formatUnitText(qty, unit) {
                    qty = qty ?? "1";
                    unit = (unit || "").trim();

                    if (!unit) return qty;

                    const compactUnits = ["g", "ml", "mL"];
                    if (compactUnits.includes(unit)) {
                        return `${qty}${unit}`;
                    }

                    return `${qty} ${unit}`;
                }

                // ✅ Build Main Item HTML
                const item = data.item;
                const mainQtyText = formatQtyUnit(
                    item.selected_qty_unit,
                    item.qty,
                    item.unit
                );
                let mainItem = `
                    <div class="swap-item" id="mainSwapItem" data-item-id="${
                        item.id
                    }" style="border-bottom: none">
                        <img src="${data.item_image}" alt="${
                    data.item_name
                }" class="swap-item-img"/>
                        <div class="flex-wrapper">
                        <div class="swap-item-info">
                            <div class="swap-item-name">${data.item_name}</div>
                            <div class="swap-item-qty"><b>Qty:</b> ${mainQtyText}</div>
                        </div>
                        <div class="swap-item-actions">
                            ${
                                item.description
                                    ? `
                                    <button class="smart-swap-btn info-btn" data-bs-toggle="tooltip" title="${item.description}">
                                        <img src="${profileData.assets.frontAssets}/images/dialog/Info.svg" style="width: 18px" />
                                    </button>
                                `
                                    : ""
                            }
                        </div>
                        </div>
                    </div>

                    <div class="swap-item swap-item-h3"><h3>Swap with</h3></div>
                `;

                // ✅ Build Swap Items HTML
                let swapItemsHTML = "";
                data.items.forEach(function (swapItem) {
                    const swapQtyText = formatQtyUnit(
                        swapItem.selected_qty_unit,
                        swapItem.swap_item_qty,
                        swapItem.swap_item_unit
                    );

                    swapItemsHTML += `
                        <div class="swap-item">
                            <img src="${swapItem.swap_item_image}" alt="${
                        swapItem.swap_item_name
                    }" class="swap-item-img"/>
                            <div class="flex-wrapper">
                            <div class="swap-item-info">
                                <div class="swap-item-name">${
                                    swapItem.swap_item_name
                                }</div>
                                <div class="swap-item-qty"><b>Qty:</b> ${swapQtyText}</div>
                            </div>
                            <div class="swap-item-actions">
                                <button class="smart-swap-btn swap-btn" data-swap-item-id="${
                                    swapItem.swap_item_id
                                }">
                                    <img src="${
                                        profileData.assets.frontAssets
                                    }/images/dialog/swap.svg" style="width: 18px; margin-right: 4px;" /><span>Swap</span>
                                </button>
                                ${
                                    swapItem.swap_item_description
                                        ? `
                                        <button class="smart-swap-btn info-btn" data-bs-toggle="tooltip" title="${swapItem.swap_item_description}">
                                            <img src="${profileData.assets.frontAssets}/images/dialog/Info.svg" style="width: 18px" />
                                        </button>
                                    `
                                        : ""
                                }
                            </div>
                            </div>
                        </div>
                    `;
                });

                // ✅ Inject into DOM
                $swapList.html(mainItem + swapItemsHTML);

                // ✅ Enable Bootstrap 5 tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
                $("#mealItemModel").modal("hide");
            },
            error: function (xhr, status, error) {
                $swapList.html(
                    '<p class="text-danger text-center">Failed to load swap items.</p>'
                );
                console.error("Error loading swap items:", error);
            },
        });
    });

    $("#mealItemModel").on("hidden.bs.modal", function () {
        $(".modal-backdrop").remove();
        $("#mealItemsContainer").empty();
        $("#mealItemsLoadingSpinner").hide();
    });

    let currentMainItem = null;
    let swaps = []; // Array to hold latest swap pair

    $(document).on("click", ".swap-btn", function () {
        const $clickedSwap = $(this).closest(".swap-item");
        const swapItemId = $(this).data("swap-item-id");

        const $mainItem = $("#mainSwapItem"); // ✅ SELECTS THE MAIN ITEM CORRECTLY NOW
        const mainItemId = $mainItem.data("item-id"); // You must set this in HTML: data-item-id="${item.id}"

        // Capture current main item details if not already stored
        if (!currentMainItem) {
            currentMainItem = {
                id: mainItemId,
                name: $mainItem.find(".swap-item-name").text(),
                qty: $mainItem
                    .find(".swap-item-qty")
                    .text()
                    .replace("Qty:", "")
                    .trim(),
                description:
                    $mainItem
                        .find(".info-btn")
                        .attr("data-bs-original-title") || "",
                image: $mainItem.find("img.swap-item-img").attr("src"),
            };
        }

        // Get clicked swap item details
        const swapItem = {
            id: swapItemId,
            name: $clickedSwap.find(".swap-item-name").text(),
            qty: $clickedSwap
                .find(".swap-item-qty")
                .text()
                .replace("Qty:", "")
                .trim(),
            description:
                $clickedSwap.find(".info-btn").attr("data-bs-original-title") ||
                "",
            image: $clickedSwap.find("img.swap-item-img").attr("src"),
        };

        // === Update Main Item UI ===
        $mainItem.find(".swap-item-name").text(swapItem.name);
        $mainItem.find(".swap-item-qty").html("<b>Qty:</b> " + swapItem.qty);
        $mainItem.find("img.swap-item-img").attr("src", swapItem.image);
        $mainItem.find('[data-bs-toggle="tooltip"]').tooltip("dispose");
        $mainItem.find(".info-btn").remove();

        if (swapItem.description) {
            $mainItem.find(".swap-item-actions").append(`
                <button class="smart-swap-btn info-btn" data-bs-toggle="tooltip" title="${swapItem.description}">
                    <img src="${profileData.assets.frontAssets}images/dialog/Info.svg" style="width: 18px" />
                </button>
            `);
        }

        // === Replace clicked swap item with the original main item ===
        const revertedHTML = `
            <img src="${currentMainItem.image}" alt="${
            currentMainItem.name
        }" class="swap-item-img"/>
            <div class="flex-wrapper">
            <div class="swap-item-info">
                <div class="swap-item-name">${currentMainItem.name}</div>
                <div class="swap-item-qty"><b>Qty:</b> ${
                    currentMainItem.qty
                }</div>
            </div>
            <div class="swap-item-actions">
                <button class="smart-swap-btn swap-btn" data-swap-item-id="${
                    currentMainItem.id
                }">
                    <img src="${
                        profileData.assets.frontAssets
                    }images/dialog/swap.svg" style="width: 18px; margin-right: 4px;" /><span>Swap</span>
                </button>
                ${
                    currentMainItem.description
                        ? `
                        <button class="smart-swap-btn info-btn" data-bs-toggle="tooltip" title="${currentMainItem.description}">
                            <img src="${profileData.assets.frontAssets}images/dialog/Info.svg" style="width: 18px" />
                        </button>`
                        : ""
                }
            </div>
            </div>
        `;

        $clickedSwap.html(revertedHTML);

        // ✅ Update swap tracking (replace last entry if already swapped)
        swaps = [
            {
                main_id: swapItem.id,
                swap_id: currentMainItem.id,
                user_item_id: currentMainItem.id, // or use some real user_item_id if needed
            },
        ];

        // Update the reference for next potential swap
        currentMainItem = swapItem;

        // Reinitialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    });

    $("#smartSwapModal").on("hidden.bs.modal", function () {
        currentMainItem = null;
        swaps = []; // Reset swaps array
        $("#smartSwapModalLabel").text(""); // Clear modal title
        $("#smartSwapModal .swap-list").empty(); // Clear HTML inside modal
        $(".modal-backdrop").remove();
    });

    // Apply Swap Changes functionality
    $(document).on("click", ".apply-changes-btn", function () {
        // Send all swaps to the server
        const userItemId = $(this).attr("data-user-item-id");
        const userMealId = $(this).attr("data-user-meal-id");
        const userPlanId = $(this).attr("data-user-plan-id");
        const userSubCategoryId = $(this).attr("data-user-sub-category-id");
        const userCategoryId = $(this).attr("data-user-category-id");

        $.ajax({
            url: profileData.routes.itemsSwaps, // Laravel route to handle the request
            method: "GET",
            data: {
                swaps: swaps,
                meal_id: userMealId,
                user_item_id: userItemId,
                user_meal_id: userMealId,
                user_category_id: userCategoryId,
                user_sub_category_id: userSubCategoryId,
                user_plan_id: userPlanId,
                user_id: userId,
            },
            success: function (response) {
                // Handle success response
                swaps = [];
                currentMainItem = null;

                if (response.success) {
                    $("#smartSwapModal").modal("hide");
                    var meal_id = response.data["meal_id"];
                    var meal_name = response.data["meal_name"];
                    var user_meal_id = response.data["user_meal_id"];
                    mealItemModelReload(
                        meal_id,
                        meal_name,
                        user_meal_id,
                        userSubCategoryId,
                        userPlanId,
                        userCategoryId
                    );
                } else {
                    $("#errormodalmain .modal-body").html(
                        `<h4>Ooops!</h4><p>${response.message}</p>`
                    );
                    $("#errormodalmain").modal("show");
                }
            },
            error: function (xhr, status, error) {
                // Handle error response
                if (xhr.status === 422) {
                    // Laravel-style validation error handling
                    let errors = xhr.responseJSON?.errors;
                    let messageHtml = "";

                    if (errors) {
                        // fallback if error messages not formatted
                        messageHtml =
                            "<h4>Ooops!</h4><p>Invalid swap. Please check and try again.</p>";
                    }

                    $("#errormodalmain .modal-body").html(messageHtml);
                } else {
                    // Generic fallback for other HTTP errors
                    $("#errormodalmain .modal-body").html(
                        "<h4>Ooops!</h4>	<p>Invalid swap. Please try again later.</p>"
                    );
                }

                $("#errormodalmain").modal("show");
            },
        });
    });
});

// Function to open quiz outcome modal
function openCustomCongratsModal() {
    const modal = new bootstrap.Modal(
        document.getElementById("customCongratsModal")
    );
    modal.show();

    // Automatically fetch nutrition score if quiz ID is available
    const quizId = getQuizIdFromStorage();
    if (quizId) {
        fetchAndDisplayNutritionScore(quizId);
    } else {
        // Set default display if no quiz ID is available
        setDefaultNutritionDisplay();
    }
}

// Function to close the modal
function closeCustomCongratsModal() {
    const modal = bootstrap.Modal.getInstance(
        document.getElementById("customCongratsModal")
    );
    if (modal) {
        modal.hide();
    }
}

// Function to get quiz ID from session storage
function getQuizIdFromStorage() {
    // Try to get from completed quiz ID first
    let quizId = sessionStorage.getItem("completed_quiz_id");

    // If still not found, try from quiz state
    if (!quizId) {
        const quizState = sessionStorage.getItem("quiz_state");
        if (quizState) {
            try {
                const state = JSON.parse(quizState);
                quizId = state.quizId;
            } catch (e) {
                console.error("Error parsing quiz state:", e);
            }
        }
    }

    return quizId;
}

// Function to fetch and display nutrition score
function fetchAndDisplayNutritionScore(quizId) {
    if (!quizId) {
        console.log("No quiz ID provided, setting default display");
        setDefaultNutritionDisplay();
        return;
    }

    // Show loading state
    const percentageElement = document.querySelector(".nutrition-percentage");
    const arrowElement = document.querySelector(".nutrition-result");

    if (percentageElement) {
        percentageElement.textContent = "Loading...";
    }

    if (arrowElement) {
        arrowElement.style.transform = "rotate(90deg)"; // Reset to default position
    }

    // Make AJAX request to get nutrition score using jQuery
    $.ajax({
        url: profileData.routes.quizNutritionScore,
        method: "POST",
        headers: { "X-CSRF-TOKEN": profileData.csrfToken },
        data: {
            quiz_id: quizId,
        },
        success: function (response) {
            if (response.success && response.nutrition_score) {
                // Update percentage
                if (percentageElement) {
                    percentageElement.textContent = response.nutrition_percentage + "%";
                }

                // Update arrow rotation
                if (arrowElement) {
                    arrowElement.style.transform = `rotate(${response.arrow_rotation}deg)`;
                }

                // Update feedback if available
                if (response.feedback) {
                    const feedbackElement = document.querySelector(
                        ".nutrition-feedback"
                    );
                    if (feedbackElement) {
                        feedbackElement.textContent = response.feedback;
                    }
                }
            } else {
                console.error(
                    "Error fetching nutrition score:",
                    response.message
                );
                setDefaultNutritionDisplay();
            }
        },
        error: function (xhr) {
            console.error("Error fetching nutrition score:", xhr.responseText);
            setDefaultNutritionDisplay();
        },
    });
}

// Function to set default nutrition display
function setDefaultNutritionDisplay() {
    const percentageElement = document.querySelector(".nutrition-percentage");
    const arrowElement = document.querySelector(".nutrition-result");

    if (percentageElement) {
        percentageElement.textContent = "--%";
    }

    if (arrowElement) {
        arrowElement.style.transform = "rotate(90deg)"; // Default position
    }
}

// Optional: Add event listener for when modal is hidden
document.addEventListener("DOMContentLoaded", function () {
    const modal = document.getElementById("customCongratsModal");
    if (modal) {
        modal.addEventListener("hidden.bs.modal", function () {
            // setup a session storage flag where the popup is opened or not if opened then set it to true so next time when page reload the popup will not be shown again
            sessionStorage.removeItem("customCongratsModalOpened");
            sessionStorage.setItem("customCongratsModalOpened", "true");
            // Any cleanup code can go here
            console.log("Modal closed");
        });
    }

    // Check if user has completed a quiz and show modal automatically
    checkAndShowQuizResults();
});

// Function to check if user has completed a quiz and show results
function checkAndShowQuizResults() {
    const customCongratsModalOpened = sessionStorage.getItem("customCongratsModalOpened");
    if (customCongratsModalOpened) {
        return;
    }
    const quizId = sessionStorage.getItem("completed_quiz_id");
    if (quizId) {
        // Check if this is a completed quiz by looking for nutrition score
        $.ajax({
            url: profileData.routes.quizNutritionScore,
            method: "POST",
            headers: { "X-CSRF-TOKEN": profileData.csrfToken },
            data: {
                quiz_id: quizId,
            },
            success: function (response) {
                if (response.success && response.nutrition_score) {
                    // User has completed a quiz, show the modal
                    setTimeout(() => {
                        openCustomCongratsModal();
                    }, 1000); // Small delay to ensure page is fully loaded
                } else {
                    console.log(
                        "Quiz not completed or no nutrition score found"
                    );
                    // Set default display if no nutrition data
                    setDefaultNutritionDisplay();
                }
            },
            error: function (xhr) {
                console.error(
                    "Error checking quiz completion:",
                    xhr.responseText
                );
                // Set default display on error
                setDefaultNutritionDisplay();
            },
        });
    } else {
        console.log("No quiz data is available");
        // Set default display when no quiz data is available
        setDefaultNutritionDisplay();
    }
}

// Example binding
document.querySelectorAll(".learn-more-btn").forEach((btn) => {
    btn.addEventListener("click", function (e) {
        showLearnMoreTooltip(this, "Pro Plan", e);
    });
});

// Add CSS to fix modal backdrop issues
const modalBackdropFix = document.createElement("style");
modalBackdropFix.textContent = `
    .modal-backdrop.show {
        opacity: 0.5 !important;
    }
    .modal-backdrop.fade {
        opacity: 0 !important;
    }
    .modal-backdrop:not(.show) {
        opacity: 0 !important;
        pointer-events: none !important;
    }
`;
document.head.appendChild(modalBackdropFix);

// Fix for coming soon modal close button
document.addEventListener("DOMContentLoaded", function () {
    const comingSoonModal = document.getElementById("comingSoonModal");
    const comingSoonCloseBtn =
        comingSoonModal?.querySelector(".coming-soon-close");

    if (comingSoonCloseBtn) {
        // Remove the data-bs-dismiss attribute to prevent conflicts
        comingSoonCloseBtn.removeAttribute("data-bs-dismiss");

        comingSoonCloseBtn.addEventListener("click", function (e) {
            e.preventDefault();
            e.stopPropagation();

            // Force hide the modal and remove backdrop
            comingSoonModal.style.display = "none";
            comingSoonModal.classList.remove("show");
            document.body.classList.remove("modal-open");

            // Restore body scroll
            document.body.style.overflow = "";
            document.body.style.paddingRight = "";

            // Remove all modal backdrops
            const backdrops = document.querySelectorAll(".modal-backdrop");
            backdrops.forEach((backdrop) => {
                backdrop.remove();
            });

            // Also try Bootstrap method as backup
            if (typeof bootstrap !== "undefined") {
                const modal = bootstrap.Modal.getInstance(comingSoonModal);
                if (modal) {
                    modal.hide();
                }
            }
        });
    }

    // Also handle the modal opening to ensure proper backdrop management
    document.querySelectorAll(".coming-soon-popup").forEach(function (card) {
        card.addEventListener("click", function (e) {
            var comingSoonModal = document.getElementById("comingSoonModal");
            if (comingSoonModal && typeof bootstrap !== "undefined") {
                e.preventDefault();

                // Remove any existing backdrops first
                const existingBackdrops =
                    document.querySelectorAll(".modal-backdrop");
                existingBackdrops.forEach((backdrop) => backdrop.remove());

                var modal = new bootstrap.Modal(comingSoonModal);
                modal.show();
            }
        });
    });

    // Add event listener for modal hidden event to ensure body scroll is restored
    if (comingSoonModal) {
        comingSoonModal.addEventListener("hidden.bs.modal", function () {
            // Restore body scroll when modal is hidden
            document.body.style.overflow = "";
            document.body.style.paddingRight = "";
            document.body.classList.remove("modal-open");
        });
    }
});
