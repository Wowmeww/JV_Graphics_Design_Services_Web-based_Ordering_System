function initializeDragAndDrop(event) {
    // Prevent default to avoid text selection during drag
    event.preventDefault();

    const element = event.currentTarget;

    // Only proceed if the element is draggable (could also check a data-draggable attribute)
    if (element.getAttribute('draggable') === 'false') return;

    // Store initial positions and cursor offset
    const startX = element.offsetLeft;
    const startY = element.offsetTop;
    const offsetX = event.clientX - element.getBoundingClientRect().left;
    const offsetY = event.clientY - element.getBoundingClientRect().top;

    // Ensure element has positioning and higher z-index during drag
    element.style.cursor = 'grabbing';

    // Boundary constraints (optional)
    const parentRect = element.parentElement.getBoundingClientRect();
    const maxX = parentRect.width - element.offsetWidth;
    const maxY = parentRect.height - element.offsetHeight;

    // Mouse move handler
    function onMouseMove(e) {
        // Calculate new position with boundary checks
        let newX = e.clientX - offsetX - parentRect.left;
        let newY = e.clientY - offsetY - parentRect.top;

        // // Constrain within parent boundaries
        // newX = Math.max(0, Math.min(newX, maxX));
        // newY = Math.max(0, Math.min(newY, maxY));

        // Apply new position with smooth transition
        element.style.left = `${newX}px`;
        element.style.top = `${newY}px`;
    }

    // Mouse up handler
    function onMouseUp() {
        // Restore cursor and remove temp styles
        element.style.cursor = '';
        element.style.transition = 'all 0.18s ease';

        // Snap to grid (optional)
        // const snappedX = Math.round(parseInt(element.style.left) / 10) * 10;
        // const snappedY = Math.round(parseInt(element.style.top) / 10) * 10;
        // element.style.left = `${snappedX}px`;
        // element.style.top = `${snappedY}px`;

        // Clean up event listeners
        document.removeEventListener('mousemove', onMouseMove);
        document.removeEventListener('mouseup', onMouseUp);

        // Dispatch custom event
        element.dispatchEvent(
            new CustomEvent('drag-end', {
                detail: {
                    x: parseInt(element.style.left),
                    y: parseInt(element.style.top),
                },
            }),
        );
    }

    // Add event listeners with passive: false for better control
    document.addEventListener('mousemove', onMouseMove, { passive: false });
    document.addEventListener('mouseup', onMouseUp, { once: true });

    // Dispatch custom drag start event
    element.dispatchEvent(
        new CustomEvent('drag-start', {
            detail: { startX, startY },
        }),
    );
}

export default initializeDragAndDrop;