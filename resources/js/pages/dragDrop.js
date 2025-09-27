function initializeDragAndDrop(event) {
    event.preventDefault();

    const element = event.currentTarget;
    if (element.getAttribute("draggable") === "false") return;

    // Ensure element is absolutely positioned
    if (getComputedStyle(element).position === "static") {
        element.style.position = "absolute";
    }

    const parentRect = element.parentElement.getBoundingClientRect();
    const elementRect = element.getBoundingClientRect();

    const startX = ("touches" in event ? event.touches[0].clientX : event.clientX);
    const startY = ("touches" in event ? event.touches[0].clientY : event.clientY);

    const offsetX = startX - elementRect.left;
    const offsetY = startY - elementRect.top;

    let newX = element.offsetLeft;
    let newY = element.offsetTop;
    let isDragging = true;

    element.style.cursor = "grabbing";
    element.style.transition = "none"; // disable animation while dragging

    // Smooth updates via RAF
    function updatePosition() {
        if (!isDragging) return;
        element.style.left = `${newX}px`;
        element.style.top = `${newY}px`;
        requestAnimationFrame(updatePosition);
    }

    function moveHandler(e) {
        const clientX = e.type.startsWith("touch")
            ? e.touches[0].clientX
            : e.clientX;
        const clientY = e.type.startsWith("touch")
            ? e.touches[0].clientY
            : e.clientY;

        const bounds = element.parentElement.getBoundingClientRect();

        newX = clientX - offsetX - bounds.left;
        newY = clientY - offsetY - bounds.top;

        // Optional boundaries
        // const width = element.offsetWidth;
        // const height = element.offsetHeight;
        // newX = Math.max(0, Math.min(newX, bounds.width - width));
        // newY = Math.max(0, Math.min(newY, bounds.height - height));
    }

    function endHandler() {
        isDragging = false;
        element.style.cursor = "";
        element.style.transition = "all 0.18s ease";

        document.removeEventListener("mousemove", moveHandler);
        document.removeEventListener("mouseup", endHandler);
        document.removeEventListener("touchmove", moveHandler);
        document.removeEventListener("touchend", endHandler);

        element.dispatchEvent(
            new CustomEvent("drag-end", {
                detail: {
                    x: parseInt(element.style.left, 10) || 0,
                    y: parseInt(element.style.top, 10) || 0,
                },
            })
        );
    }

    // Listeners for both mouse + touch
    document.addEventListener("mousemove", moveHandler, { passive: false });
    document.addEventListener("mouseup", endHandler, { once: true });
    document.addEventListener("touchmove", moveHandler, { passive: false });
    document.addEventListener("touchend", endHandler, { once: true });

    requestAnimationFrame(updatePosition);

    element.dispatchEvent(
        new CustomEvent("drag-start", {
            detail: {
                x: element.offsetLeft,
                y: element.offsetTop,
            },
        })
    );
}

export default initializeDragAndDrop;
