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

    const offsetX = event.clientX - elementRect.left;
    const offsetY = event.clientY - elementRect.top;

    // Initialize with current position so it doesn’t “jump”
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

    function onMouseMove(e) {
        const bounds = element.parentElement.getBoundingClientRect();

        newX = e.clientX - offsetX - bounds.left;
        newY = e.clientY - offsetY - bounds.top;

        // Optional boundaries
        // const width = element.offsetWidth;
        // const height = element.offsetHeight;
        // newX = Math.max(0, Math.min(newX, bounds.width - width));
        // newY = Math.max(0, Math.min(newY, bounds.height - height));
    }

    function onMouseUp() {
        isDragging = false;
        element.style.cursor = "";
        element.style.transition = "all 0.18s ease"; // enable smooth release

        document.removeEventListener("mousemove", onMouseMove);
        document.removeEventListener("mouseup", onMouseUp);

        element.dispatchEvent(
            new CustomEvent("drag-end", {
                detail: { 
                    x: parseInt(element.style.left, 10) || 0,
                    y: parseInt(element.style.top, 10) || 0,
                },
            })
        );
    }

    // Listeners
    document.addEventListener("mousemove", onMouseMove, { passive: false });
    document.addEventListener("mouseup", onMouseUp, { once: true });

    // Start loop
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
