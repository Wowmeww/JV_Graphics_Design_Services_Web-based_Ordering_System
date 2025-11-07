function initializeDragAndDrop(event) {
    event.preventDefault();

    const el = event.currentTarget;
    if (el.getAttribute("draggable") === "false") return;

    // Ensure absolute positioning
    if (getComputedStyle(el).position === "static") {
        el.style.position = "absolute";
    }

    const parent = el.parentElement;
    const parentRect = parent.getBoundingClientRect();
    const elRect = el.getBoundingClientRect();

    const isTouch = event.type.startsWith("touch");
    const startX = isTouch ? event.touches[0].clientX : event.clientX;
    const startY = isTouch ? event.touches[0].clientY : event.clientY;

    const offsetX = startX - elRect.left;
    const offsetY = startY - elRect.top;

    let newX = el.offsetLeft;
    let newY = el.offsetTop;
    let dragging = true;

    el.style.cursor = "grabbing";
    el.style.transition = "none";

    // Pointer position updater
    const moveHandler = (e) => {
        const clientX = e.touches ? e.touches[0].clientX : e.clientX;
        const clientY = e.touches ? e.touches[0].clientY : e.clientY;

        newX = clientX - offsetX - parentRect.left;
        newY = clientY - offsetY - parentRect.top;

        // Optional boundaries:
        // const w = el.offsetWidth;
        // const h = el.offsetHeight;
        // newX = Math.max(0, Math.min(newX, parentRect.width - w));
        // newY = Math.max(0, Math.min(newY, parentRect.height - h));
    };

    const endHandler = () => {
        dragging = false;
        el.style.cursor = "";
        el.style.transition = "all 0.18s ease";

        document.removeEventListener("mousemove", moveHandler);
        document.removeEventListener("mouseup", endHandler);
        document.removeEventListener("touchmove", moveHandler);
        document.removeEventListener("touchend", endHandler);

        el.dispatchEvent(
            new CustomEvent("drag-end", {
                detail: {
                    x: parseInt(el.style.left) || 0,
                    y: parseInt(el.style.top) || 0,
                },
            })
        );
    };

    // Movement loop
    const tick = () => {
        if (!dragging) return;
        el.style.left = `${newX}px`;
        el.style.top = `${newY}px`;
        requestAnimationFrame(tick);
    };

    // Listeners
    document.addEventListener("mousemove", moveHandler, { passive: false });
    document.addEventListener("mouseup", endHandler, { once: true });
    document.addEventListener("touchmove", moveHandler, { passive: false });
    document.addEventListener("touchend", endHandler, { once: true });

    requestAnimationFrame(tick);

    // Fire drag-start
    el.dispatchEvent(
        new CustomEvent("drag-start", {
            detail: { x: el.offsetLeft, y: el.offsetTop },
        })
    );
}

export default initializeDragAndDrop;
