function fetchEvents() {
    fetch('fetch_events.php') // or your combined PHP file with ?action=fetch
        .then(response => response.json())
        .then(events => {
            console.log(events);
            const container = document.getElementById('events-container');
            container.innerHTML = ''; // Clear old cards

            events.forEach(event => {
                const card = document.createElement('div');
                card.className = 'card';

                // Build image URL - adjust path as needed
                const imageUrl = event.image ? `images/events/${escapeHtml(event.image)}` : 'images/events/default.png';

                card.innerHTML = `
                            <img src="${imageUrl}" alt="${escapeHtml(event.event_name)}">
                            <div class="card-content">
                                <h3>${escapeHtml(event.event_name)}</h3>
                                <p><strong>Date:</strong> ${escapeHtml(event.event_date)}</p>
                                <p><strong>Time:</strong> ${escapeHtml(event.event_time)}</p>
                                <p><strong>Location:</strong> ${escapeHtml(event.location)}</p>
                                <p><strong>Ticket Price:</strong> $${escapeHtml(event.ticket_price)}</p>
                                <p><strong>Available Seats:</strong> ${escapeHtml(event.all_seat)}</p>
                                <form action="book.php" method="post">
                                    <input type="hidden" name="event_id" value="${event.id}">
                                    <button type="submit">Book Now</button>
                                </form>
                            </div>
                        `;

                container.appendChild(card);
            });
        })
        .catch(err => {
            console.error('Error fetching events:', err);
        });
}

// Escape HTML to avoid XSS
function escapeHtml(text) {
    if (typeof text !== 'string') {
        return text;
    }
    return text.replace(/[&<>"']/g, function (m) {
        return {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        } [m];
    });
}

// Initial fetch
fetchEvents();

// Refresh every 5 seconds
setInterval(fetchEvents, 5000);